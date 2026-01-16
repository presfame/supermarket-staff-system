@extends('layouts.app')
@section('title', 'Reports')
@section('content')
<div class="mb-4">
    <h2>Reports</h2>
    <p class="text-muted mb-0">Generate and view comprehensive reports</p>
</div>

<div class="row g-4">
    <div class="col-md-4">
        <div class="table-card h-100">
            <div class="text-center py-3">
                <div style="width: 70px; height: 70px; background: #dbeafe; border-radius: 16px; display: flex; align-items: center; justify-content: center; margin: 0 auto 16px;">
                    <i class="fas fa-calendar-check fa-2x" style="color: var(--primary);"></i>
                </div>
                <h5>Attendance Report</h5>
                <p class="text-muted mb-3">View employee attendance summary, late arrivals, absences and leave records</p>
                <a href="{{ route('reports.attendance') }}" class="btn btn-primary">
                    <i class="fas fa-file-alt me-2"></i>Generate Report
                </a>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="table-card h-100">
            <div class="text-center py-3">
                <div style="width: 70px; height: 70px; background: #d1fae5; border-radius: 16px; display: flex; align-items: center; justify-content: center; margin: 0 auto 16px;">
                    <i class="fas fa-file-invoice-dollar fa-2x" style="color: var(--success);"></i>
                </div>
                <h5>Payroll Report</h5>
                <p class="text-muted mb-3">View payroll summaries, deductions breakdown and department-wise costs</p>
                <a href="{{ route('reports.payroll') }}" class="btn btn-success">
                    <i class="fas fa-file-alt me-2"></i>Generate Report
                </a>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="table-card h-100">
            <div class="text-center py-3">
                <div style="width: 70px; height: 70px; background: #fef3c7; border-radius: 16px; display: flex; align-items: center; justify-content: center; margin: 0 auto 16px;">
                    <i class="fas fa-chart-line fa-2x" style="color: var(--warning);"></i>
                </div>
                <h5>Performance Report</h5>
                <p class="text-muted mb-3">Track employee attendance rates, punctuality and overtime hours</p>
                <a href="{{ route('reports.performance') }}" class="btn btn-warning">
                    <i class="fas fa-file-alt me-2"></i>Generate Report
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
