@extends('layouts.master')
@section('title', 'Manage Users')
@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="fw-bold" style="color: var(--purple-main);">User Management</h2>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <table class="table table-hover mb-0 align-middle">
            <thead class="table-light">
                <tr>
                    <th class="ps-4">Name</th>
                    <th>Email</th>
                    <th>Roles / Membership</th>
                    <th>Joined Date</th>
                    <th class="text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                <tr>
                    <td class="ps-4 fw-bold">{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>
                        @forelse($user->getRoleNames() as $role)
                            <span class="badge bg-info text-dark">{{ $role }}</span>
                        @empty
                            <span class="badge bg-secondary">Member</span>
                        @endforelse
                    </td>
                    <td>{{ $user->created_at->format('M d, Y') }}</td>
                    <td class="text-center">
                        <button class="btn btn-sm btn-outline-primary" disabled><i class="fas fa-edit"></i></button>
                        @if($user->id !== auth()->id())
                            <button class="btn btn-sm btn-outline-danger" disabled><i class="fas fa-trash"></i></button>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<div class="mt-4 alert alert-info small">
    <i class="fas fa-info-circle"></i> Roles are managed via the Spatie Permissions system. To change a user's role, please use the Database Seeder or a custom admin command.
</div>

@endsection
