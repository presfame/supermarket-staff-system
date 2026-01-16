@extends('layouts.app')
@section('title', 'Edit Attendance')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2>Edit Attendance Record</h2>
        <p class="text-muted mb-0">{{ $attendance->employee->full_name }} - {{ \Carbon\Carbon::parse($attendance->date)->format('d M Y') }}</p>
    </div>
    <a href="{{ route('attendance.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left me-2"></i>Back
    </a>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="table-card">
            <form action="{{ route('attendance.update', $attendance->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Employee</label>
                        <input type="text" class="form-control" value="{{ $attendance->employee->full_name }}" disabled>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Date</label>
                        <input type="text" class="form-control" value="{{ \Carbon\Carbon::parse($attendance->date)->format('d M Y') }}" disabled>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Clock In</label>
                        <input type="time" name="clock_in" class="form-control" 
                               value="{{ $attendance->clock_in ? \Carbon\Carbon::parse($attendance->clock_in)->format('H:i') : '' }}">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Clock Out</label>
                        <input type="time" name="clock_out" class="form-control" 
                               value="{{ $attendance->clock_out ? \Carbon\Carbon::parse($attendance->clock_out)->format('H:i') : '' }}">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Status *</label>
                        <select name="status" class="form-select" required>
                            @foreach(['Present', 'Late', 'Absent', 'Half-day', 'Leave'] as $status)
                            <option value="{{ $status }}" {{ $attendance->status == $status ? 'selected' : '' }}>{{ $status }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Remarks</label>
                        <input type="text" name="remarks" class="form-control" value="{{ $attendance->remarks }}" placeholder="Optional">
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2 mt-3">
                    <a href="{{ route('attendance.index') }}" class="btn btn-secondary">Cancel</a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>Update Attendance
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="col-md-4">
        <div class="table-card">
            <h6 class="mb-3"><i class="fas fa-info-circle me-2 text-primary"></i>Current Record</h6>
            <table class="table table-sm">
                <tr>
                    <td class="text-muted">Hours Worked</td>
                    <td class="fw-bold">{{ $attendance->hours_worked ?? 0 }} hrs</td>
                </tr>
                <tr>
                    <td class="text-muted">Overtime</td>
                    <td class="fw-bold">{{ $attendance->overtime_hours ?? 0 }} hrs</td>
                </tr>
                <tr>
                    <td class="text-muted">Recorded By</td>
                    <td class="fw-bold">{{ $attendance->recorder->name ?? 'System' }}</td>
                </tr>
                <tr>
                    <td class="text-muted">Last Updated</td>
                    <td class="fw-bold">{{ $attendance->updated_at->format('d M Y, h:i A') }}</td>
                </tr>
            </table>
        </div>
    </div>
</div>
@endsection
