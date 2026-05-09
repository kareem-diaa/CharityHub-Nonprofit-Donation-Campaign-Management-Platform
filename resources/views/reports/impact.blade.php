@extends('layouts.master')
@section('title', 'System Impact Report')
@section('content')

<div class="mb-4">
    <h2 class="fw-bold" style="color: var(--purple-main);">System Impact & Statistics</h2>
    <p class="text-muted">Real-time overview of CharityHub's global impact.</p>
</div>

<!-- Key Metrics -->
<div class="row mb-5">
    <div class="col-md-3">
        <a href="{{ route('reports_donations') }}" class="text-decoration-none">
            <div class="card text-white shadow border-0 h-100" style="background: #be2edd; border-radius: 15px;">
                <div class="card-body text-center p-4">
                    <div class="bg-white bg-opacity-25 rounded-circle d-inline-block p-3 mb-3">
                        <i class="fas fa-hand-holding-usd fa-2x"></i>
                    </div>
                    <h6 class="text-white-50 text-uppercase small fw-bold">Total Funds</h6>
                    <h2 class="fw-bold mb-1">${{ number_format($totalDonations, 2) }}</h2>
                    <p class="small text-white-50 mb-0">Every dollar changes a life.</p>
                </div>
            </div>
        </a>
    </div>
    <div class="col-md-3">
        <a href="{{ route('campaigns_list') }}" class="text-decoration-none">
            <div class="card text-white shadow border-0 h-100" style="background: #a29bfe; border-radius: 15px;">
                <div class="card-body text-center p-4">
                    <div class="bg-white bg-opacity-25 rounded-circle d-inline-block p-3 mb-3">
                        <i class="fas fa-bullhorn fa-2x"></i>
                    </div>
                    <h6 class="text-white-50 text-uppercase small fw-bold">Active Campaigns</h6>
                    <h2 class="fw-bold mb-1">{{ $activeCampaigns }} Active</h2>
                    <p class="small text-white-50 mb-0">Driving change together.</p>
                </div>
            </div>
        </a>
    </div>
    <div class="col-md-3">
        <a href="{{ route('reports_volunteers') }}" class="text-decoration-none">
            <div class="card text-white shadow border-0 h-100" style="background: #d1d8ff; border-radius: 15px;">
                <div class="card-body text-center p-4">
                    <div class="bg-white bg-opacity-25 rounded-circle d-inline-block p-3 mb-3">
                        <i class="fas fa-users fa-2x text-primary"></i>
                    </div>
                    <h6 class="text-primary text-uppercase small fw-bold opacity-75">Volunteers</h6>
                    <h2 class="fw-bold mb-1 text-primary">{{ $totalVolunteers }} People</h2>
                    <p class="small text-primary opacity-75 mb-0">The heartbeat of our mission.</p>
                </div>
            </div>
        </a>
    </div>
    <div class="col-md-3">
        <div class="card text-white shadow border-0 h-100" style="background: #9b59b6; border-radius: 15px;">
            <div class="card-body text-center p-4">
                <div class="bg-white bg-opacity-25 rounded-circle d-inline-block p-3 mb-3">
                    <i class="fas fa-clock fa-2x"></i>
                </div>
                <h6 class="text-white-50 text-uppercase small fw-bold">Total Hours</h6>
                <h2 class="fw-bold mb-1">{{ number_format($totalVolunteerHours) }}h</h2>
                <p class="small text-white-50 mb-0">Building the future today.</p>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Campaign Breakdown Table -->
    <div class="col-md-8">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white fw-bold">Top Performing Campaigns</div>
            <div class="card-body">
                <table class="table align-middle">
                    <thead>
                        <tr>
                            <th>Campaign Name</th>
                            <th>Progress</th>
                            <th>Raised</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($campaignBreakdown as $campaign)
                        <tr>
                            <td>{{ $campaign->title }}</td>
                            <td width="40%">
                                @php
                                    $percent = $campaign->goal_amount > 0 ? ($campaign->raised_amount / $campaign->goal_amount) * 100 : 0;
                                    $percent = $percent > 100 ? 100 : $percent;
                                @endphp
                                <div class="progress" style="height: 8px;">
                                    <div class="progress-bar bg-success" style="width: {{ $percent }}%"></div>
                                </div>
                            </td>
                            <td class="fw-bold text-success">${{ number_format($campaign->raised_amount, 2) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Impact Quote/Info -->
    <div class="col-md-4">
        <div class="card border-0 bg-light shadow-sm h-100">
            <div class="card-body d-flex flex-column justify-content-center text-center">
                <i class="fas fa-quote-left fa-3x text-muted mb-3 opacity-25"></i>
                <h4 class="fst-italic text-muted">"Giving is not just about making a donation. It is about making a difference."</h4>
                <p class="mt-4 text-secondary small">This data is generated automatically from verified Stripe transactions and volunteer logs.</p>
            </div>
        </div>
    </div>
</div>

@endsection
