@extends('layouts.master')
@section('title', 'Edit Campaign')
@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card mt-4">
            <div class="card-header card-header-custom">
                Edit Campaign
            </div>
            <div class="card-body">
                <form action="{{ route('campaigns_update', $campaign) }}" method="post">
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
                        <label class="form-label">Title:</label>
                        <input type="text" class="form-control" name="title" value="{{ $campaign->title }}" required>
                    </div>

                    <div class="form-group mb-3">
                        <label class="form-label">Description:</label>
                        <!-- Protection from Stored XSS: always escape input -->
                        <textarea class="form-control" name="description" rows="4" required>{{ $campaign->description }}</textarea>
                    </div>

                    <div class="row">
                        <div class="col-md-4 form-group mb-3">
                            <label class="form-label">Goal Amount ($):</label>
                            <input type="number" step="0.01" class="form-control" name="goal_amount" value="{{ $campaign->goal_amount }}" required>
                        </div>
                        <div class="col-md-4 form-group mb-3">
                            <label class="form-label">Deadline:</label>
                            <input type="date" class="form-control" name="deadline" value="{{ $campaign->deadline }}" required>
                        </div>
                        <div class="col-md-4 form-group mb-3">
                            <label class="form-label">Status:</label>
                            <select name="status" class="form-control">
                                <option value="active" {{ $campaign->status == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="completed" {{ $campaign->status == 'completed' ? 'selected' : '' }}>Completed</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="form-group mb-3">
                        <button type="submit" class="btn btn-warning w-100">Update Campaign</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
