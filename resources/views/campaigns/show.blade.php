@extends('layouts.master')
@section('title', $campaign->title)

@push('meta')
    <meta property="og:title" content="{{ $campaign->title }} - CharityHub">
    <meta property="og:description" content="{{ Str::limit(strip_tags($campaign->description), 150) }}">
    <meta property="og:url" content="{{ route('campaigns_show', $campaign) }}">
    <meta property="og:type" content="website">
    <meta property="og:image" content="{{ asset('img/default-campaign.jpg') }}"> <!-- Placeholder if no image column -->
@endpush

@section('content')
<div class="row">
    <div class="col-md-8 offset-md-2">
        <div class="card shadow-sm mb-4" style="border-top: 4px solid var(--neon-green);">
            <div class="card-body p-5">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h2 class="card-title mb-0" style="color: var(--neon-green);">{{ $campaign->title }}</h2>
                    <span class="badge bg-secondary fs-6">{{ ucfirst($campaign->status) }}</span>
                </div>
                
                <p class="text-muted mb-4"><i class="fas fa-calendar-alt"></i> Deadline: {{ \Carbon\Carbon::parse($campaign->deadline)->format('F j, Y') }}</p>

                <div class="campaign-description fs-5 lh-base mb-5">
                    {!! $campaign->description !!}
                </div>

                <!-- Livewire Progress Bar -->
                <livewire:campaign-progress-bar :campaign="$campaign" />

                <div class="d-grid gap-2 d-md-flex justify-content-md-start mt-4">
                    <a href="{{ route('donations_create', $campaign) }}" class="btn btn-success btn-lg px-5 me-md-2">Donate Now</a>
                </div>

                <!-- Social Share -->
                <hr class="my-4">
                <h5 class="mb-3">Share this campaign</h5>
                <div class="d-flex gap-2">
                    <a href="https://twitter.com/intent/tweet?text={{ urlencode('Support this campaign: ' . $campaign->title) }}&url={{ urlencode(route('campaigns_show', $campaign)) }}" target="_blank" class="btn btn-outline-info">
                        <i class="fab fa-twitter"></i> Twitter
                    </a>
                    <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(route('campaigns_show', $campaign)) }}" target="_blank" class="btn btn-outline-primary">
                        <i class="fab fa-facebook-f"></i> Facebook
                    </a>
                    <a href="https://api.whatsapp.com/send?text={{ urlencode('Check out this campaign on CharityHub: ' . route('campaigns_show', $campaign)) }}" target="_blank" class="btn btn-outline-success">
                        <i class="fab fa-whatsapp"></i> WhatsApp
                    </a>
                    <button type="button" class="btn btn-outline-secondary" onclick="navigator.clipboard.writeText('{{ route('campaigns_show', $campaign) }}'); alert('Link copied to clipboard!');">
                        <i class="fas fa-link"></i> Copy Link
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
