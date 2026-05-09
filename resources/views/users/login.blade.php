@extends('layouts.master')
@section('title', 'Login')
@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card mt-4">
            <div class="card-header card-header-custom">
                Login to CharityHub
            </div>
            <div class="card-body">
                <form action="{{ route('do_login') }}" method="post">
                    {{ csrf_field() }}
                    
                    <div class="form-group mb-3">
                        <label for="email" class="form-label">Email:</label>
                        <input type="email" class="form-control" placeholder="Enter your email" name="email" value="{{ old('email') }}" required>
                    </div>
                    
                    <div class="form-group mb-3">
                        <label for="password" class="form-label">Password:</label>
                        <input type="password" class="form-control" placeholder="Enter your password" name="password" required>
                        <div class="text-end mt-1">
                            <a href="{{ route('forgot_password') }}" class="text-muted small">Forgot Password?</a>
                        </div>
                    </div>
                    
                    <div class="form-group mb-3">
                        <button type="submit" class="btn btn-primary w-100">Login</button>
                    </div>
                    
                    <div class="form-group mb-3 text-center">
                        <a href="{{ route('login_with_google') }}" class="btn btn-outline-dark w-100">
                            <img src="https://upload.wikimedia.org/wikipedia/commons/5/53/Google_%22G%22_Logo.svg" width="20" class="me-2"> Login with Google
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
