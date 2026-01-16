@extends('layouts.app')
@section('title', 'Performance Report')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2>Employee Performance Report</h2>
        <p class="text-muted mb-0">
            <span class="date-display">
                <i class="fas fa-calendar"></i>
                {{ date('F Y', mktime(0, 0, 0, $month, 1, $year)) }}
            </span>
        </p>
    </div>
    <div class="d-flex gap-2">
        <form method="GET" class="d-flex gap-2">
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

<div class="table-card">
    <h5 class="mb-3"><i class="fas fa-chart-line me-2 text-primary"></i>Performance Metrics</h5>
    <table class="table datatable">
        <thead>
            <tr>
                <th>Rank</th>
                <th>Employee</th>
                <th>Department</th>
                <th class="text-center">Attendance Rate</th>
                <th class="text-center">Punctuality Rate</th>
                <th class="text-center">Days Present</th>
                <th class="text-center">Late</th>
                <th class="text-center">Overtime</th>
            </tr>
        </thead>
        <tbody>
            @php $rank = 1; @endphp
            @foreach($employees as $data)
            <tr>
                <td>
                    @if($rank <= 3)
                    <span class="badge bg-{{ $rank == 1 ? 'warning' : ($rank == 2 ? 'secondary' : 'info') }}">
                        <i class="fas fa-trophy me-1"></i>#{{ $rank }}
                    </span>
                    @else
                    {{ $rank }}
                    @endif
                </td>
                <td>
                    <div class="d-flex align-items-center gap-2">
                        <div style="width: 36px; height: 36px; background: var(--primary); border-radius: 8px; display: flex; align-items: center; justify-content: center; color: white; font-weight: 600; font-size: 11px;">
                            {{ strtoupper(substr($data['employee']->first_name, 0, 1) . substr($data['employee']->last_name, 0, 1)) }}
                        </div>
                        <div>
                            <div style="font-weight: 600;">{{ $data['employee']->full_name }}</div>
                            <small class="text-muted">{{ $data['employee']->position->title ?? '-' }}</small>
                        </div>
                    </div>
                </td>
                <td>{{ $data['employee']->department->name ?? '-' }}</td>
                <td class="text-center">
                    <div style="width: 60px; height: 8px; background: #e2e8f0; border-radius: 4px; display: inline-block; overflow: hidden;">
                        <div style="width: {{ $data['attendance_rate'] }}%; height: 100%; background: {{ $data['attendance_rate'] >= 90 ? 'var(--success)' : ($data['attendance_rate'] >= 70 ? 'var(--warning)' : 'var(--danger)') }};"></div>
                    </div>
                    <span class="ms-2" style="font-weight: 600;">{{ $data['attendance_rate'] }}%</span>
                </td>
                <td class="text-center">
                    <div style="width: 60px; height: 8px; background: #e2e8f0; border-radius: 4px; display: inline-block; overflow: hidden;">
                        <div style="width: {{ $data['punctuality_rate'] }}%; height: 100%; background: var(--primary);"></div>
                    </div>
                    <span class="ms-2" style="font-weight: 600;">{{ $data['punctuality_rate'] }}%</span>
                </td>
                <td class="text-center"><span class="badge bg-success">{{ $data['present'] }}</span></td>
                <td class="text-center"><span class="badge bg-warning">{{ $data['late'] }}</span></td>
                <td class="text-center">{{ $data['overtime_hours'] }} hrs</td>
            </tr>
            @php $rank++; @endphp
            @endforeach
        </tbody>
    </table>
</div>

<div class="mt-3">
    <small class="text-muted">
        <i class="fas fa-info-circle me-1"></i>
        <strong>Legend:</strong> 
        Attendance Rate = (Present Days / Total Working Days) × 100 | 
        Punctuality Rate = (On-Time Days / Present Days) × 100
    </small>
</div>
@endsection
