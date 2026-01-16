@extends('layouts.app')
@section('title', 'My Schedule')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2>My Shift Schedule</h2>
        <p class="text-muted mb-0">View your assigned work shifts</p>
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
    </div>
</div>

@if($todayShift)
<div class="alert alert-info">
    <i class="fas fa-calendar-check me-2"></i>
    <strong>Today's Shift:</strong> {{ $todayShift->shift->name }} 
    ({{ \Carbon\Carbon::parse($todayShift->shift->start_time)->format('h:i A') }} - {{ \Carbon\Carbon::parse($todayShift->shift->end_time)->format('h:i A') }})
</div>
@endif

<div class="row">
    <div class="col-md-8">
        <div class="table-card">
            <h5 class="mb-3"><i class="fas fa-calendar me-2 text-primary"></i>{{ date('F Y', mktime(0, 0, 0, $month, 1, $year)) }} Schedule</h5>
            
            @if($monthSchedules->count() > 0)
            <table class="table">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Day</th>
                        <th>Shift</th>
                        <th>Time</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($monthSchedules as $schedule)
                    <tr class="{{ $schedule->schedule_date->isToday() ? 'table-primary' : '' }}">
                        <td>
                            <strong>{{ $schedule->schedule_date->format('d M Y') }}</strong>
                            @if($schedule->schedule_date->isToday())
                            <span class="badge bg-success ms-1">Today</span>
                            @endif
                        </td>
                        <td>{{ $schedule->schedule_date->format('l') }}</td>
                        <td><strong>{{ $schedule->shift->name }}</strong></td>
                        <td>
                            {{ \Carbon\Carbon::parse($schedule->shift->start_time)->format('h:i A') }} - 
                            {{ \Carbon\Carbon::parse($schedule->shift->end_time)->format('h:i A') }}
                        </td>
                        <td>
                            @php
                                $statusColors = [
                                    'Scheduled' => 'info',
                                    'Confirmed' => 'primary',
                                    'Completed' => 'success',
                                    'Absent' => 'danger'
                                ];
                            @endphp
                            <span class="badge bg-{{ $statusColors[$schedule->status] ?? 'secondary' }}">
                                {{ $schedule->status }}
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @else
            <div class="text-center py-4 text-muted">
                <i class="fas fa-calendar-times fa-2x mb-2"></i><br>
                No shifts scheduled for this month
            </div>
            @endif
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="table-card mb-3">
            <h6 class="mb-3"><i class="fas fa-clock me-2 text-primary"></i>Upcoming Shifts</h6>
            @if($upcomingShifts->count() > 0)
            @foreach($upcomingShifts->take(5) as $schedule)
            <div class="d-flex justify-content-between align-items-center mb-2 pb-2 border-bottom">
                <div>
                    <div style="font-weight: 600;">{{ $schedule->schedule_date->format('D, d M') }}</div>
                    <small class="text-muted">{{ $schedule->shift->name }}</small>
                </div>
                <small class="text-muted">{{ \Carbon\Carbon::parse($schedule->shift->start_time)->format('h:i A') }}</small>
            </div>
            @endforeach
            @else
            <p class="text-muted mb-0">No upcoming shifts</p>
            @endif
        </div>

        <div class="table-card">
            <h6 class="mb-3"><i class="fas fa-chart-bar me-2 text-primary"></i>This Month Summary</h6>
            <div class="row g-2 text-center">
                <div class="col-6">
                    <div class="summary-box">
                        <div class="label">Total Shifts</div>
                        <div class="value">{{ $monthSchedules->count() }}</div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="summary-box">
                        <div class="label">Total Hours</div>
                        <div class="value">{{ $monthSchedules->sum(fn($s) => $s->shift->hours) }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
