<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Campaign;
use App\Models\Donation;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Stripe\Stripe;
use Stripe\Checkout\Session;

class DonationsController extends Controller
{
    public function create(Campaign $campaign)
    {
        return view('donations.create', compact('campaign'));
    }

    public function process(Request $request, Campaign $campaign)
    {
        // 1. Check if campaign is already finished or reached goal
        if ($campaign->status === 'finished' || $campaign->raised_amount >= $campaign->goal_amount) {
            return redirect()->back()->with('error', 'This campaign has already reached its goal. Thank you for your interest!');
        }

        $request->validate([
            'amount' => 'required|numeric|min:1',
            'type' => 'required|in:one-time,recurring',
        ]);

        // 2. Limit donation to remaining goal
        $remaining = $campaign->goal_amount - $campaign->raised_amount;
        $donationAmount = $request->amount;

        if ($donationAmount > $remaining) {
            return redirect()->back()->with('error', 'You can only donate up to $' . number_format($remaining, 2) . ' to complete this campaign goal.');
        }

        Stripe::setApiKey(env('STRIPE_SECRET'));

        $session = Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price_data' => [
                    'currency' => 'usd',
                    'product_data' => [
                        'name' => 'Donation for: ' . $campaign->title,
                    ],
                    'unit_amount' => $request->amount * 100,
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'customer_email' => auth()->user()->email ?? null,
            'success_url' => route('donations_success', $campaign->id) . '?session_id={CHECKOUT_SESSION_ID}&amount=' . $request->amount,
            'cancel_url' => route('donations_cancel', $campaign->id),
        ]);

        return redirect($session->url);
    }

    public function success(Request $request, Campaign $campaign)
    {
        Stripe::setApiKey(env('STRIPE_SECRET'));
        $session = Session::retrieve($request->get('session_id'));

        if ($session->payment_status == 'paid') {
            DB::beginTransaction();
            try {
                $donation = new Donation();
                $donation->campaign_id = $campaign->id;
                $donation->user_id = auth()->check() ? auth()->id() : null;
                $donation->amount = $request->amount;
                $donation->type = 'one-time';
                $donation->payment_method = 'Stripe';
                $donation->transaction_id = $session->payment_intent;
                $donation->idempotency_key = $session->id;
                $donation->status = 'completed';
                $donation->save();

                $campaign->raised_amount += $request->amount;
                if ($campaign->raised_amount >= $campaign->goal_amount) {
                    $campaign->status = 'finished';
                }
                $campaign->save();

                DB::commit();
                return redirect()->route('campaigns_list')->with('success', 'Thank you for your generous donation via Stripe!')->with('donation_id', $donation->id);
            } catch (\Exception $e) {
                DB::rollBack();
                return redirect()->route('campaigns_list')->with('error', 'Something went wrong while saving your donation.');
            }
        }

        return redirect()->route('campaigns_list')->with('error', 'Payment failed.');
    }

    public function cancel(Campaign $campaign)
    {
        return redirect()->route('donations_create', $campaign->id)->with('error', 'Payment was cancelled.');
    }
}
