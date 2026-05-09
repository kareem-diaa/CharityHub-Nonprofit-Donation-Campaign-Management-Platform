@extends('layouts.master')
@section('title', 'Create Volunteer Task')
@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card mt-4">
            <div class="card-header card-header-custom">Create New Volunteer Task</div>
            <div class="card-body">
                <form action="{{ route('volunteers_store') }}" method="POST">
                    {{ csrf_field() }}
                    <div class="form-group mb-3">
                        <label class="form-label">Task Title:</label>
                        <input type="text" class="form-control" name="title" required>
                    </div>
                    <div class="form-group mb-3">
                        <label class="form-label">Description:</label>
                        <textarea class="form-control" name="description" rows="3" required></textarea>
                    </div>
                    <div class="row">
                        <div class="col-md-6 form-group mb-3">
                            <label class="form-label">Start Date:</label>
                            <input type="date" class="form-control" name="task_date" required>
                        </div>
                        <div class="col-md-6 form-group mb-3">
                            <label class="form-label">End Date (Optional):</label>
                            <input type="date" class="form-control" name="end_date">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 form-group mb-3">
                            <label class="form-label">Hours Required:</label>
                            <input type="number" class="form-control" name="hours_required" required>
                        </div>
                        <div class="col-md-6 form-group mb-3">
                            <label class="form-label">Capacity (Max Volunteers):</label>
                            <input type="number" class="form-control" name="capacity" value="10" required>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Create Task</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
