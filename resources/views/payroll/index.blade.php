@extends('layouts.app')
@section('title', 'Payroll')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2>Payroll Records</h2>
        <p class="text-muted mb-0">Manage employee salary payments</p>
    </div>
    <div class="d-flex gap-2">
        <form method="GET" class="d-flex gap-2">
            <select name="month" class="form-select" style="width: 130px;">
                @for($m = 1; $m <= 12; $m++)
                <option value="{{ $m }}" {{ ($filterMonth ?? now()->month) == $m ? 'selected' : '' }}>
                    {{ date('F', mktime(0, 0, 0, $m, 1)) }}
                </option>
                @endfor
            </select>
            <select name="year" class="form-select" style="width: 100px;">
                @for($y = 2024; $y <= 2026; $y++)
                <option value="{{ $y }}" {{ ($filterYear ?? now()->year) == $y ? 'selected' : '' }}>{{ $y }}</option>
                @endfor
            </select>
            <button type="submit" class="btn btn-secondary"><i class="fas fa-filter"></i></button>
        </form>
        <a href="{{ route('payroll.process.form') }}" class="btn btn-primary">
            <i class="fas fa-calculator me-2"></i>Process Payroll
        </a>
    </div>
</div>

<!-- Summary Cards -->
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="stat-card green">
            <div class="icon"><i class="fas fa-money-bill-wave"></i></div>
            <div class="value">KES {{ number_format($payrolls->sum('gross_salary')) }}</div>
            <div class="label">Total Gross</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card orange">
            <div class="icon"><i class="fas fa-minus-circle"></i></div>
            <div class="value">KES {{ number_format($payrolls->sum('total_deductions')) }}</div>
            <div class="label">Total Deductions</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card blue">
            <div class="icon"><i class="fas fa-wallet"></i></div>
            <div class="value">KES {{ number_format($payrolls->sum('net_salary')) }}</div>
            <div class="label">Total Net</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card purple">
            <div class="icon"><i class="fas fa-users"></i></div>
            <div class="value">{{ $payrolls->count() }}</div>
            <div class="label">Employees Paid</div>
        </div>
    </div>
</div>

<div class="table-card">
    <table class="table datatable">
        <thead>
            <tr>
                <th>Employee</th>
                <th>Period</th>
                <th>Gross Salary</th>
                <th>NSSF</th>
                <th>SHIF</th>
                <th>PAYE</th>
                <th>Net Salary</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($payrolls as $payroll)
            <tr>
                <td>
                    <div class="d-flex align-items-center gap-2">
                        <div style="width: 36px; height: 36px; background: var(--success); border-radius: 8px; display: flex; align-items: center; justify-content: center; color: white; font-weight: 600; font-size: 12px;">
                            {{ strtoupper(substr($payroll->employee->first_name, 0, 1) . substr($payroll->employee->last_name, 0, 1)) }}
                        </div>
                        <div>
                            <div style="font-weight: 600;">{{ $payroll->employee->full_name }}</div>
                            <small class="text-muted">{{ $payroll->employee->employee_number }}</small>
                        </div>
                    </div>
                </td>
                <td>
                    <span class="date-display">
                        <i class="fas fa-calendar"></i>
                        {{ date('M Y', mktime(0, 0, 0, $payroll->pay_period_month, 1, $payroll->pay_period_year)) }}
                    </span>
                </td>
                <td class="fw-bold">KES {{ number_format($payroll->gross_salary) }}</td>
                <td class="text-danger">{{ number_format($payroll->nssf_deduction) }}</td>
                <td class="text-danger">{{ number_format($payroll->shif_deduction) }}</td>
                <td class="text-danger">{{ number_format($payroll->paye_deduction) }}</td>
                <td class="fw-bold text-success">KES {{ number_format($payroll->net_salary) }}</td>
                <td>
                    <span class="badge bg-{{ $payroll->status == 'Paid' ? 'success' : ($payroll->status == 'Pending' ? 'warning' : 'secondary') }}">
                        {{ $payroll->status }}
                    </span>
                </td>
                <td>
                    <div class="d-flex gap-1">
                        <a href="{{ route('payroll.show', $payroll->id) }}" class="btn btn-sm btn-info" title="View Payslip">
                            <i class="fas fa-eye"></i>
                        </a>
                        <a href="{{ route('payroll.download', $payroll->id) }}" class="btn btn-sm btn-success" title="Download PDF">
                            <i class="fas fa-download"></i>
                        </a>
                        <form action="{{ route('payroll.reverse', $payroll->id) }}" method="POST" class="d-inline" 
                              onsubmit="return confirm('⚠️ Are you sure you want to REVERSE/DELETE this payroll for {{ $payroll->employee->full_name }}?\n\nThis action cannot be undone!')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger" title="Reverse Payroll">
                                <i class="fas fa-undo"></i>
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
