@extends('layouts.master')
@section('title', 'Make a Donation')
@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card mt-4">
            <div class="card-header card-header-custom">
                Donate to: {{ $campaign->title }}
            </div>
            <div class="card-body">
                <form action="{{ route('donations_process', $campaign->id) }}" method="post">
                    {{ csrf_field() }}
                    <input type="hidden" name="idempotency_key" value="{{ Str::uuid() }}">
                    
                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                            </ul>
                        </div>
                    @endif
                    
                    <div class="form-group mb-3">
                        <label class="form-label">Donation Amount ($):</label>
                        <input type="number" step="0.01" class="form-control" name="amount" value="50.00" required>
                    </div>

                    <div class="form-group mb-3">
                        <label class="form-label">Donation Type:</label>
                        <select name="type" class="form-select">
                            <option value="one-time">One-time</option>
                            <option value="recurring">Recurring (Monthly)</option>
                        </select>
                    </div>

                    <hr>
                    <h5 style="color: var(--neon-green);">Secure Payment (Simulated)</h5>
                    <div class="form-group mb-3">
                        <label class="form-label">Card Number:</label>
                        <input type="text" class="form-control" placeholder="**** **** **** ****" required>
                    </div>
                    <div class="row">
                        <div class="col-6 form-group mb-3">
                            <label class="form-label">Expiry Date:</label>
                            <input type="text" class="form-control" placeholder="MM/YY" required>
                        </div>
                        <div class="col-6 form-group mb-3">
                            <label class="form-label">CVC:</label>
                            <input type="text" class="form-control" placeholder="***" required>
                        </div>
                    </div>
                    
                    <div class="form-group mb-3">
                        <button type="submit" class="btn btn-primary w-100">Donate Securely</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
