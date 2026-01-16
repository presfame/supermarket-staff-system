@extends('layouts.app')
@section('title', 'Dashboard')
@section('content')

<style>
    .stat-card-modern {
        background: white;
        border-radius: 14px;
        padding: 20px;
        box-shadow: 0 2px 12px rgba(0,0,0,0.04);
        position: relative;
        overflow: hidden;
    }
    .stat-card-modern .stat-icon {
        width: 50px;
        height: 50px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        color: white;
        margin-bottom: 16px;
    }
    .stat-card-modern .stat-icon.blue { background: linear-gradient(135deg, #3b82f6, #1d4ed8); }
    .stat-card-modern .stat-icon.green { background: linear-gradient(135deg, #10b981, #059669); }
    .stat-card-modern .stat-icon.orange { background: linear-gradient(135deg, #f59e0b, #d97706); }
    .stat-card-modern .stat-icon.purple { background: linear-gradient(135deg, #8b5cf6, #7c3aed); }
    .stat-card-modern .stat-icon.pink { background: linear-gradient(135deg, #ec4899, #db2777); }
    .stat-card-modern .stat-value {
        font-size: 28px;
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 4px;
    }
    .stat-card-modern .stat-label {
        font-size: 13px;
        color: #64748b;
    }
    .stat-card-modern .stat-change {
        position: absolute;
        top: 20px;
        right: 20px;
        font-size: 12px;
        font-weight: 600;
        padding: 4px 8px;
        border-radius: 6px;
    }
    .stat-card-modern .stat-change.up { background: #dcfce7; color: #16a34a; }
    .stat-card-modern .stat-change.down { background: #fee2e2; color: #dc2626; }
    
    .dashboard-card {
        background: white;
        border-radius: 14px;
        padding: 24px;
        box-shadow: 0 2px 12px rgba(0,0,0,0.04);
        height: 100%;
    }
    .dashboard-card .card-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
    }
    .dashboard-card .card-title {
        font-size: 16px;
        font-weight: 600;
        color: #1e293b;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .dashboard-card .card-title i {
        color: var(--primary);
    }
    
    .employee-row {
        display: flex;
        align-items: center;
        padding: 12px 0;
        border-bottom: 1px solid #f1f5f9;
    }
    .employee-row:last-child { border-bottom: none; }
    .employee-avatar {
        width: 42px;
        height: 42px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: 600;
        font-size: 14px;
        margin-right: 12px;
    }
    .employee-info .name { font-weight: 600; color: #1e293b; font-size: 14px; }
    .employee-info .role { font-size: 12px; color: #64748b; }
    .employee-status {
        margin-left: auto;
        font-size: 11px;
        padding: 4px 10px;
        border-radius: 6px;
        font-weight: 600;
    }
    .employee-status.active { background: #dcfce7; color: #16a34a; }
    
    .activity-item {
        display: flex;
        gap: 14px;
        padding: 14px 0;
        border-bottom: 1px solid #f1f5f9;
    }
    .activity-item:last-child { border-bottom: none; }
    .activity-date {
        text-align: center;
        min-width: 50px;
    }
    .activity-date .day { font-size: 20px; font-weight: 700; color: var(--primary); }
    .activity-date .month { font-size: 11px; color: #64748b; text-transform: uppercase; }
    .activity-content .title { font-weight: 600; color: #1e293b; font-size: 14px; margin-bottom: 4px; }
    .activity-content .meta { font-size: 12px; color: #64748b; }
    .activity-badge {
        font-size: 10px;
        padding: 3px 8px;
        border-radius: 4px;
        font-weight: 600;
        margin-left: 8px;
    }
    .activity-badge.payroll { background: #dbeafe; color: #2563eb; }
    .activity-badge.attendance { background: #dcfce7; color: #16a34a; }
    .activity-badge.new { background: #fef3c7; color: #d97706; }
    
    .payroll-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 14px 0;
        border-bottom: 1px solid #f1f5f9;
    }
    .payroll-row:last-child { border-bottom: none; }
    .payroll-row .employee { display: flex; align-items: center; gap: 12px; }
    .payroll-row .amount { font-weight: 700; color: #16a34a; }
    .payroll-row .period { font-size: 12px; color: #64748b; }
</style>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 style="font-weight: 700; color: #1e293b;">Dashboard</h2>
        <p class="text-muted mb-0">Welcome back! Here's your overview for {{ now()->format('F Y') }}</p>
    </div>
    <div class="badge bg-primary px-3 py-2">
        <i class="fas fa-calendar-day me-2"></i>{{ now()->format('l, d M Y') }}
    </div>
</div>

<!-- Stats Row -->
<div class="row g-4 mb-4">
    <div class="col-xl-3 col-md-6">
        <div class="stat-card-modern">
            <div class="stat-icon blue"><i class="fas fa-users"></i></div>
            <div class="stat-value">{{ $totalEmployees }}</div>
            <div class="stat-label">Total Employees</div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="stat-card-modern">
            <div class="stat-icon green"><i class="fas fa-user-check"></i></div>
            <div class="stat-value">{{ $activeEmployees }}</div>
            <div class="stat-label">Active Employees</div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="stat-card-modern">
            <div class="stat-icon orange"><i class="fas fa-fingerprint"></i></div>
            <div class="stat-value">{{ $todayAttendance }}%</div>
            <div class="stat-label">Today's Attendance</div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="stat-card-modern">
            <div class="stat-icon purple"><i class="fas fa-wallet"></i></div>
            <div class="stat-value">KES {{ number_format($monthlyPayroll / 1000) }}K</div>
            <div class="stat-label">Monthly Payroll</div>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <!-- Attendance Trend Chart -->
    <div class="col-lg-8">
        <div class="dashboard-card">
            <div class="card-header">
                <div class="card-title"><i class="fas fa-chart-line"></i> Attendance Trend (Last 6 Months)</div>
            </div>
            <div id="attendanceChart" style="height: 300px;"></div>
        </div>
    </div>
    
    <!-- Department Distribution -->
    <div class="col-lg-4">
        <div class="dashboard-card">
            <div class="card-header">
                <div class="card-title"><i class="fas fa-building"></i> Employees by Department</div>
            </div>
            <div id="departmentChart" style="height: 300px;"></div>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- Recent Employees -->
    <div class="col-lg-4">
        <div class="dashboard-card">
            <div class="card-header">
                <div class="card-title"><i class="fas fa-user-plus"></i> Recent Employees</div>
                <a href="{{ route('employees.index') }}" class="btn btn-sm btn-outline-primary">View All</a>
            </div>
            @foreach($recentEmployees as $emp)
            <div class="employee-row">
                <div class="employee-avatar" style="background: {{ ['#3b82f6', '#10b981', '#f59e0b', '#8b5cf6', '#ec4899'][rand(0,4)] }};">
                    {{ strtoupper(substr($emp->first_name, 0, 1) . substr($emp->last_name, 0, 1)) }}
                </div>
                <div class="employee-info">
                    <div class="name">{{ $emp->full_name }}</div>
                    <div class="role">{{ $emp->position->title ?? 'N/A' }}</div>
                </div>
                <div class="employee-status active">Active</div>
            </div>
            @endforeach
        </div>
    </div>
    
    <!-- Recent Activities -->
    <div class="col-lg-4">
        <div class="dashboard-card">
            <div class="card-header">
                <div class="card-title"><i class="fas fa-clock"></i> Recent Activities</div>
            </div>
            @php
                $activities = [
                    ['day' => now()->format('d'), 'month' => now()->format('M'), 'title' => 'Payroll processed for ' . now()->format('F'), 'type' => 'payroll'],
                    ['day' => now()->subDays(1)->format('d'), 'month' => now()->subDays(1)->format('M'), 'title' => 'New employee onboarded', 'type' => 'new'],
                    ['day' => now()->subDays(2)->format('d'), 'month' => now()->subDays(2)->format('M'), 'title' => 'Attendance report generated', 'type' => 'attendance'],
                    ['day' => now()->subDays(3)->format('d'), 'month' => now()->subDays(3)->format('M'), 'title' => 'Shift schedules updated', 'type' => 'attendance'],
                ];
            @endphp
            @foreach($activities as $activity)
            <div class="activity-item">
                <div class="activity-date">
                    <div class="day">{{ $activity['day'] }}</div>
                    <div class="month">{{ $activity['month'] }}</div>
                </div>
                <div class="activity-content">
                    <div class="title">
                        {{ $activity['title'] }}
                        <span class="activity-badge {{ $activity['type'] }}">{{ ucfirst($activity['type']) }}</span>
                    </div>
                    <div class="meta">System Activity</div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    
    <!-- Recent Payroll -->
    <div class="col-lg-4">
        <div class="dashboard-card">
            <div class="card-header">
                <div class="card-title"><i class="fas fa-money-bill-wave"></i> Recent Payroll</div>
                <a href="{{ route('payroll.index') }}" class="btn btn-sm btn-outline-primary">View All</a>
            </div>
            @php
                $recentPayroll = \App\Models\Payroll::with('employee')
                    ->orderBy('created_at', 'desc')
                    ->take(5)
                    ->get();
            @endphp
            @forelse($recentPayroll as $pay)
            <div class="payroll-row">
                <div class="employee">
                    <div class="employee-avatar" style="background: #10b981; width: 36px; height: 36px; font-size: 12px;">
                        {{ strtoupper(substr($pay->employee->first_name, 0, 1) . substr($pay->employee->last_name, 0, 1)) }}
                    </div>
                    <div>
                        <div style="font-weight: 600; font-size: 13px;">{{ $pay->employee->full_name }}</div>
                        <div class="period">{{ date('M Y', mktime(0, 0, 0, $pay->pay_period_month, 1, $pay->pay_period_year)) }}</div>
                    </div>
                </div>
                <div class="amount">KES {{ number_format($pay->net_salary) }}</div>
            </div>
            @empty
            <p class="text-center text-muted py-4">No payroll processed yet</p>
            @endforelse
        </div>
    </div>
</div>

<!-- ApexCharts -->
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
@section('scripts')
<script>
// Attendance Trend Chart
var attendanceOptions = {
    series: [{
        name: 'Present',
        data: [92, 88, 95, 91, 89, 93]
    }, {
        name: 'Absent/Leave',
        data: [8, 12, 5, 9, 11, 7]
    }],
    chart: {
        type: 'area',
        height: 300,
        toolbar: { show: false },
        fontFamily: 'Inter, sans-serif'
    },
    colors: ['#3b82f6', '#f59e0b'],
    fill: {
        type: 'gradient',
        gradient: {
            shadeIntensity: 1,
            opacityFrom: 0.4,
            opacityTo: 0.1,
        }
    },
    stroke: { curve: 'smooth', width: 2 },
    xaxis: {
        categories: ['Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
        labels: { style: { colors: '#64748b' } }
    },
    yaxis: {
        labels: { 
            style: { colors: '#64748b' },
            formatter: function(val) { return val + '%'; }
        }
    },
    grid: { borderColor: '#f1f5f9' },
    legend: { position: 'top' },
    tooltip: { y: { formatter: function(val) { return val + '%'; } } }
};
new ApexCharts(document.querySelector("#attendanceChart"), attendanceOptions).render();

// Department Distribution Chart
var deptOptions = {
    series: [{{ $departments->pluck('employees_count')->implode(',') }}],
    chart: {
        type: 'donut',
        height: 300,
        fontFamily: 'Inter, sans-serif'
    },
    labels: [{!! $departments->pluck('name')->map(fn($n) => "'$n'")->implode(',') !!}],
    colors: ['#3b82f6', '#10b981', '#f59e0b', '#8b5cf6', '#ec4899', '#06b6d4'],
    plotOptions: {
        pie: {
            donut: {
                size: '65%',
                labels: {
                    show: true,
                    total: {
                        show: true,
                        label: 'Total',
                        fontSize: '14px',
                        fontWeight: 600,
                        color: '#64748b'
                    }
                }
            }
        }
    },
    legend: { position: 'bottom', fontSize: '12px' },
    dataLabels: { enabled: false }
};
new ApexCharts(document.querySelector("#departmentChart"), deptOptions).render();
</script>
@endsection
@endsection
