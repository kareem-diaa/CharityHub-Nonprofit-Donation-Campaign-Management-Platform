<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>CharityHub - @yield('title')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* Fix for yellow autofill in browsers */
        input:-webkit-autofill,
        input:-webkit-autofill:hover,
        input:-webkit-autofill:focus,
        input:-webkit-autofill:active {
            -webkit-box-shadow: 0 0 0 30px #f8f1ff inset !important;
            -webkit-text-fill-color: #4834d4 !important;
        }
        .card:hover {
            transform: translateY(-5px);
            transition: all 0.3s ease;
        }
    </style>
</head>
<body>
    @include('layouts.menu')
    <div class="container mt-4">
        @if(session('success'))
            <div class="alert alert-success d-flex justify-content-between align-items-center">
                <div><strong>Success!</strong> {!! session('success') !!}</div>
                @if(session('donation_id'))
                    <a href="{{ route('donations_certificate', session('donation_id')) }}" class="btn btn-sm btn-light border">
                        <i class="fas fa-file-pdf text-danger"></i> Download Certificate
                    </a>
                @endif
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger">
                <strong>Error!</strong> {{ session('error') }}
            </div>
        @endif
        
        @yield('content')
    </div>
</body>
</html>
