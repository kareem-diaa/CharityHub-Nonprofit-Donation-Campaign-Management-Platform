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
                    
                    <!-- Livewire Progress Bar -->
                    <livewire:campaign-progress-bar :campaign="$campaign" />

                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <a href="{{ route('campaigns_show', $campaign) }}" class="btn btn-outline-primary">View</a>
                            <a href="{{ route('donations_create', $campaign) }}" class="btn btn-outline-success">Donate</a>
                        </div>
                        
                        @auth
                            @can('manage_campaigns')
                            <div>
                                <a href="{{ route('campaigns_edit', $campaign) }}" class="btn btn-sm btn-warning">Edit</a>
                                <form action="{{ route('campaigns_delete', $campaign) }}" method="POST" class="d-inline">
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
