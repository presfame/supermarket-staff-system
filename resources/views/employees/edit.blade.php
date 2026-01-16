@extends('layouts.app')
@section('title', 'Edit Employee')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Edit Employee: {{ $employee->full_name }}</h2>
    <a href="{{ route('employees.index') }}" class="btn btn-secondary">Back to List</a>
</div>

<div class="table-card">
    <form action="{{ route('employees.update', $employee->id) }}" method="POST">
        @csrf
        @method('PUT')
        
        <div class="row mb-4">
            <div class="col-12">
                <h5 class="text-primary mb-3"><i class="fas fa-user me-2"></i>Personal Information</h5>
            </div>
            <div class="col-md-4 mb-3">
                <label class="form-label">Employee Number</label>
                <input type="text" class="form-control" value="{{ $employee->employee_number }}" readonly>
            </div>
            <div class="col-md-4 mb-3">
                <label class="form-label">First Name <span class="text-danger">*</span></label>
                <input type="text" name="first_name" class="form-control @error('first_name') is-invalid @enderror" value="{{ old('first_name', $employee->first_name) }}" required>
                @error('first_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-4 mb-3">
                <label class="form-label">Last Name <span class="text-danger">*</span></label>
                <input type="text" name="last_name" class="form-control @error('last_name') is-invalid @enderror" value="{{ old('last_name', $employee->last_name) }}" required>
                @error('last_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-4 mb-3">
                <label class="form-label">ID Number</label>
                <input type="text" name="id_number" class="form-control" value="{{ old('id_number', $employee->id_number) }}">
            </div>
            <div class="col-md-4 mb-3">
                <label class="form-label">Phone</label>
                <input type="text" name="phone" class="form-control" value="{{ old('phone', $employee->phone) }}">
            </div>
            <div class="col-md-4 mb-3">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control" value="{{ old('email', $employee->email) }}">
            </div>
            <div class="col-md-4 mb-3">
                <label class="form-label">Date of Birth</label>
                <input type="date" name="date_of_birth" class="form-control" value="{{ old('date_of_birth', $employee->date_of_birth?->format('Y-m-d')) }}">
            </div>
            <div class="col-md-4 mb-3">
                <label class="form-label">Gender</label>
                <select name="gender" class="form-select">
                    <option value="Male" {{ old('gender', $employee->gender) == 'Male' ? 'selected' : '' }}>Male</option>
                    <option value="Female" {{ old('gender', $employee->gender) == 'Female' ? 'selected' : '' }}>Female</option>
                    <option value="Other" {{ old('gender', $employee->gender) == 'Other' ? 'selected' : '' }}>Other</option>
                </select>
            </div>
            <div class="col-md-4 mb-3">
                <label class="form-label">Address</label>
                <textarea name="address" class="form-control" rows="2">{{ old('address', $employee->address) }}</textarea>
            </div>
        </div>

        <hr>

        <div class="row mb-4">
            <div class="col-12">
                <h5 class="text-primary mb-3"><i class="fas fa-briefcase me-2"></i>Employment Details</h5>
            </div>
            <div class="col-md-4 mb-3">
                <label class="form-label">Department <span class="text-danger">*</span></label>
                <select name="department_id" class="form-select @error('department_id') is-invalid @enderror" required>
                    @foreach($departments as $department)
                        <option value="{{ $department->id }}" {{ old('department_id', $employee->department_id) == $department->id ? 'selected' : '' }}>{{ $department->name }}</option>
                    @endforeach
                </select>
                @error('department_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-4 mb-3">
                <label class="form-label">Position <span class="text-danger">*</span></label>
                <select name="position_id" class="form-select @error('position_id') is-invalid @enderror" required>
                    @foreach($positions as $position)
                        <option value="{{ $position->id }}" {{ old('position_id', $employee->position_id) == $position->id ? 'selected' : '' }}>{{ $position->title }}</option>
                    @endforeach
                </select>
                @error('position_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-4 mb-3">
                <label class="form-label">Date of Hire</label>
                <input type="date" name="date_of_hire" class="form-control" value="{{ old('date_of_hire', $employee->date_of_hire?->format('Y-m-d')) }}">
            </div>
            <div class="col-md-4 mb-3">
                <label class="form-label">Employment Type</label>
                <select name="employment_type" class="form-select">
                    <option value="Full-time" {{ old('employment_type', $employee->employment_type) == 'Full-time' ? 'selected' : '' }}>Full-time</option>
                    <option value="Part-time" {{ old('employment_type', $employee->employment_type) == 'Part-time' ? 'selected' : '' }}>Part-time</option>
                    <option value="Contract" {{ old('employment_type', $employee->employment_type) == 'Contract' ? 'selected' : '' }}>Contract</option>
                </select>
            </div>
            <div class="col-md-4 mb-3">
                <label class="form-label">Employment Status</label>
                <select name="employment_status" class="form-select">
                    <option value="Active" {{ old('employment_status', $employee->employment_status) == 'Active' ? 'selected' : '' }}>Active</option>
                    <option value="Inactive" {{ old('employment_status', $employee->employment_status) == 'Inactive' ? 'selected' : '' }}>Inactive</option>
                    <option value="Suspended" {{ old('employment_status', $employee->employment_status) == 'Suspended' ? 'selected' : '' }}>Suspended</option>
                    <option value="Terminated" {{ old('employment_status', $employee->employment_status) == 'Terminated' ? 'selected' : '' }}>Terminated</option>
                </select>
            </div>
        </div>

        <hr>

        <div class="row mb-4">
            <div class="col-12">
                <h5 class="text-primary mb-3"><i class="fas fa-money-bill-wave me-2"></i>Salary Information</h5>
            </div>
            <div class="col-md-4 mb-3">
                <label class="form-label">Pay Type</label>
                <select name="pay_type" class="form-select">
                    <option value="Monthly" {{ old('pay_type', $employee->pay_type) == 'Monthly' ? 'selected' : '' }}>Monthly</option>
                    <option value="Hourly" {{ old('pay_type', $employee->pay_type) == 'Hourly' ? 'selected' : '' }}>Hourly</option>
                </select>
            </div>
            <div class="col-md-4 mb-3">
                <label class="form-label">Basic Salary (KES) <span class="text-danger">*</span></label>
                <input type="number" step="0.01" name="basic_salary" class="form-control @error('basic_salary') is-invalid @enderror" value="{{ old('basic_salary', $employee->basic_salary) }}" required>
                @error('basic_salary')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-4 mb-3">
                <label class="form-label">Hourly Rate (KES)</label>
                <input type="number" step="0.01" name="hourly_rate" class="form-control" value="{{ old('hourly_rate', $employee->hourly_rate) }}">
            </div>
        </div>

        <hr>

        <div class="row mb-4">
            <div class="col-12">
                <h5 class="text-primary mb-3"><i class="fas fa-university me-2"></i>Bank Details</h5>
            </div>
            <div class="col-md-4 mb-3">
                <label class="form-label">Bank Name</label>
                <input type="text" name="bank_name" class="form-control" value="{{ old('bank_name', $employee->bank_name) }}">
            </div>
            <div class="col-md-4 mb-3">
                <label class="form-label">Bank Account</label>
                <input type="text" name="bank_account" class="form-control" value="{{ old('bank_account', $employee->bank_account) }}">
            </div>
            <div class="col-md-4 mb-3">
                <label class="form-label">Bank Branch</label>
                <input type="text" name="bank_branch" class="form-control" value="{{ old('bank_branch', $employee->bank_branch) }}">
            </div>
        </div>

        <hr>

        <div class="row mb-4">
            <div class="col-12">
                <h5 class="text-primary mb-3"><i class="fas fa-file-contract me-2"></i>Statutory Information</h5>
            </div>
            <div class="col-md-4 mb-3">
                <label class="form-label">KRA PIN</label>
                <input type="text" name="kra_pin" class="form-control" value="{{ old('kra_pin', $employee->kra_pin) }}">
            </div>
            <div class="col-md-4 mb-3">
                <label class="form-label">NSSF Number</label>
                <input type="text" name="nssf_number" class="form-control" value="{{ old('nssf_number', $employee->nssf_number) }}">
            </div>
            <div class="col-md-4 mb-3">
                <label class="form-label">NHIF Number</label>
                <input type="text" name="nhif_number" class="form-control" value="{{ old('nhif_number', $employee->nhif_number) }}">
            </div>
        </div>

        <div class="d-flex justify-content-end gap-2">
            <a href="{{ route('employees.index') }}" class="btn btn-secondary">Cancel</a>
            <button type="submit" class="btn btn-primary"><i class="fas fa-save me-2"></i>Update Employee</button>
        </div>
    </form>
</div>
@endsection
