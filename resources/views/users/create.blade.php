@extends('layouts.app')
@section('title','Create User')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Create User</h2>
    <a href="{{ route('users.index') }}" class="btn btn-secondary">Back</a>
</div>

<div class="table-card">
    <form action="{{ route('users.store') }}" method="POST">
        @csrf
        @if($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <div class="mb-3">
            <label class="form-label">Name</label>
            <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Password</label>
            <input type="password" name="password" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Role</label>
            <select name="role" class="form-select" required>
                <option value="admin" {{ old('role')=='admin' ? 'selected' : '' }}>Admin</option>
                <option value="hr" {{ old('role')=='hr' ? 'selected' : '' }}>HR</option>
                <option value="supervisor" {{ old('role')=='supervisor' ? 'selected' : '' }}>Supervisor</option>
                <option value="employee" {{ old('role')=='employee' ? 'selected' : '' }}>Employee</option>
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label">Link Employee (optional)</label>
            <select name="employee_id" class="form-select">
                <option value="">None</option>
                @foreach($employees as $e)
                    <option value="{{ $e->id }}" {{ old('employee_id') == $e->id ? 'selected' : '' }}>{{ $e->full_name }} ({{ $e->email }})</option>
                @endforeach
            </select>
        </div>
        <div class="form-check mb-3">
            <input type="checkbox" name="is_active" class="form-check-input" id="is_active" @if(old('is_active', true)) checked @endif>
            <label class="form-check-label" for="is_active">Active</label>
        </div>
        <div class="d-flex justify-content-end gap-2">
            <a href="{{ route('users.index') }}" class="btn btn-secondary">Cancel</a>
            <button class="btn btn-primary">Create</button>
        </div>
    </form>
</div>
@endsection
