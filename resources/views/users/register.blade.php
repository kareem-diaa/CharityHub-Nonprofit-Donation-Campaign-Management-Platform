@extends('layouts.master')
@section('title', 'Register')
@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card mt-4">
            <div class="card-header card-header-custom">
                Register for CharityHub
            </div>
            <div class="card-body">
                <form action="{{ route('do_register') }}" method="post">
                    {{ csrf_field() }}
                    
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
                        <label for="name" class="form-label">Name:</label>
                        <input type="text" class="form-control" placeholder="Enter your full name" name="name" value="{{ old('name') }}" required>
                    </div>

                    <div class="form-group mb-3">
                        <label for="email" class="form-label">Email:</label>
                        <input type="email" class="form-control" placeholder="Enter your email" name="email" value="{{ old('email') }}" required>
                    </div>
                    
                    <div class="form-group mb-3">
                        <label for="password" class="form-label">Password:</label>
                        <input type="password" class="form-control" placeholder="Enter your password" name="password" required>
                        <small class="text-muted">Minimum 8 characters.</small>
                    </div>

                    <div class="form-group mb-3">
                        <label for="password_confirmation" class="form-label">Confirm Password:</label>
                        <input type="password" class="form-control" placeholder="Confirm your password" name="password_confirmation" required>
                    </div>
                    
                    <div class="form-group mb-3">
                        <button type="submit" class="btn btn-primary w-100">Register</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
