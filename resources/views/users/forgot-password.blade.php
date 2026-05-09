@extends('layouts.master')

@section('title', 'Forgot Password - CharityHub')

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card shadow-sm p-4">
                <div class="text-center mb-4">
                    <h3 class="fw-bold" style="color: var(--purple-main);">Forgot Password?</h3>
                    <p class="text-muted">Enter your email to reset your password.</p>
                </div>

                @if(session('success'))
                    <div class="alert alert-success">
                        <strong>Test Mode:</strong><br>
                        {!! session('success') !!}
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif

                <form action="{{ route('forgot_password_process') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Email Address</label>
                        <input type="email" name="email" class="form-control" required placeholder="Enter your registered email">
                        @error('email')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-primary w-100">Send Reset Link</button>
                    
                    <div class="text-center mt-3">
                        <a href="{{ route('login') }}" class="text-decoration-none" style="color: var(--purple-main); font-weight: 500;">Back to Login</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
