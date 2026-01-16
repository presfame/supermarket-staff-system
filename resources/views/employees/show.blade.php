@extends('layouts.app')
@section('title', $employee->full_name)
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2>{{ $employee->full_name }}</h2>
        <p class="text-muted mb-0">Employee Profile</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('employees.edit', $employee->id) }}" class="btn btn-info">
            <i class="fas fa-edit me-2"></i>Edit
        </a>
        @if(auth()->check() && in_array(auth()->user()->role, ['admin','hr']))
            <a href="{{ route('leave-requests.create', ['employee_id' => $employee->id]) }}" class="btn btn-warning">
                <i class="fas fa-plane-departure me-2"></i>Create Leave
            </a>
        @endif
        @if(auth()->check() && (auth()->user()->email == $employee->email || auth()->user()->employee_id == $employee->id))
            <a href="{{ route('leave-requests.create') }}" class="btn btn-primary">
                <i class="fas fa-paper-plane me-2"></i>Request Leave
            </a>
        @endif
        <a href="{{ route('employees.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-2"></i>Back
        </a>
    </div>
</div>

<div class="row">
    <div class="col-md-4">
        <div class="table-card text-center mb-4">
            <div style="width: 100px; height: 100px; background: var(--primary); border-radius: 20px; display: flex; align-items: center; justify-content: center; color: white; font-weight: 700; font-size: 32px; margin: 0 auto 16px;">
                {{ strtoupper(substr($employee->first_name, 0, 1) . substr($employee->last_name, 0, 1)) }}
            </div>
            <h4>{{ $employee->full_name }}</h4>
            <p class="text-muted">{{ $employee->position->title ?? 'N/A' }}</p>
            <span class="badge bg-{{ $employee->employment_status == 'Active' ? 'success' : 'secondary' }} mb-3">
                {{ $employee->employment_status }}
            </span>
            
            <hr>
            
            <div class="text-start">
                <p><i class="fas fa-id-card text-primary me-2"></i> {{ $employee->employee_number }}</p>
                <p><i class="fas fa-phone text-primary me-2"></i> {{ $employee->phone }}</p>
                <p><i class="fas fa-envelope text-primary me-2"></i> {{ $employee->email ?? 'N/A' }}</p>
                <p><i class="fas fa-building text-primary me-2"></i> {{ $employee->department->name ?? 'N/A' }}</p>
            </div>
        </div>
    </div>
    
    <div class="col-md-8">
        <div class="row g-4">
            <div class="col-md-6">
                <div class="table-card h-100">
                    <h6 class="mb-3"><i class="fas fa-user me-2 text-primary"></i>Personal Information</h6>
                    <table class="table table-sm">
                        <tr><td class="text-muted">ID Number</td><td class="fw-bold">{{ $employee->id_number }}</td></tr>
                        <tr><td class="text-muted">Gender</td><td>{{ $employee->gender }}</td></tr>
                        <tr>
                            <td class="text-muted">Date of Birth</td>
                            <td>
                                <span class="date-display">
                                    <i class="fas fa-calendar"></i>
                                    {{ $employee->date_of_birth ? $employee->date_of_birth->format('d M Y') : '-' }}
                                </span>
                            </td>
                        </tr>
                        <tr><td class="text-muted">Address</td><td>{{ $employee->address ?? '-' }}</td></tr>
                    </table>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="table-card h-100">
                    <h6 class="mb-3"><i class="fas fa-briefcase me-2 text-primary"></i>Employment Details</h6>
                    <table class="table table-sm">
                        <tr>
                            <td class="text-muted">Hire Date</td>
                            <td>
                                <span class="date-display">
                                    <i class="fas fa-calendar"></i>
                                    {{ $employee->date_of_hire ? $employee->date_of_hire->format('d M Y') : '-' }}
                                </span>
                            </td>
                        </tr>
                        <tr><td class="text-muted">Type</td><td>{{ $employee->employment_type }}</td></tr>
                        <tr><td class="text-muted">Pay Type</td><td>{{ $employee->pay_type }}</td></tr>
                        <tr><td class="text-muted">Basic Salary</td><td class="fw-bold text-success">KES {{ number_format($employee->basic_salary) }}</td></tr>
                    </table>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="table-card h-100">
                    <h6 class="mb-3"><i class="fas fa-university me-2 text-primary"></i>Bank Details</h6>
                    <table class="table table-sm">
                        <tr><td class="text-muted">Bank</td><td>{{ $employee->bank_name ?? '-' }}</td></tr>
                        <tr><td class="text-muted">Account</td><td>{{ $employee->bank_account ?? '-' }}</td></tr>
                        <tr><td class="text-muted">Branch</td><td>{{ $employee->bank_branch ?? '-' }}</td></tr>
                    </table>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="table-card h-100">
                    <h6 class="mb-3"><i class="fas fa-file-contract me-2 text-primary"></i>Statutory Info</h6>
                    <table class="table table-sm">
                        <tr><td class="text-muted">KRA PIN</td><td>{{ $employee->kra_pin ?? '-' }}</td></tr>
                        <tr><td class="text-muted">NSSF No</td><td>{{ $employee->nssf_number ?? '-' }}</td></tr>
                        <tr><td class="text-muted">NHIF No</td><td>{{ $employee->nhif_number ?? '-' }}</td></tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
