@extends('layouts.app')
@section('title', 'Attendance')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2>Attendance Records</h2>
        <p class="text-muted mb-0">Track employee attendance, leaves, and work hours</p>
    </div>
    <div class="d-flex gap-2 align-items-center">
        <form method="GET" class="d-flex gap-2">
            <select name="filter_type" class="form-select" style="width: auto;" onchange="toggleDateInputs(this.value)">
                <option value="date" {{ $filterType == 'date' ? 'selected' : '' }}>By Date</option>
                <option value="month" {{ $filterType == 'month' ? 'selected' : '' }}>By Month</option>
            </select>
            <div id="dateInput" style="{{ $filterType == 'month' ? 'display:none' : '' }}">
                <input type="date" name="date" value="{{ $date ?? now()->format('Y-m-d') }}" class="form-control">
            </div>
            <div id="monthInput" style="{{ $filterType == 'date' ? 'display:none' : '' }}" class="d-flex gap-2">
                <select name="month" class="form-select">
                    @for($m = 1; $m <= 12; $m++)
                    <option value="{{ $m }}" {{ ($month ?? now()->month) == $m ? 'selected' : '' }}>
                        {{ date('F', mktime(0, 0, 0, $m, 1)) }}
                    </option>
                    @endfor
                </select>
                <select name="year" class="form-select">
                    @for($y = 2024; $y <= 2026; $y++)
                    <option value="{{ $y }}" {{ ($year ?? now()->year) == $y ? 'selected' : '' }}>{{ $y }}</option>
                    @endfor
                </select>
            </div>
            <button type="submit" class="btn btn-secondary">
                <i class="fas fa-filter"></i>
            </button>
        </form>
        <a href="{{ route('attendance.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-1"></i> Record
        </a>
    </div>
</div>

<!-- Summary Cards -->
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="stat-card green">
            <div class="icon"><i class="fas fa-check"></i></div>
            <div class="value">{{ $summary['present'] ?? 0 }}</div>
            <div class="label">Present</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card orange">
            <div class="icon"><i class="fas fa-clock"></i></div>
            <div class="value">{{ $summary['late'] ?? 0 }}</div>
            <div class="label">Late</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card" style="border-left: 4px solid var(--danger);">
            <div class="icon" style="background: #fee2e2; color: var(--danger);"><i class="fas fa-times"></i></div>
            <div class="value">{{ $summary['absent'] ?? 0 }}</div>
            <div class="label">Absent</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card blue">
            <div class="icon"><i class="fas fa-plane"></i></div>
            <div class="value">{{ $summary['leave'] ?? 0 }}</div>
            <div class="label">On Leave</div>
        </div>
    </div>
</div>

<div class="table-card">
    <table class="table datatable">
        <thead>
            <tr>
                <th>Employee</th>
                <th>Department</th>
                <th>Shift</th>
                <th>Date</th>
                <th>Clock In</th>
                <th>Clock Out</th>
                <th>Hours</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($attendances as $record)
            <tr>
                <td>
                    <div class="d-flex align-items-center gap-2">
                        <div style="width: 36px; height: 36px; background: var(--primary); border-radius: 8px; display: flex; align-items: center; justify-content: center; color: white; font-weight: 600; font-size: 12px;">
                            {{ strtoupper(substr($record->employee->first_name, 0, 1) . substr($record->employee->last_name, 0, 1)) }}
                        </div>
                        <div>
                            <div style="font-weight: 600;">{{ $record->employee->full_name }}</div>
                            <small class="text-muted">{{ $record->employee->employee_number }}</small>
                        </div>
                    </div>
                </td>
                <td>{{ $record->employee->department->name ?? '-' }}</td>
                <td>
                    @if($record->shift)
                    <span class="badge bg-secondary">{{ $record->shift->name }}</span>
                    @else
                    -
                    @endif
                </td>
                <td>
                    <span class="date-display">
                        <i class="fas fa-calendar"></i>
                        {{ \Carbon\Carbon::parse($record->date)->format('d M Y') }}
                    </span>
                </td>
                <td>
                    @if($record->clock_in)
                    <i class="fas fa-sign-in-alt text-success me-1"></i>{{ $record->clock_in }}
                    @else - @endif
                </td>
                <td>
                    @if($record->clock_out)
                    <i class="fas fa-sign-out-alt text-danger me-1"></i>{{ $record->clock_out }}
                    @else - @endif
                </td>
                <td>
                    {{ $record->hours_worked ?? 0 }} hrs
                    @if($record->overtime_hours > 0)
                    <span class="badge bg-warning" style="font-size: 10px;">+{{ $record->overtime_hours }} OT</span>
                    @endif
                </td>
                <td>
                    @php
                        $statusColors = ['Present' => 'success', 'Late' => 'warning', 'Absent' => 'danger', 'Leave' => 'info', 'Half-day' => 'secondary'];
                    @endphp
                    <span class="badge bg-{{ $statusColors[$record->status] ?? 'secondary' }}">
                        {{ $record->status }}
                    </span>
                </td>
                <td>
                    <a href="{{ route('attendance.edit', $record->id) }}" class="btn btn-sm btn-info">
                        <i class="fas fa-edit"></i>
                    </a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<script>
function toggleDateInputs(value) {
    document.getElementById('dateInput').style.display = value == 'date' ? 'block' : 'none';
    document.getElementById('monthInput').style.display = value == 'month' ? 'flex' : 'none';
}
</script>
@endsection
