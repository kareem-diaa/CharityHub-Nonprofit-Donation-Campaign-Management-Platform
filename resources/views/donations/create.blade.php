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
                <form action="{{ route('donations_process', $campaign) }}" method="post">
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

                    <div class="alert alert-info small mt-2">
                        <i class="fas fa-shield-alt"></i> You will be redirected to a <strong>Secure Stripe Payment</strong> page.
                    </div>
                    
                    <div class="form-group mb-3">
                        <button type="submit" class="btn btn-primary w-100 py-2 fw-bold">Proceed to Payment</button>
                    </div>

                    <div class="text-center mt-3">
                        <p class="small text-muted mb-1">Testing Mode Active</p>
                        <code class="bg-light p-1 border rounded">Test Card: 4242 4242 4242 4242</code>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
