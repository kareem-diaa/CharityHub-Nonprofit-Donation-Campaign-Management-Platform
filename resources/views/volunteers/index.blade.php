@extends('layouts.master')
@section('title', 'Volunteer Opportunities')
@section('content')

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var calendarEl = document.getElementById('calendar');
        var calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,listWeek'
            },
            events: [
                @foreach($tasks as $task)
                {
                    title: '{{ addslashes($task->title) }}',
                    start: '{{ $task->task_date }}',
                    end: '{{ $task->end_date ? \Carbon\Carbon::parse($task->end_date)->addDay()->format("Y-m-d") : "" }}',
                    url: '#task-{{ $task->id }}'
                },
                @endforeach
            ]
        });
        calendar.render();
    });
</script>
@endpush

<div class="mb-5">
    <div id="calendar" class="card shadow-sm p-3"></div>
</div>

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
        <div class="col-md-6 mb-4" id="task-{{ $task->id }}">
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
                        @php
                            $userRegistration = auth()->user()->volunteerRegistrations->where('volunteer_task_id', $task->id)->first();
                        @endphp
                        
                        @if($userRegistration)
                            <div class="card mt-3 border-success">
                                <div class="card-body p-3">
                                    <h6 class="text-success mb-2"><i class="fas fa-clock"></i> Log Your Hours</h6>
                                    <form action="{{ route('volunteers_log_hours', $userRegistration->id) }}" method="POST">
                                        {{ csrf_field() }}
                                        <div class="input-group">
                                            <input type="number" step="0.5" min="0" max="{{ $task->hours_required }}" class="form-control" name="hours_logged" value="{{ $userRegistration->hours_logged }}" required>
                                            <span class="input-group-text">/ {{ $task->hours_required }} hrs</span>
                                            <button type="submit" class="btn btn-success">Save</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        @elseif(!$isFinished && !$isFull)
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
