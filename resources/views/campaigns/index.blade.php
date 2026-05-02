@extends('layouts.master')
@section('title', 'Campaigns')
@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 style="color: var(--neon-green);">Charity Campaigns</h2>
    @auth
        @can('manage_campaigns')
            <a href="{{ route('campaigns_create') }}" class="btn btn-primary">Create Campaign</a>
        @endcan
    @endauth
</div>

<div class="row">
    @foreach($campaigns as $campaign)
        <div class="col-md-4 mb-4">
            <div class="card h-100 shadow-sm" style="border-top: 4px solid var(--neon-green);">
                <div class="card-body">
                    <h4 class="card-title">{{ $campaign->title }}</h4>
                    <span class="badge bg-secondary mb-2">{{ ucfirst($campaign->status) }}</span>
                    <p class="card-text text-muted">{!! $campaign->description !!}</p>
                    
                    <div class="mb-3">
                        <small class="text-muted">Raised: ${{ number_format($campaign->raised_amount, 2) }} / ${{ number_format($campaign->goal_amount, 2) }}</small>
                        <div class="progress mt-1" style="height: 10px;">
                            @php
                                $percent = $campaign->goal_amount > 0 ? ($campaign->raised_amount / $campaign->goal_amount) * 100 : 0;
                                $percent = $percent > 100 ? 100 : $percent;
                            @endphp
                            <div class="progress-bar progress-bar-custom" role="progressbar" style="width: {{ $percent }}%;" aria-valuenow="{{ $percent }}" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between align-items-center">
                        <a href="{{ route('donations_create', $campaign->id) }}" class="btn btn-outline-success">Donate</a>
                        
                        @auth
                            @can('manage_campaigns')
                            <div>
                                <a href="{{ route('campaigns_edit', $campaign->id) }}" class="btn btn-sm btn-warning">Edit</a>
                                <form action="{{ route('campaigns_delete', $campaign->id) }}" method="POST" class="d-inline">
                                    {{ csrf_field() }}
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                                </form>
                            </div>
                            @endcan
                        @endauth
                    </div>
                </div>
                <div class="card-footer text-muted text-center" style="background-color: transparent;">
                    Deadline: {{ \Carbon\Carbon::parse($campaign->deadline)->format('M d, Y') }}
                </div>
            </div>
        </div>
    @endforeach
    
    @if($campaigns->isEmpty())
        <div class="col-12 text-center mt-5">
            <p class="lead">No active campaigns found.</p>
        </div>
    @endif
</div>
@endsection
