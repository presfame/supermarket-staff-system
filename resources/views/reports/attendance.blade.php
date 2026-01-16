@extends('layouts.app')
@section('title', 'Attendance Report')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2>Attendance Report</h2>
        <p class="text-muted mb-0">
            <span class="date-display">
                <i class="fas fa-calendar"></i>
                {{ date('F Y', mktime(0, 0, 0, $month, 1, $year)) }}
            </span>
        </p>
    </div>
    <div class="d-flex gap-2">
        <form method="GET" class="d-flex gap-2">
            <select name="department" class="form-select" style="width: 150px;">
                <option value="">All Departments</option>
                @foreach($departments as $dept)
                <option value="{{ $dept->id }}" {{ $departmentId == $dept->id ? 'selected' : '' }}>{{ $dept->name }}</option>
                @endforeach
            </select>
            <select name="month" class="form-select" style="width: 130px;">
                @for($m = 1; $m <= 12; $m++)
                <option value="{{ $m }}" {{ $month == $m ? 'selected' : '' }}>{{ date('F', mktime(0, 0, 0, $m, 1)) }}</option>
                @endfor
            </select>
            <select name="year" class="form-select" style="width: 100px;">
                @for($y = 2024; $y <= 2026; $y++)
                <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                @endfor
            </select>
            <button type="submit" class="btn btn-primary"><i class="fas fa-filter"></i></button>
        </form>
        <a href="{{ route('reports.index') }}" class="btn btn-secondary">Back</a>
    </div>
</div>

<!-- Summary Cards -->
<div class="row g-3 mb-4">
    <div class="col-md-2">
        <div class="summary-box text-center">
            <div class="label">Present Days</div>
            <div class="value text-success">{{ $summary['total_present'] }}</div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="summary-box text-center">
            <div class="label">Late Days</div>
            <div class="value text-warning">{{ $summary['total_late'] }}</div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="summary-box text-center">
            <div class="label">Absent Days</div>
            <div class="value text-danger">{{ $summary['total_absent'] }}</div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="summary-box text-center">
            <div class="label">Leave Days</div>
            <div class="value text-info">{{ $summary['total_leave'] }}</div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="summary-box text-center">
            <div class="label">Overtime Hours</div>
            <div class="value" style="color: #8b5cf6;">{{ $summary['total_overtime'] }}</div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="summary-box text-center">
            <div class="label">Employees</div>
            <div class="value">{{ $employees->count() }}</div>
        </div>
    </div>
</div>

<div class="table-card">
    <table class="table datatable">
        <thead>
            <tr>
                <th>Employee</th>
                <th>Department</th>
                <th class="text-center">Present</th>
                <th class="text-center">Late</th>
                <th class="text-center">Absent</th>
                <th class="text-center">Leave</th>
                <th class="text-center">Overtime</th>
            </tr>
        </thead>
        <tbody>
            @foreach($employees as $data)
            <tr>
                <td>
                    <div class="d-flex align-items-center gap-2">
                        <div style="width: 36px; height: 36px; background: var(--primary); border-radius: 8px; display: flex; align-items: center; justify-content: center; color: white; font-weight: 600; font-size: 12px;">
                            {{ strtoupper(substr($data['employee']->first_name, 0, 1) . substr($data['employee']->last_name, 0, 1)) }}
                        </div>
                        <div>
                            <div style="font-weight: 600;">{{ $data['employee']->full_name }}</div>
                            <small class="text-muted">{{ $data['employee']->employee_number }}</small>
                        </div>
                    </div>
                </td>
                <td>{{ $data['employee']->department->name ?? '-' }}</td>
                <td class="text-center"><span class="badge bg-success">{{ $data['present'] }}</span></td>
                <td class="text-center"><span class="badge bg-warning">{{ $data['late'] }}</span></td>
                <td class="text-center"><span class="badge bg-danger">{{ $data['absent'] }}</span></td>
                <td class="text-center"><span class="badge bg-info">{{ $data['leave'] }}</span></td>
                <td class="text-center">{{ $data['overtime'] }} hrs</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
