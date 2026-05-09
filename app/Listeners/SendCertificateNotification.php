<?php

namespace App\Listeners;

use App\Events\DonationReceived;
use App\Jobs\SendDonorCertificateJob;

class SendCertificateNotification
{
    /**
     * Handle the event.
     */
    public function handle(DonationReceived $event): void
    {
        SendDonorCertificateJob::dispatch($event->donation);
    }
}
