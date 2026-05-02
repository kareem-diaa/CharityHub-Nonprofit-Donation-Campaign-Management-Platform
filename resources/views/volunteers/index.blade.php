@extends('layouts.master')
@section('title', 'Volunteer Opportunities')
@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 style="color: var(--neon-green);">Volunteer Opportunities</h2>
    @auth
        @can('manage_volunteers')
            <a href="{{ route('volunteers_create') }}" class="btn btn-primary">Create Task</a>
        @endcan
    @endauth
</div>

<div class="row">
    @foreach($tasks as $task)
        <div class="col-md-6 mb-4">
            <div class="card h-100 shadow-sm" style="border-left: 5px solid var(--neon-green);">
                <div class="card-body">
                    <h4 class="card-title">{{ $task->title }}</h4>
                    <p class="card-text">{{ $task->description }}</p>
                    <ul class="list-unstyled">
                        <li><strong>Date:</strong> {{ \Carbon\Carbon::parse($task->task_date)->format('M d, Y') }}</li>
                        <li><strong>Hours Required:</strong> {{ $task->hours_required }} hours</li>
                        <li><strong>Status:</strong> <span class="badge bg-secondary">{{ ucfirst($task->status) }}</span></li>
                    </ul>
                    
                    @auth
                        @can('register_volunteer')
                            <form action="{{ route('volunteers_register', $task->id) }}" method="POST" class="mt-3">
                                {{ csrf_field() }}
                                <button type="submit" class="btn btn-outline-primary w-100">Register for Task</button>
                            </form>
                        @endcan
                    @else
                        <div class="alert alert-warning mt-3 mb-0">
                            Please <a href="{{ route('login') }}">login</a> to register for this task.
                        </div>
                    @endauth
                </div>
            </div>
        </div>
    @endforeach

    @if($tasks->isEmpty())
        <div class="col-12 text-center mt-5">
            <p class="lead">No volunteer tasks available at the moment.</p>
        </div>
    @endif
</div>
@endsection
