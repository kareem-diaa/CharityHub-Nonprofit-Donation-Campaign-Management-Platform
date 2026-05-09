@extends('layouts.master')
@section('title', 'User Profile')
@section('content')
<div class="row justify-content-center">
    <div class="col-md-8 mt-4">
        <div class="card">
            <div class="card-header card-header-custom">
                User Profile
            </div>
            <div class="card-body">
                <table class="table table-striped">
                    <tr>
                        <th width="30%">Name</th>
                        <td>{{ $user->name }}</td>
                    </tr>
                    <tr>
                        <th>Email</th>
                        <td>{{ $user->email }}</td>
                    </tr>
                    <tr>
                        <th>Joined At</th>
                        <td>{{ $user->created_at->format('M d, Y') }}</td>
                    </tr>
                    <!-- 
                    <tr>
                        <th>Roles</th>
                        <td>
                            @foreach($user->roles as $role)
                                <span class="badge bg-primary">{{ $role->name }}</span>
                            @endforeach
                        </td>
                    </tr>
                    -->
                </table>

                <!-- Donation History -->
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-white fw-bold">My Donations</div>
                    <div class="card-body p-0">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>Campaign</th>
                                    <th>Amount</th>
                                    <th>Date</th>
                                    <th>Certificate</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($user->donations as $donation)
                                <tr>
                                    <td>{{ $donation->campaign->title }}</td>
                                    <td>${{ number_format($donation->amount, 2) }}</td>
                                    <td>{{ $donation->created_at->format('M d, Y') }}</td>
                                    <td>
                                        <a href="{{ route('donations_certificate', $donation->id) }}" class="btn btn-sm btn-outline-danger">
                                            <i class="fas fa-file-pdf"></i> Download
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Volunteering History -->
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white fw-bold">My Volunteering History</div>
                    <div class="card-body p-0">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>Task Title</th>
                                    <th>Date</th>
                                    <th>Hours</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($user->volunteerRegistrations as $reg)
                                <tr>
                                    <td>{{ $reg->task->title }}</td>
                                    <td>{{ \Carbon\Carbon::parse($reg->task->task_date)->format('M d, Y') }}</td>
                                    <td>{{ $reg->task->hours_required }}h</td>
                                    <td><span class="badge bg-info text-dark">{{ ucfirst($reg->status) }}</span></td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center py-3 text-muted">You haven't volunteered for any tasks yet.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
