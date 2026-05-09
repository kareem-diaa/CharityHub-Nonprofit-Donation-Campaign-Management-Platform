<?php

namespace App\Http\Controllers\Web;

use App\Contracts\PaymentGatewayInterface;
use App\Events\DonationReceived;
use App\Http\Controllers\Controller;
use App\Models\Campaign;
use App\Models\Donation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class DonationsController extends Controller
{
    public function __construct(
        protected PaymentGatewayInterface $gateway
    ) {}

    // ── Show donation form ────────────────────────────────────────────────────

    public function create(Campaign $campaign)
    {
        return view('donations.create', compact('campaign'));
    }

    // ── Create Stripe Checkout Session ────────────────────────────────────────

    public function process(Request $request, Campaign $campaign)
    {
        // 1. Guard: campaign already finished
        if ($campaign->status === 'finished' || $campaign->raised_amount >= $campaign->goal_amount) {
            return redirect()->back()->with('error', 'This campaign has already reached its goal.');
        }

        $request->validate([
            'amount' => 'required|numeric|min:1',
            'type'   => 'required|in:one-time,recurring',
        ]);

        // 2. Guard: amount exceeds remaining goal
        $remaining = $campaign->goal_amount - $campaign->raised_amount;
        if ($request->amount > $remaining) {
            return redirect()->back()->with(
                'error',
                'You can only donate up to $' . number_format($remaining, 2) . ' to complete this campaign goal.'
            );
        }

        // 3. Idempotency: generate a deterministic key for this donation intent
        //    and block if a completed donation for the same key already exists.
        $idempotencyKey = hash('sha256', implode('|', [
            auth()->id() ?? 'guest',
            $campaign->id,
            $request->amount,
            $request->type,
            now()->format('Y-m-d'),
        ]));

        if (Donation::where('idempotency_key', $idempotencyKey)->exists()) {
            return redirect()->back()->with(
                'error',
                'It looks like you have already made this donation today. Thank you!'
            );
        }

        // 4. Build gateway params — no Stripe classes here
        $mode = $request->type === 'recurring' ? 'subscription' : 'payment';

        $checkoutUrl = $this->gateway->createCheckoutSession([
            'amount'         => (int) ($request->amount * 100), // cents
            'currency'       => 'usd',
            'product_name'   => 'Donation for: ' . $campaign->title,
            'mode'           => $mode,
            'customer_email' => auth()->user()->email ?? null,
            'success_url'    => route('donations_success', $campaign)
                                . '?session_id={CHECKOUT_SESSION_ID}&amount=' . $request->amount
                                . '&type=' . $request->type
                                . '&ikey=' . $idempotencyKey,
            'cancel_url'     => route('donations_cancel', $campaign),
            'interval'       => 'month',
        ]);

        return redirect($checkoutUrl);
    }

    // ── Handle successful Stripe return ───────────────────────────────────────

    public function success(Request $request, Campaign $campaign)
    {
        $session = $this->gateway->retrieveSession($request->get('session_id'));

        if ($session->payment_status === 'paid' || $session->payment_status === 'no_payment_required') {
            // Idempotency guard: don't double-insert if user refreshes the page
            $idempotencyKey = $request->get('ikey');

            if ($idempotencyKey && Donation::where('idempotency_key', $idempotencyKey)->exists()) {
                return redirect()->route('campaigns_list')
                    ->with('success', 'Your donation was already recorded. Thank you!');
            }

            DB::beginTransaction();
            try {
                $donation                  = new Donation();
                $donation->campaign_id     = $campaign->id;
                $donation->user_id         = auth()->check() ? auth()->id() : null;
                $donation->amount          = $request->amount;
                $donation->type            = $request->get('type', 'one-time');
                $donation->payment_method  = 'Stripe';
                $donation->transaction_id  = $session->payment_intent ?? null;
                $donation->stripe_subscription_id = $session->subscription ?? null;
                $donation->idempotency_key = $idempotencyKey;
                $donation->status          = 'completed';
                $donation->save();

                // Update campaign raised amount (only for one-time; recurring is
                // handled incrementally by the webhook on every invoice.paid)
                if ($donation->type === 'one-time') {
                    $campaign->raised_amount += $request->amount;
                    if ($campaign->raised_amount >= $campaign->goal_amount) {
                        $campaign->status = 'finished';
                    }
                    $campaign->save();
                }

                DB::commit();

                // Fire the domain event
                event(new DonationReceived($donation));

                return redirect()->route('campaigns_list')
                    ->with('success', 'Thank you for your generous donation!')
                    ->with('donation_id', $donation->id);

            } catch (\Exception $e) {
                DB::rollBack();
                Log::error('Donation save failed', [
                    'session_id' => $request->get('session_id'),
                    'error'      => $e->getMessage(),
                    'ip'         => $request->ip(),
                ]);
                return redirect()->route('campaigns_list')
                    ->with('error', 'Something went wrong while saving your donation.');
            }
        }

        Log::warning('Stripe session not paid', [
            'session_id'     => $request->get('session_id'),
            'payment_status' => $session->payment_status,
            'ip'             => $request->ip(),
        ]);

        return redirect()->route('campaigns_list')->with('error', 'Payment failed.');
    }

    // ── Handle cancelled Stripe return ────────────────────────────────────────

    public function cancel(Campaign $campaign)
    {
        return redirect()->route('donations_create', $campaign)
            ->with('error', 'Payment was cancelled.');
    }
}
