@extends('layouts.app')
@section('title','Edit User')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Edit User</h2>
    <a href="{{ route('users.index') }}" class="btn btn-secondary">Back</a>
</div>

<div class="table-card">
    <form action="{{ route('users.update', $user->id) }}" method="POST">
        @csrf @method('PUT')
        <div class="mb-3">
            <label class="form-label">Name</label>
            <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Password (leave blank to keep current)</label>
            <input type="password" name="password" class="form-control">
        </div>
        <div class="mb-3">
            <label class="form-label">Role</label>
            <select name="role" class="form-select" required>
                <option value="admin" {{ $user->role=='admin' ? 'selected' : '' }}>Admin</option>
                <option value="hr" {{ $user->role=='hr' ? 'selected' : '' }}>HR</option>
                <option value="supervisor" {{ $user->role=='supervisor' ? 'selected' : '' }}>Supervisor</option>
                <option value="employee" {{ $user->role=='employee' ? 'selected' : '' }}>Employee</option>
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label">Link Employee (optional)</label>
            <select name="employee_id" class="form-select">
                <option value="">None</option>
                @foreach($employees as $e)
                    <option value="{{ $e->id }}" {{ $user->employee_id == $e->id ? 'selected' : '' }}>{{ $e->full_name }} ({{ $e->email }})</option>
                @endforeach
            </select>
        </div>
        <div class="form-check mb-3">
            <input type="checkbox" name="is_active" class="form-check-input" id="is_active" {{ $user->is_active ? 'checked' : '' }}>
            <label class="form-check-label" for="is_active">Active</label>
        </div>
        <div class="d-flex justify-content-end gap-2">
            <a href="{{ route('users.index') }}" class="btn btn-secondary">Cancel</a>
            <button class="btn btn-primary">Save</button>
        </div>
    </form>
</div>
@endsection
