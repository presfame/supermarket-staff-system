@extends('layouts.app')
@section('title', $shift->name)
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2>{{ $shift->name }}</h2>
        <p class="text-muted mb-0">
            <i class="fas fa-clock me-1"></i>
            {{ \Carbon\Carbon::parse($shift->start_time)->format('h:i A') }} - 
            {{ \Carbon\Carbon::parse($shift->end_time)->format('h:i A') }}
            <span class="badge bg-primary ms-2">{{ $shift->hours }} hours</span>
        </p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('shifts.edit', $shift->id) }}" class="btn btn-secondary">
            <i class="fas fa-edit me-2"></i>Edit
        </a>
        <a href="{{ route('shifts.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-2"></i>Back
        </a>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="table-card">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="mb-0"><i class="fas fa-users me-2 text-primary"></i>Assigned Employees ({{ $shift->currentEmployees->count() }})</h5>
            </div>
            
            <table class="table">
                <thead>
                    <tr>
                        <th>Employee</th>
                        <th>Department</th>
                        <th>Position</th>
                        <th>Assigned</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($shift->currentEmployees as $employee)
                    <tr>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <div style="width: 36px; height: 36px; background: var(--primary); border-radius: 8px; display: flex; align-items: center; justify-content: center; color: white; font-weight: 600; font-size: 12px;">
                                    {{ strtoupper(substr($employee->first_name, 0, 1) . substr($employee->last_name, 0, 1)) }}
                                </div>
                                <div>
                                    <div style="font-weight: 600;">{{ $employee->full_name }}</div>
                                    <small class="text-muted">{{ $employee->employee_number }}</small>
                                </div>
                            </div>
                        </td>
                        <td>{{ $employee->department->name ?? '-' }}</td>
                        <td>{{ $employee->position->title ?? '-' }}</td>
                        <td>{{ \Carbon\Carbon::parse($employee->pivot->effective_from)->format('d M Y') }}</td>
                        <td>
                            <form action="{{ route('shifts.remove-employee', $shift->id) }}" method="POST" class="d-inline">
                                @csrf
                                <input type="hidden" name="employee_id" value="{{ $employee->id }}">
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Remove from shift?')">
                                    <i class="fas fa-times"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-4 text-muted">No employees assigned to this shift</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="table-card">
            <h6 class="mb-3"><i class="fas fa-user-plus me-2 text-primary"></i>Assign Employee</h6>
            <form action="{{ route('shifts.assign-employee', $shift->id) }}" method="POST">
                @csrf
                <div class="mb-3">
                    <select name="employee_id" class="form-select" required>
                        <option value="">Select employee...</option>
                        @foreach($employees as $emp)
                        <option value="{{ $emp->id }}">{{ $emp->full_name }} ({{ $emp->department->name ?? 'N/A' }})</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="btn btn-primary w-100">
                    <i class="fas fa-plus me-2"></i>Assign to Shift
                </button>
            </form>
        </div>

        @if($shift->description)
        <div class="table-card mt-3">
            <h6 class="mb-2">Description</h6>
            <p class="text-muted mb-0">{{ $shift->description }}</p>
        </div>
        @endif
    </div>
</div>
@endsection
