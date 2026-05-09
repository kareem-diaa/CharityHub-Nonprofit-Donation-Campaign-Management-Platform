<?php

namespace App\Listeners;

use App\Events\DonationReceived;
use Illuminate\Support\Facades\Log;

class LogFinancialTransaction
{
    /**
     * Handle the event.
     */
    public function handle(DonationReceived $event): void
    {
        $donation = $event->donation;

        Log::info('Financial Audit: Donation processed', [
            'donation_id'      => $donation->id,
            'campaign_id'      => $donation->campaign_id,
            'user_id'          => $donation->user_id,
            'amount'           => $donation->amount,
            'type'             => $donation->type,
            'transaction_id'   => $donation->transaction_id,
            'subscription_id'  => $donation->stripe_subscription_id,
            'idempotency_key'  => $donation->idempotency_key,
            'status'           => $donation->status,
            'timestamp'        => now()->toDateTimeString(),
        ]);
    }
}
