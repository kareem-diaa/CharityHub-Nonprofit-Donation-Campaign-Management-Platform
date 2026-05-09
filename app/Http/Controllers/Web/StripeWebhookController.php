<?php

namespace App\Http\Controllers\Web;

use App\Events\DonationReceived;
use App\Http\Controllers\Controller;
use App\Models\Campaign;
use App\Models\Donation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Stripe\Exception\SignatureVerificationException;
use Stripe\Stripe;
use Stripe\Webhook;

class StripeWebhookController extends Controller
{
    /**
     * Handle incoming Stripe webhook events.
     * Route: POST /stripe/webhook (CSRF-exempt — see bootstrap/app.php)
     */
    public function handle(Request $request)
    {
        Stripe::setApiKey(config('services.stripe.secret'));

        $payload   = $request->getContent();
        $sigHeader = $request->header('Stripe-Signature');
        $secret    = config('services.stripe.webhook_secret');

        // 1. Verify the webhook signature to reject forged requests
        try {
            $event = Webhook::constructEvent($payload, $sigHeader, $secret);
        } catch (SignatureVerificationException $e) {
            Log::warning('Stripe webhook signature verification failed', [
                'error' => $e->getMessage(),
                'ip'    => $request->ip(),
            ]);
            return response('Invalid signature', 400);
        }

        Log::info('Stripe webhook received', ['type' => $event->type]);

        // 2. Route to the correct handler based on event type
        match ($event->type) {
            'checkout.session.completed'    => $this->handleCheckoutCompleted($event->data->object),
            'invoice.paid'                  => $this->handleInvoicePaid($event->data->object),
            'customer.subscription.deleted' => $this->handleSubscriptionDeleted($event->data->object),
            default                         => null, // Ignore unhandled event types
        };

        return response('Webhook handled', 200);
    }

    // ── checkout.session.completed ────────────────────────────────────────────
    // Fires for BOTH one-time and first subscription payment.
    // For one-time donations this is the primary path.
    // For subscriptions this records the first payment and stores subscription ID.

    private function handleCheckoutCompleted(object $session): void
    {
        // Guard: only handle subscription sessions here.
        // One-time payments are already handled by DonationsController::success().
        if ($session->mode !== 'subscription') {
            return;
        }

        // We store campaign_id in metadata — set this when creating the session
        // if campaign tracking via webhook is needed for subscriptions.
        // For now we match via subscription ID to update any existing pending record.
        $subscriptionId = $session->subscription ?? null;
        if (!$subscriptionId) {
            return;
        }

        // If DonationsController::success() already wrote the record, update it
        $donation = Donation::where('stripe_subscription_id', $subscriptionId)->first();
        if ($donation) {
            $donation->status = 'completed';
            $donation->save();
        }

        Log::info('Subscription checkout completed', [
            'subscription_id' => $subscriptionId,
            'customer'        => $session->customer,
        ]);
    }

    // ── invoice.paid ──────────────────────────────────────────────────────────
    // Fires on every successful recurring charge after the first.
    // Creates a new Donation record for each renewal cycle.

    private function handleInvoicePaid(object $invoice): void
    {
        $subscriptionId = $invoice->subscription ?? null;
        if (!$subscriptionId) {
            return;
        }

        // Find the original donation to get campaign and user context
        $original = Donation::where('stripe_subscription_id', $subscriptionId)
            ->where('type', 'recurring')
            ->latest()
            ->first();

        if (!$original) {
            Log::warning('invoice.paid: no matching recurring donation found', [
                'subscription_id' => $subscriptionId,
            ]);
            return;
        }

        $amountPaid = $invoice->amount_paid / 100; // convert from cents

        DB::beginTransaction();
        try {
            $donation                       = new Donation();
            $donation->campaign_id          = $original->campaign_id;
            $donation->user_id              = $original->user_id;
            $donation->amount               = $amountPaid;
            $donation->type                 = 'recurring';
            $donation->payment_method       = 'Stripe';
            $donation->transaction_id       = $invoice->payment_intent ?? null;
            $donation->stripe_subscription_id = $subscriptionId;
            $donation->idempotency_key      = 'invoice_' . $invoice->id;
            $donation->status               = 'completed';
            $donation->save();

            // Increment campaign raised amount on every renewal
            $campaign = Campaign::find($original->campaign_id);
            if ($campaign && $campaign->status !== 'finished') {
                $campaign->raised_amount += $amountPaid;
                if ($campaign->raised_amount >= $campaign->goal_amount) {
                    $campaign->status = 'finished';
                }
                $campaign->save();
            }

            DB::commit();

            // Fire the domain event for renewal
            event(new DonationReceived($donation));

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to record recurring donation from webhook', [
                'subscription_id' => $subscriptionId,
                'invoice_id'      => $invoice->id,
                'error'           => $e->getMessage(),
            ]);
        }
    }

    // ── customer.subscription.deleted ────────────────────────────────────────
    // Fires when a subscription is cancelled (by the donor or admin).
    // Marks all associated donation records as cancelled.

    private function handleSubscriptionDeleted(object $subscription): void
    {
        $updated = Donation::where('stripe_subscription_id', $subscription->id)
            ->where('type', 'recurring')
            ->update(['status' => 'cancelled']);

        Log::info('Subscription cancelled', [
            'subscription_id' => $subscription->id,
            'records_updated' => $updated,
        ]);
    }
}
