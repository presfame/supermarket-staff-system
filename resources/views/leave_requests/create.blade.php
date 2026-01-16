@extends('layouts.app')
@section('title', 'Request Leave')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Request Leave</h2>
    <a href="{{ route('leave-requests.index') }}" class="btn btn-secondary">Back</a>
</div>

<div class="table-card">
    <form action="{{ route('leave-requests.store') }}" method="POST">
        @csrf

        @if(isset($employees) && $employees->count())
        <div class="mb-3">
            <label class="form-label">Employee</label>
            <select name="employee_id" class="form-select">
                <option value="">Select Employee</option>
                @foreach($employees as $emp)
                    <option value="{{ $emp->id }}" {{ request()->query('employee_id') == $emp->id ? 'selected' : '' }}>{{ $emp->full_name }} ({{ $emp->email }})</option>
                @endforeach
            </select>
        </div>
        @endif

        <div class="mb-3">
            <label class="form-label">Start Date</label>
            <input type="date" name="start_date" class="form-control" value="{{ old('start_date') }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label">End Date</label>
            <input type="date" name="end_date" class="form-control" value="{{ old('end_date') }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Reason</label>
            <textarea name="reason" class="form-control" rows="4" required>{{ old('reason') }}</textarea>
        </div>

        <div class="d-flex justify-content-end gap-2">
            <a href="{{ route('leave-requests.index') }}" class="btn btn-secondary">Cancel</a>
            <button class="btn btn-primary" type="submit">Submit Request</button>
        </div>
    </form>
</div>
@endsection
