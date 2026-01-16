@extends('layouts.app')
@section('title', 'Payroll Report')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2>Payroll Report</h2>
        <p class="text-muted mb-0">{{ date('F Y', mktime(0, 0, 0, $month, 1, $year)) }}</p>
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

@if($payrolls->count() > 0)
<!-- Summary Cards -->
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="stat-card green">
            <div class="icon"><i class="fas fa-money-bill-wave"></i></div>
            <div class="value">KES {{ number_format($summary['total_gross']) }}</div>
            <div class="label">Total Gross Salary</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card orange">
            <div class="icon"><i class="fas fa-minus-circle"></i></div>
            <div class="value">KES {{ number_format($summary['total_deductions']) }}</div>
            <div class="label">Total Deductions</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card blue">
            <div class="icon"><i class="fas fa-wallet"></i></div>
            <div class="value">KES {{ number_format($summary['total_net']) }}</div>
            <div class="label">Total Net Salary</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card purple">
            <div class="icon"><i class="fas fa-users"></i></div>
            <div class="value">{{ $summary['employees_count'] }}</div>
            <div class="label">Employees Paid</div>
        </div>
    </div>
</div>

<!-- Deductions Breakdown -->
<div class="row g-4 mb-4">
    <div class="col-md-8">
        <div class="table-card">
            <h5><i class="fas fa-building me-2 text-primary"></i>Department Breakdown</h5>
            <table class="table">
                <thead>
                    <tr>
                        <th>Department</th>
                        <th class="text-center">Employees</th>
                        <th class="text-end">Gross Salary</th>
                        <th class="text-end">Net Salary</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($departmentBreakdown as $dept)
                    <tr>
                        <td><strong>{{ $dept['department'] }}</strong></td>
                        <td class="text-center">{{ $dept['count'] }}</td>
                        <td class="text-end">KES {{ number_format($dept['gross']) }}</td>
                        <td class="text-end">KES {{ number_format($dept['net']) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <div class="col-md-4">
        <div class="table-card">
            <h5><i class="fas fa-receipt me-2 text-primary"></i>Statutory Deductions</h5>
            <table class="table table-sm">
                <tr>
                    <td>NSSF</td>
                    <td class="text-end fw-bold">KES {{ number_format($summary['total_nssf']) }}</td>
                </tr>
                <tr>
                    <td>SHIF</td>
                    <td class="text-end fw-bold">KES {{ number_format($summary['total_shif']) }}</td>
                </tr>
                <tr>
                    <td>Housing Levy</td>
                    <td class="text-end fw-bold">KES {{ number_format($summary['total_housing']) }}</td>
                </tr>
                <tr>
                    <td>PAYE</td>
                    <td class="text-end fw-bold">KES {{ number_format($summary['total_paye']) }}</td>
                </tr>
                <tr style="background: #fee2e2;">
                    <td><strong>Total</strong></td>
                    <td class="text-end"><strong>KES {{ number_format($summary['total_deductions']) }}</strong></td>
                </tr>
            </table>
        </div>
    </div>
</div>
@else
<div class="table-card text-center py-5">
    <i class="fas fa-file-invoice fa-3x text-muted mb-3"></i>
    <h5>No payroll data</h5>
    <p class="text-muted">No payroll has been processed for {{ date('F Y', mktime(0, 0, 0, $month, 1, $year)) }}</p>
</div>
@endif
@endsection
