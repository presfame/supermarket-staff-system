@extends('layouts.app')
@section('title', 'My Payslips')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2>My Payslips</h2>
        <p class="text-muted mb-0">View your salary payment history</p>
    </div>
</div>

@if(auth()->user()->employee_id)
<div class="table-card">
    <table class="table datatable">
        <thead>
            <tr>
                <th>Pay Period</th>
                <th>Gross Salary</th>
                <th>NSSF</th>
                <th>SHIF</th>
                <th>PAYE</th>
                <th>Total Deductions</th>
                <th>Net Salary</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($payrolls as $payroll)
            <tr>
                <td>
                    <span class="date-display">
                        <i class="fas fa-calendar"></i>
                        <strong>{{ date('F Y', mktime(0, 0, 0, $payroll->pay_period_month, 1, $payroll->pay_period_year)) }}</strong>
                    </span>
                </td>
                <td class="fw-bold">KES {{ number_format($payroll->gross_salary) }}</td>
                <td class="text-danger">{{ number_format($payroll->nssf_deduction) }}</td>
                <td class="text-danger">{{ number_format($payroll->shif_deduction) }}</td>
                <td class="text-danger">{{ number_format($payroll->paye_deduction) }}</td>
                <td class="text-danger">KES {{ number_format($payroll->total_deductions) }}</td>
                <td class="fw-bold text-success">KES {{ number_format($payroll->net_salary) }}</td>
                <td>
                    <span class="badge bg-{{ $payroll->status == 'Paid' ? 'success' : 'warning' }}">
                        {{ $payroll->status }}
                    </span>
                </td>
                <td>
                    <div class="d-flex gap-1">
                        <a href="{{ route('payroll.show.own', $payroll->id) }}" class="btn btn-sm btn-info" title="View">
                            <i class="fas fa-eye"></i>
                        </a>
                        <a href="{{ route('payroll.download.own', $payroll->id) }}" class="btn btn-sm btn-success" title="Download PDF">
                            <i class="fas fa-download"></i>
                        </a>
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@else
<div class="table-card text-center py-5">
    <i class="fas fa-user-slash fa-3x text-muted mb-3"></i>
    <h5>No Employee Profile Linked</h5>
    <p class="text-muted">Your account is not linked to an employee profile. Please contact HR.</p>
</div>
@endif
@endsection
