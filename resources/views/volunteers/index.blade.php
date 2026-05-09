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
                        <li><strong>Start Date:</strong> {{ \Carbon\Carbon::parse($task->task_date)->format('M d, Y') }}</li>
                        @if($task->end_date)
                            <li><strong>End Date:</strong> {{ \Carbon\Carbon::parse($task->end_date)->format('M d, Y') }}</li>
                        @endif
                        <li><strong>Capacity:</strong> {{ $task->registrations->count() }} / {{ $task->capacity }} Volunteers</li>
                    </ul>

                    @php
                        $isFinished = $task->isFinished();
                        $isFull = $task->isFull();
                    @endphp

                    <div class="mb-3">
                        @if($isFinished)
                            <span class="badge bg-danger">Task Finished</span>
                        @elseif($isFull)
                            <span class="badge bg-warning text-dark">Full Capacity</span>
                        @else
                            <span class="badge bg-success">Open for Registration</span>
                        @endif
                    </div>
                    
                    @auth
                        @if(!$isFinished && !$isFull)
                            <form action="{{ route('volunteers_register', $task->id) }}" method="POST" class="mt-3">
                                {{ csrf_field() }}
                                <button type="submit" class="btn btn-outline-primary w-100">Register Now</button>
                            </form>
                        @endif

                        @can('manage_volunteers')
                            <div class="mt-2 d-flex justify-content-end gap-2">
                                <a href="{{ route('volunteers_edit', $task->id) }}" class="btn btn-sm btn-warning">Edit</a>
                                <form action="{{ route('volunteers_delete', $task->id) }}" method="POST" class="d-inline">
                                    {{ csrf_field() }}
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this task?')">Delete</button>
                                </form>
                            </div>
                        @endcan
                    @else
                        <div class="alert alert-warning mt-3 mb-0">
                            Please <a href="{{ route('login') }}">login</a> to register.
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
