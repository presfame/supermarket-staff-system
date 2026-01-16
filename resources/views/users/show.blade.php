@extends('layouts.app')
@section('title', 'User')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>User Details</h2>
    <a href="{{ route('users.index') }}" class="btn btn-secondary">Back</a>
</div>

<div class="table-card">
    <dl class="row">
        <dt class="col-sm-3">Name</dt><dd class="col-sm-9">{{ $user->name }}</dd>
        <dt class="col-sm-3">Email</dt><dd class="col-sm-9">{{ $user->email }}</dd>
        <dt class="col-sm-3">Role</dt><dd class="col-sm-9">{{ $user->role }}</dd>
        <dt class="col-sm-3">Employee</dt><dd class="col-sm-9">{{ $user->employee->full_name ?? '—' }}</dd>
        <dt class="col-sm-3">Active</dt><dd class="col-sm-9">{{ $user->is_active ? 'Yes' : 'No' }}</dd>
        <dt class="col-sm-3">Created</dt><dd class="col-sm-9">{{ $user->created_at }}</dd>
    </dl>
</div>
@endsection
