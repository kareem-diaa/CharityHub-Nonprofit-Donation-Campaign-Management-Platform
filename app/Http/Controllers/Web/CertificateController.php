<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Donation;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class CertificateController extends Controller
{
    public function download(Donation $donation)
    {
        // Security Check: Only the donor or an admin can download this certificate
        if (auth()->user()->hasRole('Admin')) {
            // Admin can download anything
        } elseif (auth()->id() !== $donation->user_id) {
            abort(403, 'Unauthorized action.');
        }
        
        $data = [
            'donor_name'     => $donation->user->name ?? 'Generous Donor',
            'amount'         => $donation->amount,
            'campaign'       => $donation->campaign->title,
            'date'           => $donation->created_at->format('M d, Y'),
            'transaction_id' => $donation->transaction_id,
            'donation_id'    => $donation->id,
        ];

        $pdf = Pdf::loadView('donations.certificate_pdf', $data);

        return $pdf->download('CharityHub-Certificate-' . $donation->id . '.pdf');
    }

    public function verify(Donation $donation)
    {
        $donation->load(['user', 'campaign']);
        return view('donations.certificate_verify', compact('donation'));
    }
}
