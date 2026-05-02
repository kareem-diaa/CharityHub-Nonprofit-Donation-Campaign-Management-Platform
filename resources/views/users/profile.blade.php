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
                
                <div class="mt-3">
                    <!-- <a href="{{ url('/profile/edit') }}" class="btn btn-warning">Edit Profile</a> -->
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
