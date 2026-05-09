@extends('layouts.master')
@section('title', 'All Donations')
@section('content')

<div class="mb-4">
    <a href="{{ route('reports_impact') }}" class="btn btn-sm btn-outline-secondary mb-2"><i class="fas fa-arrow-left"></i> Back to Impact</a>
    <h2 class="fw-bold" style="color: var(--purple-main);">Detailed Donations List</h2>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th class="ps-4">Donor</th>
                    <th>Campaign</th>
                    <th>Amount</th>
                    <th>Date</th>
                    <th>Transaction ID</th>
                    <th class="text-center">Certificate</th>
                </tr>
            </thead>
            <tbody>
                @foreach($donations as $donation)
                <tr>
                    <td class="ps-4">{{ $donation->user->name ?? 'Anonymous' }}</td>
                    <td>{{ $donation->campaign->title }}</td>
                    <td class="fw-bold text-success">${{ number_format($donation->amount, 2) }}</td>
                    <td>{{ $donation->created_at->format('M d, Y H:i') }}</td>
                    <td><small class="text-muted">{{ $donation->transaction_id }}</small></td>
                    <td class="text-center">
                        <a href="{{ route('donations_certificate', $donation->id) }}" class="btn btn-sm btn-outline-danger">
                            <i class="fas fa-file-pdf"></i> View
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

@endsection
