@extends('layouts.app')
@section('title', 'Record Attendance')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Record Attendance</h2>
    <a href="{{ route('attendance.index') }}" class="btn btn-secondary">Back to List</a>
</div>

<div class="table-card">
    <form action="{{ route('attendance.store') }}" method="POST">
        @csrf
        
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Employee <span class="text-danger">*</span></label>
                <select name="employee_id" class="form-select @error('employee_id') is-invalid @enderror" required>
                    <option value="">Select Employee</option>
                    @foreach($employees as $employee)
                        <option value="{{ $employee->id }}" {{ old('employee_id') == $employee->id ? 'selected' : '' }}>
                            {{ $employee->employee_number }} - {{ $employee->full_name }} ({{ $employee->department->name ?? 'N/A' }})
                        </option>
                    @endforeach
                </select>
                @error('employee_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-3 mb-3">
                <label class="form-label">Date <span class="text-danger">*</span></label>
                <input type="date" name="date" class="form-control @error('date') is-invalid @enderror" value="{{ old('date', date('Y-m-d')) }}" required>
                @error('date')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-3 mb-3">
                <label class="form-label">Shift</label>
                <select name="shift_id" class="form-select">
                    <option value="">Select Shift</option>
                    @foreach($shifts as $shift)
                        <option value="{{ $shift->id }}" {{ old('shift_id') == $shift->id ? 'selected' : '' }}>
                            {{ $shift->name }} ({{ \Carbon\Carbon::parse($shift->start_time)->format('h:i A') }} - {{ \Carbon\Carbon::parse($shift->end_time)->format('h:i A') }})
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3 mb-3">
                <label class="form-label">Clock In <span class="text-danger">*</span></label>
                <input type="time" name="clock_in" class="form-control @error('clock_in') is-invalid @enderror" value="{{ old('clock_in', '08:00') }}" required>
                @error('clock_in')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-3 mb-3">
                <label class="form-label">Clock Out</label>
                <input type="time" name="clock_out" class="form-control @error('clock_out') is-invalid @enderror" value="{{ old('clock_out') }}">
                @error('clock_out')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-3 mb-3">
                <label class="form-label">Status <span class="text-danger">*</span></label>
                <select name="status" class="form-select @error('status') is-invalid @enderror" required>
                    <option value="Present" {{ old('status') == 'Present' ? 'selected' : '' }}>Present</option>
                    <option value="Late" {{ old('status') == 'Late' ? 'selected' : '' }}>Late</option>
                    <option value="Half-day" {{ old('status') == 'Half-day' ? 'selected' : '' }}>Half-day</option>
                    <option value="Absent" {{ old('status') == 'Absent' ? 'selected' : '' }}>Absent</option>
                    <option value="Leave" {{ old('status') == 'Leave' ? 'selected' : '' }}>Leave</option>
                </select>
                @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-3 mb-3">
                <label class="form-label">Remarks</label>
                <input type="text" name="remarks" class="form-control" value="{{ old('remarks') }}" placeholder="Optional notes">
            </div>
        </div>

        <div class="d-flex justify-content-end gap-2">
            <a href="{{ route('attendance.index') }}" class="btn btn-secondary">Cancel</a>
            <button type="submit" class="btn btn-primary"><i class="fas fa-save me-2"></i>Record Attendance</button>
        </div>
    </form>
</div>
@endsection
