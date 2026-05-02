@extends('layouts.master')
@section('title', 'Create Volunteer Task')
@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card mt-4">
            <div class="card-header card-header-custom">
                Create New Volunteer Task
            </div>
            <div class="card-body">
                <form action="{{ route('volunteers_store') }}" method="post">
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
                        <label class="form-label">Task Title:</label>
                        <input type="text" class="form-control" name="title" value="{{ old('title') }}" required>
                    </div>

                    <div class="form-group mb-3">
                        <label class="form-label">Description:</label>
                        <textarea class="form-control" name="description" rows="4" required>{{ old('description') }}</textarea>
                    </div>

                    <div class="row">
                        <div class="col-md-6 form-group mb-3">
                            <label class="form-label">Task Date:</label>
                            <input type="date" class="form-control" name="task_date" value="{{ old('task_date') }}" required>
                        </div>
                        <div class="col-md-6 form-group mb-3">
                            <label class="form-label">Hours Required:</label>
                            <input type="number" class="form-control" name="hours_required" value="{{ old('hours_required') }}" required>
                        </div>
                    </div>
                    
                    <div class="form-group mb-3">
                        <button type="submit" class="btn btn-primary w-100">Save Task</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
