@extends('layouts.app')
@section('title', 'Payslip Details')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2>Payslip Details</h2>
        <p class="text-muted mb-0">{{ date('F Y', mktime(0, 0, 0, $payroll->pay_period_month, 1, $payroll->pay_period_year)) }}</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('payroll.download', $payroll->id) }}" class="btn btn-success">
            <i class="fas fa-download me-2"></i>Download PDF
        </a>
        <a href="{{ route('payroll.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-2"></i>Back
        </a>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="table-card" id="payslip-content">
            <!-- Header -->
            <div class="text-center mb-4 pb-4 border-bottom">
                <h4 class="mb-1" style="color: var(--primary); font-weight: 700;">
                    <i class="fas fa-store me-2"></i>MANIMO SUPERMARKET
                </h4>
                <p class="text-muted mb-0">Employee Management & Payroll System</p>
            </div>

            <div class="row mb-4 pb-3 border-bottom">
                <div class="col-md-6">
                    <h5 class="mb-2" style="font-weight: 700;">{{ $payroll->employee->full_name }}</h5>
                    <p class="mb-1"><strong>Employee No:</strong> {{ $payroll->employee->employee_number }}</p>
                    <p class="mb-1"><strong>Department:</strong> {{ $payroll->employee->department->name ?? 'N/A' }}</p>
                    <p class="mb-0"><strong>Position:</strong> {{ $payroll->employee->position->title ?? 'N/A' }}</p>
                </div>
                <div class="col-md-6 text-end">
                    <h5 class="mb-2" style="font-weight: 700;">PAYSLIP</h5>
                    <p class="mb-1"><strong>Period:</strong> {{ date('F Y', mktime(0, 0, 0, $payroll->pay_period_month, 1, $payroll->pay_period_year)) }}</p>
                    <span class="badge bg-{{ $payroll->status == 'Paid' ? 'success' : ($payroll->status == 'Processed' ? 'info' : 'warning') }}" style="font-size: 13px;">
                        {{ $payroll->status }}
                    </span>
                </div>
            </div>

            <!-- Days Worked Summary -->
            <div class="row mb-4">
                <div class="col-md-4">
                    <div class="summary-box text-center">
                        <div class="label">Days Worked</div>
                        <div class="value text-success">{{ $payroll->days_worked ?? 0 }}</div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="summary-box text-center">
                        <div class="label">Days Absent</div>
                        <div class="value text-danger">{{ $payroll->days_absent ?? 0 }}</div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="summary-box text-center">
                        <div class="label">Days Leave</div>
                        <div class="value text-info">{{ $payroll->days_leave ?? 0 }}</div>
                    </div>
                </div>
            </div>

            <div class="row mb-4">
                <div class="col-md-6">
                    <h6 class="mb-3" style="color: var(--success); font-weight: 700;">
                        <i class="fas fa-plus-circle me-2"></i>EARNINGS
                    </h6>
                    <table class="table table-sm">
                        <tr>
                            <td>Basic Salary</td>
                            <td class="text-end fw-bold">KES {{ number_format($payroll->basic_salary, 2) }}</td>
                        </tr>
                        @if($payroll->overtime_hours > 0)
                        <tr>
                            <td>Overtime ({{ $payroll->overtime_hours }} hrs)</td>
                            <td class="text-end">KES {{ number_format($payroll->overtime_pay, 2) }}</td>
                        </tr>
                        @endif
                        <tr style="background: #dcfce7;">
                            <td><strong>Gross Salary</strong></td>
                            <td class="text-end"><strong>KES {{ number_format($payroll->gross_salary, 2) }}</strong></td>
                        </tr>
                    </table>
                </div>
                
                <div class="col-md-6">
                    <h6 class="mb-3" style="color: var(--danger); font-weight: 700;">
                        <i class="fas fa-minus-circle me-2"></i>STATUTORY DEDUCTIONS
                    </h6>
                    <table class="table table-sm">
                        <tr>
                            <td>NSSF</td>
                            <td class="text-end">KES {{ number_format($payroll->nssf_deduction, 2) }}</td>
                        </tr>
                        <tr>
                            <td>SHIF (Health)</td>
                            <td class="text-end">KES {{ number_format($payroll->shif_deduction, 2) }}</td>
                        </tr>
                        <tr>
                            <td>Housing Levy</td>
                            <td class="text-end">KES {{ number_format($payroll->housing_levy ?? 0, 2) }}</td>
                        </tr>
                        <tr>
                            <td>PAYE (Tax)</td>
                            <td class="text-end">KES {{ number_format($payroll->paye_deduction, 2) }}</td>
                        </tr>
                        @if($payroll->other_deductions > 0)
                        <tr>
                            <td>Other Deductions</td>
                            <td class="text-end">KES {{ number_format($payroll->other_deductions, 2) }}</td>
                        </tr>
                        @endif
                        <tr style="background: #fee2e2;">
                            <td><strong>Total Deductions</strong></td>
                            <td class="text-end"><strong>KES {{ number_format($payroll->total_deductions, 2) }}</strong></td>
                        </tr>
                    </table>
                </div>
            </div>

            <div class="net-salary-box">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="mb-0" style="font-weight: 600;">NET SALARY</h5>
                        <small style="opacity: 0.8;">Amount payable to employee</small>
                    </div>
                    <h3 class="mb-0" style="font-weight: 800;">KES {{ number_format($payroll->net_salary, 2) }}</h3>
                </div>
            </div>

            <div class="mt-4 pt-3 border-top text-center">
                <small class="text-muted">
                    This is a computer-generated payslip. Generated on {{ now()->format('d M Y, h:i A') }}
                </small>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="table-card mb-4">
            <h6 class="mb-3"><i class="fas fa-user me-2 text-primary"></i>Employee Details</h6>
            <table class="table table-sm">
                <tr>
                    <td class="text-muted">ID Number</td>
                    <td class="fw-bold">{{ $payroll->employee->id_number }}</td>
                </tr>
                <tr>
                    <td class="text-muted">Pay Type</td>
                    <td class="fw-bold">{{ $payroll->employee->pay_type }}</td>
                </tr>
                <tr>
                    <td class="text-muted">Bank</td>
                    <td class="fw-bold">{{ $payroll->employee->bank_name ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <td class="text-muted">Account</td>
                    <td class="fw-bold">{{ $payroll->employee->bank_account ?? 'N/A' }}</td>
                </tr>
            </table>
        </div>
        
        <div class="table-card">
            <h6 class="mb-3"><i class="fas fa-file-alt me-2 text-primary"></i>Statutory Numbers</h6>
            <table class="table table-sm">
                <tr>
                    <td class="text-muted">KRA PIN</td>
                    <td class="fw-bold">{{ $payroll->employee->kra_pin ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <td class="text-muted">NSSF No.</td>
                    <td class="fw-bold">{{ $payroll->employee->nssf_number ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <td class="text-muted">NHIF No.</td>
                    <td class="fw-bold">{{ $payroll->employee->nhif_number ?? 'N/A' }}</td>
                </tr>
            </table>
        </div>
    </div>
</div>
@endsection
