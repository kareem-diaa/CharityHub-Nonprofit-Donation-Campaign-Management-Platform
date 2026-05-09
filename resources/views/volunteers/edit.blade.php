@extends('layouts.master')
@section('title', 'Edit Volunteer Task')
@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card mt-4">
            <div class="card-header card-header-custom" style="background-color: var(--purple-main);">Edit Volunteer Task: {{ $task->title }}</div>
            <div class="card-body">
                <form action="{{ route('volunteers_update', $task->id) }}" method="POST">
                    {{ csrf_field() }}
                    <div class="form-group mb-3">
                        <label class="form-label">Task Title:</label>
                        <input type="text" class="form-control" name="title" value="{{ $task->title }}" required>
                    </div>
                    <div class="form-group mb-3">
                        <label class="form-label">Description:</label>
                        <textarea class="form-control" name="description" rows="3" required>{{ $task->description }}</textarea>
                    </div>
                    <div class="row">
                        <div class="col-md-6 form-group mb-3">
                            <label class="form-label">Start Date:</label>
                            <input type="date" class="form-control" name="task_date" value="{{ $task->task_date }}" required>
                        </div>
                        <div class="col-md-6 form-group mb-3">
                            <label class="form-label">End Date (Optional):</label>
                            <input type="date" class="form-control" name="end_date" value="{{ $task->end_date }}">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 form-group mb-3">
                            <label class="form-label">Hours Required:</label>
                            <input type="number" class="form-control" name="hours_required" value="{{ $task->hours_required }}" required>
                        </div>
                        <div class="col-md-6 form-group mb-3">
                            <label class="form-label">Capacity (Max Volunteers):</label>
                            <input type="number" class="form-control" name="capacity" value="{{ $task->capacity }}" required>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-warning w-100 fw-bold">Update Task</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
