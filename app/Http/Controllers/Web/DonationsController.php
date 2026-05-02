<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Campaign;
use App\Models\Donation;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class DonationsController extends Controller
{
    public function create(Campaign $campaign)
    {
        return view('donations.create', compact('campaign'));
    }

    public function process(Request $request, Campaign $campaign)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1',
            'type' => 'required|in:one-time,recurring',
            'idempotency_key' => 'required|string',
            // Normally we would validate card details here
        ]);

        // Idempotency Check to prevent duplicate charges
        $existingDonation = Donation::where('idempotency_key', $request->idempotency_key)->first();
        if ($existingDonation) {
            return redirect()->route('campaigns_list')->with('success', 'Your donation was already processed. Thank you!');
        }

        DB::beginTransaction();
        try {
            // 1. Simulate Stripe Charge
            $transactionId = 'txn_' . Str::random(16); // Simulated Transaction ID

            // 2. Save Donation Record
            $donation = new Donation();
            $donation->campaign_id = $campaign->id;
            $donation->user_id = auth()->check() ? auth()->id() : null;
            $donation->amount = $request->amount;
            $donation->type = $request->type;
            $donation->payment_method = 'Credit Card (Simulated)';
            $donation->transaction_id = $transactionId;
            $donation->idempotency_key = $request->idempotency_key;
            $donation->status = 'completed';
            $donation->save();

            // 3. Update Campaign Ledger/Progress
            $campaign->raised_amount += $request->amount;
            $campaign->save();

            DB::commit();

            return redirect()->route('campaigns_list')->with('success', 'Thank you for your generous donation!');

        } catch (\Exception $e) {
            DB::rollBack();
            // Secure Error Handling: Do not expose raw SQL errors to users
            return redirect()->back()->with('error', 'Payment processing failed. Please try again later.');
        }
    }
}
