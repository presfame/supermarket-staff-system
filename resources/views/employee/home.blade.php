@extends('layouts.app')
@section('title', 'My Home')
@section('content')
@php
    $user = auth()->user();
    $employee = $user->employee_id ? \App\Models\Employee::with(['department', 'position'])->find($user->employee_id) : null;
    $todayAttendance = $employee ? \App\Models\Attendance::where('employee_id', $employee->id)->whereDate('date', today())->first() : null;
    $todayShift = $employee ? \App\Models\ShiftSchedule::where('employee_id', $employee->id)->where('schedule_date', today())->with('shift')->first() : null;
@endphp

<style>
    .employee-welcome {
        background: var(--primary);
        border-radius: 16px;
        padding: 32px;
        color: white;
        position: relative;
        overflow: hidden;
    }
    .employee-welcome::before {
        content: '';
        position: absolute;
        right: -50px;
        top: -50px;
        width: 200px;
        height: 200px;
        background: rgba(255,255,255,0.1);
        border-radius: 50%;
    }
    .employee-welcome::after {
        content: '';
        position: absolute;
        right: 50px;
        bottom: -80px;
        width: 150px;
        height: 150px;
        background: rgba(255,255,255,0.05);
        border-radius: 50%;
    }
    .employee-avatar {
        width: 70px;
        height: 70px;
        background: rgba(255,255,255,0.2);
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        font-weight: 700;
        border: 2px solid rgba(255,255,255,0.3);
    }
    .attendance-card {
        background: white;
        border-radius: 16px;
        padding: 24px;
        box-shadow: 0 2px 12px rgba(0,0,0,0.06);
        height: 100%;
    }
    .attendance-card .card-title {
        font-size: 14px;
        font-weight: 600;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .attendance-card .card-title i {
        width: 32px;
        height: 32px;
        background: #f1f5f9;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--primary);
    }
    .time-display {
        text-align: center;
        padding: 16px;
        background: #f8fafc;
        border-radius: 12px;
    }
    .time-display .label {
        font-size: 12px;
        color: #94a3b8;
        margin-bottom: 4px;
    }
    .time-display .time {
        font-size: 20px;
        font-weight: 700;
        font-family: 'Courier New', monospace;
    }
    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 8px 20px;
        border-radius: 20px;
        font-weight: 600;
        font-size: 13px;
    }
    .status-badge.success { background: #dcfce7; color: #16a34a; }
    .status-badge.warning { background: #fef3c7; color: #d97706; }
    .status-badge.danger { background: #fee2e2; color: #dc2626; }
    .shift-info-box {
        background: #f8fafc;
        border-radius: 12px;
        padding: 20px;
        text-align: center;
    }
    .shift-badge {
        background: var(--primary);
        color: white;
        padding: 8px 20px;
        border-radius: 8px;
        font-weight: 600;
        display: inline-block;
        margin-bottom: 16px;
    }
    .shift-time-grid {
        display: grid;
        grid-template-columns: 1fr 1fr 1fr;
        gap: 12px;
        margin-top: 12px;
    }
    .shift-time-item .label { font-size: 11px; color: #94a3b8; }
    .shift-time-item .value { font-size: 16px; font-weight: 700; color: #1e293b; }
    .quick-link {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 14px 16px;
        background: #f8fafc;
        border-radius: 10px;
        color: #334155;
        text-decoration: none;
        transition: all 0.2s;
        border: 1px solid transparent;
    }
    .quick-link:hover {
        background: #f1f5f9;
        border-color: var(--primary);
        color: var(--primary);
    }
    .quick-link i {
        width: 36px;
        height: 36px;
        background: white;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--primary);
        box-shadow: 0 2px 4px rgba(0,0,0,0.06);
    }
    .info-row {
        display: flex;
        justify-content: space-between;
        padding: 12px 0;
        border-bottom: 1px solid #f1f5f9;
    }
    .info-row:last-child { border-bottom: none; }
    .info-row .label { color: #64748b; font-size: 13px; }
    .info-row .value { font-weight: 600; color: #1e293b; }
    .clock-btn {
        padding: 16px 48px;
        font-size: 16px;
        font-weight: 600;
        border-radius: 12px;
        border: none;
        cursor: pointer;
        transition: all 0.2s;
    }
    .clock-btn.clock-in { background: #16a34a; color: white; }
    .clock-btn.clock-in:hover { background: #15803d; }
    .clock-btn.clock-out { background: #dc2626; color: white; }
    .clock-btn.clock-out:hover { background: #b91c1c; }
    .empty-state {
        text-align: center;
        padding: 30px;
        color: #94a3b8;
    }
    .empty-state i { font-size: 40px; margin-bottom: 12px; opacity: 0.5; }
</style>

<div class="row g-4">
    <!-- Welcome Banner -->
    <div class="col-12">
        <div class="employee-welcome">
            <div class="d-flex justify-content-between align-items-center position-relative" style="z-index: 1;">
                <div>
                    <h2 class="mb-2" style="font-weight: 700;">Good {{ now()->format('H') < 12 ? 'Morning' : (now()->format('H') < 17 ? 'Afternoon' : 'Evening') }}, {{ $employee->first_name ?? $user->name }}!</h2>
                    <p class="mb-0 opacity-75">
                        <i class="fas fa-calendar-day me-2"></i>{{ now()->format('l, d F Y') }}
                    </p>
                </div>
                <div class="employee-avatar">
                    {{ strtoupper(substr($employee->first_name ?? 'U', 0, 1) . substr($employee->last_name ?? 'S', 0, 1)) }}
                </div>
            </div>
        </div>
    </div>

    <!-- Today's Attendance -->
    <div class="col-lg-6">
        <div class="attendance-card">
            <div class="card-title">
                <i class="fas fa-clock"></i>
                Today's Attendance
            </div>
            
            @if($todayAttendance)
                <div class="d-flex gap-3 mb-4">
                    <div class="time-display flex-fill">
                        <div class="label">Clock In</div>
                        <div class="time text-success">
                            {{ $todayAttendance->clock_in ? \Carbon\Carbon::parse($todayAttendance->clock_in)->format('h:i A') : '--:--' }}
                        </div>
                    </div>
                    <div class="time-display flex-fill">
                        <div class="label">Clock Out</div>
                        <div class="time {{ $todayAttendance->clock_out ? 'text-danger' : 'text-muted' }}">
                            {{ $todayAttendance->clock_out ? \Carbon\Carbon::parse($todayAttendance->clock_out)->format('h:i A') : '--:--' }}
                        </div>
                    </div>
                </div>
                
                <div class="text-center">
                    <span class="status-badge {{ $todayAttendance->status == 'Present' ? 'success' : ($todayAttendance->status == 'Late' ? 'warning' : 'danger') }}">
                        <i class="fas fa-check-circle"></i>
                        {{ $todayAttendance->status }}
                    </span>
                    
                    @if(!$todayAttendance->clock_out)
                    <div class="mt-4">
                        <form action="{{ route('attendance.clock-out') }}" method="POST">
                            @csrf
                            <button type="submit" class="clock-btn clock-out">
                                <i class="fas fa-sign-out-alt me-2"></i>Clock Out
                            </button>
                        </form>
                    </div>
                    @endif
                </div>
            @else
                <div class="text-center py-3">
                    <div class="empty-state mb-3">
                        <i class="fas fa-user-clock"></i>
                        <p class="mb-0">You haven't clocked in today</p>
                    </div>
                    <form action="{{ route('attendance.clock-in') }}" method="POST">
                        @csrf
                        <button type="submit" class="clock-btn clock-in">
                            <i class="fas fa-sign-in-alt me-2"></i>Clock In
                        </button>
                    </form>
                    <p class="text-muted small mt-3 mb-0">
                        Current time: {{ now()->format('h:i A') }}
                    </p>
                </div>
            @endif
        </div>
    </div>

    <!-- Today's Shift -->
    <div class="col-lg-6">
        <div class="attendance-card">
            <div class="card-title">
                <i class="fas fa-calendar-check"></i>
                Today's Shift
            </div>
            
            @if($todayShift && $todayShift->shift)
                <div class="shift-info-box">
                    <div class="shift-badge">{{ $todayShift->shift->name }}</div>
                    <div class="shift-time-grid">
                        <div class="shift-time-item">
                            <div class="label">START TIME</div>
                            <div class="value">{{ \Carbon\Carbon::parse($todayShift->shift->start_time)->format('h:i A') }}</div>
                        </div>
                        <div class="shift-time-item">
                            <div class="label">END TIME</div>
                            <div class="value">{{ \Carbon\Carbon::parse($todayShift->shift->end_time)->format('h:i A') }}</div>
                        </div>
                        <div class="shift-time-item">
                            <div class="label">DURATION</div>
                            <div class="value">{{ $todayShift->shift->hours }} hrs</div>
                        </div>
                    </div>
                </div>
            @else
                <div class="empty-state">
                    <i class="fas fa-calendar-times"></i>
                    <p class="mb-3">No shift scheduled for today</p>
                    <a href="{{ route('schedule.my') }}" class="btn btn-outline-primary btn-sm">
                        View Full Schedule
                    </a>
                </div>
            @endif
        </div>
    </div>

    <!-- Quick Links -->
    <div class="col-lg-6">
        <div class="attendance-card">
            <div class="card-title">
                <i class="fas fa-link"></i>
                Quick Links
            </div>
            <div class="d-flex flex-column gap-3">
                <a href="{{ route('schedule.my') }}" class="quick-link">
                    <i class="fas fa-calendar-alt"></i>
                    <span>My Schedule</span>
                </a>
                <a href="{{ route('payslips.my') }}" class="quick-link">
                    <i class="fas fa-file-invoice-dollar"></i>
                    <span>My Payslips</span>
                </a>
            </div>
        </div>
    </div>

    <!-- My Info -->
    @if($employee)
    <div class="col-lg-6">
        <div class="attendance-card">
            <div class="card-title">
                <i class="fas fa-id-card"></i>
                My Information
            </div>
            <div>
                <div class="info-row">
                    <span class="label">Employee #</span>
                    <span class="value">{{ $employee->employee_number }}</span>
                </div>
                <div class="info-row">
                    <span class="label">Department</span>
                    <span class="value">{{ $employee->department->name ?? 'N/A' }}</span>
                </div>
                <div class="info-row">
                    <span class="label">Position</span>
                    <span class="value">{{ $employee->position->title ?? 'N/A' }}</span>
                </div>
                <div class="info-row">
                    <span class="label">Email</span>
                    <span class="value">{{ $employee->email }}</span>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection
