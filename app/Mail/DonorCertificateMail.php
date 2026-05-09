<?php

namespace App\Mail;

use App\Models\Donation;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class DonorCertificateMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(public Donation $donation)
    {
        $this->donation->loadMissing(['user', 'campaign']);
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "Your CharityHub Donation Certificate - {$this->donation->campaign->title}",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.donor_certificate',
            with: [
                'donation' => $this->donation,
            ]
        );
    }

    public function attachments(): array
    {
        $pdf = Pdf::loadView('donations.certificate_pdf', [
            'donor_name'     => $this->donation->user->name ?? 'Generous Donor',
            'amount'         => $this->donation->amount,
            'campaign'       => $this->donation->campaign->title,
            'date'           => $this->donation->created_at->format('F j, Y'),
            'transaction_id' => $this->donation->transaction_id,
            'donation_id'    => $this->donation->id,
        ]);

        return [
            Attachment::fromData(fn () => $pdf->output(), "CharityHub-Certificate-{$this->donation->id}.pdf")
                    ->withMime('application/pdf'),
        ];
    }
}
