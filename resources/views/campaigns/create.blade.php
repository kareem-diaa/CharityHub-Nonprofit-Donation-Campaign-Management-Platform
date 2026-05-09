@extends('layouts.master')
@section('title', 'Create Campaign')
@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card mt-4">
            <div class="card-header card-header-custom">
                Create New Campaign
            </div>
            <div class="card-body">
                <form action="{{ route('campaigns_store') }}" method="post" enctype="multipart/form-data">
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
                        <label class="form-label">Title:</label>
                        <input type="text" class="form-control" name="title" value="{{ old('title') }}" required>
                    </div>

                    <div class="form-group mb-3">
                        <label class="form-label">Slug (URL friendly name):</label>
                        <input type="text" class="form-control" name="slug" value="{{ old('slug') }}" required>
                    </div>

                    <div class="form-group mb-3">
                        <label class="form-label">Description:</label>
                        <textarea class="form-control" name="description" rows="4" required>{{ old('description') }}</textarea>
                    </div>

                    <div class="form-group mb-3">
                        <label class="form-label">Campaign Image:</label>
                        <input type="file" class="form-control" name="image" accept="image/*">
                    </div>

                    <div class="row">
                        <div class="col-md-6 form-group mb-3">
                            <label class="form-label">Goal Amount ($):</label>
                            <input type="number" step="0.01" class="form-control" name="goal_amount" value="{{ old('goal_amount') }}" required>
                        </div>
                        <div class="col-md-6 form-group mb-3">
                            <label class="form-label">Deadline:</label>
                            <input type="date" class="form-control" name="deadline" value="{{ old('deadline') }}" required>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 form-group mb-3">
                            <label class="form-label">Latitude (Optional):</label>
                            <input type="number" step="any" class="form-control" name="latitude" value="{{ old('latitude') }}">
                        </div>
                        <div class="col-md-6 form-group mb-3">
                            <label class="form-label">Longitude (Optional):</label>
                            <input type="number" step="any" class="form-control" name="longitude" value="{{ old('longitude') }}">
                        </div>
                    </div>
                    
                    <div class="form-group mb-3">
                        <button type="submit" class="btn btn-primary w-100">Save Campaign</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
