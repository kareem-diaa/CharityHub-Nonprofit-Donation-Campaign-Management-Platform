@extends('layouts.master')
@section('title', 'Home')
@section('content')
<div class="p-5 text-center bg-dark text-white rounded-3 shadow-sm border" style="border-color: var(--neon-green) !important;">
    <h1 class="display-4 fw-bold" style="color: var(--neon-green);">Welcome to CharityHub</h1>
    <p class="lead mt-3">Empowering nonprofits to raise funds and manage campaigns efficiently.</p>
    <hr class="my-4" style="background-color: var(--neon-green); height: 2px;">
    <p>Join us to make an impact. Donate to campaigns, volunteer your time, and see real-time updates.</p>
    <a class="btn btn-primary btn-lg mt-3" href="{{ url('/campaigns') }}" role="button">Explore Campaigns</a>
    @guest
        <a class="btn btn-outline-light btn-lg mt-3 ms-2" href="{{ url('/register') }}" role="button" style="border-color: var(--neon-green); color: var(--neon-green);">Become a Member</a>
    @endguest
</div>

<div class="row mt-5">
    <div class="col-md-4">
        <div class="card h-100 shadow-sm" style="border-top: 4px solid var(--neon-green);">
            <div class="card-body text-center">
                <h3 class="card-title">Donate</h3>
                <p class="card-text">Support verified charity campaigns with secure one-time or recurring donations.</p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card h-100 shadow-sm" style="border-top: 4px solid var(--neon-green);">
            <div class="card-body text-center">
                <h3 class="card-title">Volunteer</h3>
                <p class="card-text">Find local and remote volunteer tasks. Track your hours and get certified.</p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card h-100 shadow-sm" style="border-top: 4px solid var(--neon-green);">
            <div class="card-body text-center">
                <h3 class="card-title">Track Impact</h3>
                <p class="card-text">View impact reports, photo galleries, and map integrations to see where your money goes.</p>
            </div>
        </div>
    </div>
</div>
@endsection
