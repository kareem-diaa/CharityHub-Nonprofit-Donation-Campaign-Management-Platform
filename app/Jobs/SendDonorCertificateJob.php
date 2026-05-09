<?php

namespace App\Jobs;

use App\Mail\DonorCertificateMail;
use App\Models\Donation;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendDonorCertificateJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 3;
    public $backoff = 60;

    public function __construct(public Donation $donation)
    {}

    public function handle(): void
    {
        $this->donation->loadMissing(['user', 'campaign']);
        
        if ($this->donation->user && $this->donation->user->email) {
            Mail::to($this->donation->user->email)->send(new DonorCertificateMail($this->donation));
        }
    }
}
