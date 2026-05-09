@extends('layouts.master')
@section('title', 'All Volunteers')
@section('content')

<div class="mb-4">
    <a href="{{ route('reports_impact') }}" class="btn btn-sm btn-outline-secondary mb-2"><i class="fas fa-arrow-left"></i> Back to Impact</a>
    <h2 class="fw-bold" style="color: var(--purple-main);">Detailed Volunteers List</h2>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th class="ps-4">Volunteer Name</th>
                    <th>Task Name</th>
                    <th>Task Date</th>
                    <th>Hours</th>
                    <th>Registration Date</th>
                    <th class="text-center">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($registrations as $reg)
                <tr>
                    <td class="ps-4">{{ $reg->user->name }}</td>
                    <td>{{ $reg->task->title }}</td>
                    <td>{{ \Carbon\Carbon::parse($reg->task->task_date)->format('M d, Y') }}</td>
                    <td>{{ $reg->hours_logged }}h / {{ $reg->task->hours_required }}h</td>
                    <td>{{ $reg->created_at->format('M d, Y') }}</td>
                    <td class="text-center">
                        <form action="{{ route('reports_volunteers_remove', $reg->id) }}" method="POST" class="d-inline">
                            {{ csrf_field() }}
                            <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Remove this volunteer from the task?')">
                                <i class="fas fa-user-minus"></i> Remove
                            </button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

@endsection
