@extends('layouts.master')
@section('title', 'Platform Impact Report')
@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 style="color: var(--neon-green);">Platform Impact Report</h2>
    <button onclick="window.print()" class="btn btn-outline-light">🖨️ Print Report</button>
</div>

<!-- Key Metrics -->
<div class="row mb-5">
    <div class="col-md-3">
        <div class="card text-center text-white shadow-sm h-100" style="background-color: #1a1a1a; border: 1px solid var(--neon-green);">
            <div class="card-body">
                <h5 class="card-title text-muted">Total Raised</h5>
                <h2 style="color: var(--neon-green);">${{ number_format($totalDonations, 2) }}</h2>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-center text-white shadow-sm h-100" style="background-color: #1a1a1a; border: 1px solid var(--neon-green);">
            <div class="card-body">
                <h5 class="card-title text-muted">Active Campaigns</h5>
                <h2 style="color: var(--neon-green);">{{ $activeCampaigns }} <small class="text-muted" style="font-size: 1rem;">/ {{ $totalCampaigns }} total</small></h2>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-center text-white shadow-sm h-100" style="background-color: #1a1a1a; border: 1px solid var(--neon-green);">
            <div class="card-body">
                <h5 class="card-title text-muted">Volunteers Engaged</h5>
                <h2 style="color: var(--neon-green);">{{ $totalVolunteers }}</h2>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-center text-white shadow-sm h-100" style="background-color: #1a1a1a; border: 1px solid var(--neon-green);">
            <div class="card-body">
                <h5 class="card-title text-muted">Volunteer Hours</h5>
                <h2 style="color: var(--neon-green);">{{ $totalVolunteerHours }} <small class="text-muted" style="font-size: 1rem;">hrs</small></h2>
            </div>
        </div>
    </div>
</div>

<!-- Detailed Campaign Breakdown -->
<div class="card shadow-sm" style="border-top: 4px solid var(--neon-green);">
    <div class="card-header bg-dark text-white">
        <h4 class="mb-0">Top Performing Campaigns</h4>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-dark table-hover mb-0">
                <thead>
                    <tr>
                        <th>Campaign Title</th>
                        <th>Goal Amount</th>
                        <th>Raised Amount</th>
                        <th>Completion</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($campaignBreakdown as $campaign)
                        <tr>
                            <td>{{ $campaign->title }}</td>
                            <td>${{ number_format($campaign->goal_amount, 2) }}</td>
                            <td style="color: var(--neon-green);">${{ number_format($campaign->raised_amount, 2) }}</td>
                            <td>
                                @php
                                    $percent = $campaign->goal_amount > 0 ? ($campaign->raised_amount / $campaign->goal_amount) * 100 : 0;
                                    $percent = $percent > 100 ? 100 : $percent;
                                @endphp
                                <div class="progress" style="height: 10px; background-color: #333;">
                                    <div class="progress-bar progress-bar-custom" role="progressbar" style="width: {{ $percent }}%;" aria-valuenow="{{ $percent }}" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                                <small class="text-muted">{{ number_format($percent, 1) }}%</small>
                            </td>
                        </tr>
                    @endforeach
                    @if($campaignBreakdown->isEmpty())
                        <tr>
                            <td colspan="4" class="text-center text-muted py-4">No data available yet.</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection
