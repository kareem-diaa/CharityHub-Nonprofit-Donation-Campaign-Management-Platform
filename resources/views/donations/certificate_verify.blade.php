@extends('layouts.master')
@section('title', 'Verify Certificate')
@section('content')

<div class="row justify-content-center mt-5">
    <div class="col-md-6 text-center">
        <div class="card shadow border-0" style="border-top: 5px solid var(--neon-green);">
            <div class="card-body p-5">
                <i class="fas fa-check-circle text-success fa-4x mb-3"></i>
                <h3 class="fw-bold">Certificate Verified</h3>
                <p class="text-muted">This is a genuine CharityHub donation certificate.</p>
                
                <hr>
                
                <ul class="list-unstyled text-start fs-5 mt-4">
                    <li class="mb-2"><strong>Donor:</strong> {{ $donation->user->name ?? 'Anonymous' }}</li>
                    <li class="mb-2"><strong>Campaign:</strong> {{ $donation->campaign->title }}</li>
                    <li class="mb-2"><strong>Amount:</strong> ${{ number_format($donation->amount, 2) }}</li>
                    <li class="mb-2"><strong>Date:</strong> {{ $donation->created_at->format('F j, Y') }}</li>
                    <li><strong>Transaction ID:</strong> {{ $donation->transaction_id }}</li>
                </ul>
            </div>
        </div>
    </div>
</div>

@endsection
