@extends('layouts.app')
@section('title', 'Add Employee')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Add New Employee</h2>
    <a href="{{ route('employees.index') }}" class="btn btn-secondary">Back to List</a>
</div>

<div class="table-card">
    <form action="{{ route('employees.store') }}" method="POST">
        @csrf
        
        <div class="row mb-4">
            <div class="col-12">
                <h5 class="text-primary mb-3"><i class="fas fa-user me-2"></i>Personal Information</h5>
            </div>
            <div class="col-md-4 mb-3">
                <label class="form-label">First Name <span class="text-danger">*</span></label>
                <input type="text" name="first_name" class="form-control @error('first_name') is-invalid @enderror" value="{{ old('first_name') }}" required>
                @error('first_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-4 mb-3">
                <label class="form-label">Last Name <span class="text-danger">*</span></label>
                <input type="text" name="last_name" class="form-control @error('last_name') is-invalid @enderror" value="{{ old('last_name') }}" required>
                @error('last_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-4 mb-3">
                <label class="form-label">ID Number <span class="text-danger">*</span></label>
                <input type="text" name="id_number" class="form-control @error('id_number') is-invalid @enderror" value="{{ old('id_number') }}" required>
                @error('id_number')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-4 mb-3">
                <label class="form-label">Phone <span class="text-danger">*</span></label>
                <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone') }}" required>
                @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-4 mb-3">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}">
                @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-4 mb-3">
                <label class="form-label">Create User Account</label>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="create_user" id="create_user" {{ old('create_user') ? 'checked' : '' }}>
                    <label class="form-check-label" for="create_user">Create login account for this employee</label>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <label class="form-label">Password (if creating account)</label>
                <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" value="{{ old('password') }}" placeholder="leave blank for default 'password'">
                @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-4 mb-3">
                <label class="form-label">Date of Birth <span class="text-danger">*</span></label>
                <input type="date" name="date_of_birth" class="form-control @error('date_of_birth') is-invalid @enderror" value="{{ old('date_of_birth') }}" required>
                @error('date_of_birth')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-4 mb-3">
                <label class="form-label">Gender <span class="text-danger">*</span></label>
                <select name="gender" class="form-select @error('gender') is-invalid @enderror" required>
                    <option value="">Select Gender</option>
                    <option value="Male" {{ old('gender') == 'Male' ? 'selected' : '' }}>Male</option>
                    <option value="Female" {{ old('gender') == 'Female' ? 'selected' : '' }}>Female</option>
                </select>
                @error('gender')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-8 mb-3">
                <label class="form-label">Address</label>
                <textarea name="address" class="form-control" rows="2">{{ old('address') }}</textarea>
            </div>
        </div>

        <hr>

        <div class="row mb-4">
            <div class="col-12">
                <h5 class="text-primary mb-3"><i class="fas fa-briefcase me-2"></i>Employment Details</h5>
            </div>
            <div class="col-md-4 mb-3">
                <label class="form-label">Department <span class="text-danger">*</span></label>
                <select name="department_id" id="department_id" class="form-select @error('department_id') is-invalid @enderror" required>
                    <option value="">Select Department</option>
                    @foreach($departments as $department)
                        <option value="{{ $department->id }}" {{ old('department_id') == $department->id ? 'selected' : '' }}>{{ $department->name }}</option>
                    @endforeach
                </select>
                @error('department_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-4 mb-3">
                <label class="form-label">Position <span class="text-danger">*</span></label>
                <select name="position_id" id="position_id" class="form-select @error('position_id') is-invalid @enderror" required>
                    <option value="">Select Position</option>
                    @foreach($positions as $position)
                        <option value="{{ $position->id }}" 
                                data-min-salary="{{ $position->min_salary }}" 
                                data-max-salary="{{ $position->max_salary }}"
                                data-department="{{ $position->department_id }}"
                                {{ old('position_id') == $position->id ? 'selected' : '' }}>
                            {{ $position->title }}
                        </option>
                    @endforeach
                </select>
                @error('position_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                <small id="salaryRange" class="text-muted"></small>
            </div>
            <div class="col-md-4 mb-3">
                <label class="form-label">Date of Hire <span class="text-danger">*</span></label>
                <input type="date" name="date_of_hire" class="form-control @error('date_of_hire') is-invalid @enderror" value="{{ old('date_of_hire', date('Y-m-d')) }}" required>
                @error('date_of_hire')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-4 mb-3">
                <label class="form-label">Employment Type</label>
                <select name="employment_type" class="form-select">
                    <option value="Full-time" {{ old('employment_type') == 'Full-time' ? 'selected' : '' }}>Full-time</option>
                    <option value="Part-time" {{ old('employment_type') == 'Part-time' ? 'selected' : '' }}>Part-time</option>
                    <option value="Contract" {{ old('employment_type') == 'Contract' ? 'selected' : '' }}>Contract</option>
                </select>
            </div>
            <div class="col-md-4 mb-3">
                <label class="form-label">Employment Status</label>
                <select name="employment_status" class="form-select">
                    <option value="Active" {{ old('employment_status') == 'Active' ? 'selected' : '' }}>Active</option>
                    <option value="Inactive" {{ old('employment_status') == 'Inactive' ? 'selected' : '' }}>Inactive</option>
                    <option value="Suspended" {{ old('employment_status') == 'Suspended' ? 'selected' : '' }}>Suspended</option>
                </select>
            </div>
        </div>

        <hr>

        <div class="row mb-4">
            <div class="col-12">
                <h5 class="text-primary mb-3"><i class="fas fa-money-bill-wave me-2"></i>Salary Information</h5>
            </div>
            <div class="col-md-4 mb-3">
                <label class="form-label">Pay Type <span class="text-danger">*</span></label>
                <select name="pay_type" class="form-select @error('pay_type') is-invalid @enderror" required>
                    <option value="Monthly" {{ old('pay_type') == 'Monthly' ? 'selected' : '' }}>Monthly</option>
                    <option value="Hourly" {{ old('pay_type') == 'Hourly' ? 'selected' : '' }}>Hourly</option>
                </select>
                @error('pay_type')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-4 mb-3">
                <label class="form-label">Basic Salary (KES) <span class="text-danger">*</span></label>
                <input type="number" step="0.01" name="basic_salary" id="basic_salary" class="form-control @error('basic_salary') is-invalid @enderror" value="{{ old('basic_salary') }}" required>
                @error('basic_salary')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-4 mb-3">
                <label class="form-label">Hourly Rate (KES)</label>
                <input type="number" step="0.01" name="hourly_rate" class="form-control" value="{{ old('hourly_rate') }}">
            </div>
        </div>

        <hr>

        <div class="row mb-4">
            <div class="col-12">
                <h5 class="text-primary mb-3"><i class="fas fa-university me-2"></i>Bank Details</h5>
            </div>
            <div class="col-md-4 mb-3">
                <label class="form-label">Bank Name</label>
                <input type="text" name="bank_name" class="form-control" value="{{ old('bank_name') }}">
            </div>
            <div class="col-md-4 mb-3">
                <label class="form-label">Bank Account</label>
                <input type="text" name="bank_account" class="form-control" value="{{ old('bank_account') }}">
            </div>
            <div class="col-md-4 mb-3">
                <label class="form-label">Bank Branch</label>
                <input type="text" name="bank_branch" class="form-control" value="{{ old('bank_branch') }}">
            </div>
        </div>

        <hr>

        <div class="row mb-4">
            <div class="col-12">
                <h5 class="text-primary mb-3"><i class="fas fa-file-contract me-2"></i>Statutory Information</h5>
            </div>
            <div class="col-md-4 mb-3">
                <label class="form-label">KRA PIN</label>
                <input type="text" name="kra_pin" class="form-control" value="{{ old('kra_pin') }}">
            </div>
            <div class="col-md-4 mb-3">
                <label class="form-label">NSSF Number</label>
                <input type="text" name="nssf_number" class="form-control" value="{{ old('nssf_number') }}">
            </div>
            <div class="col-md-4 mb-3">
                <label class="form-label">NHIF Number</label>
                <input type="text" name="nhif_number" class="form-control" value="{{ old('nhif_number') }}">
            </div>
        </div>

        <div class="d-flex justify-content-end gap-2">
            <a href="{{ route('employees.index') }}" class="btn btn-secondary">Cancel</a>
            <button type="submit" class="btn btn-primary"><i class="fas fa-save me-2"></i>Save Employee</button>
        </div>
    </form>
</div>

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const departmentSelect = document.getElementById('department_id');
    const positionSelect = document.getElementById('position_id');
    const salaryInput = document.getElementById('basic_salary');
    const salaryRangeText = document.getElementById('salaryRange');
    const allPositions = positionSelect.querySelectorAll('option');

    // Filter positions by department
    departmentSelect.addEventListener('change', function() {
        const deptId = this.value;
        positionSelect.innerHTML = '<option value="">Select Position</option>';
        
        allPositions.forEach(opt => {
            if (opt.value && opt.dataset.department == deptId) {
                positionSelect.appendChild(opt.cloneNode(true));
            }
        });
        
        salaryRangeText.textContent = '';
        salaryInput.value = '';
    });

    // Auto-fill salary when position is selected
    positionSelect.addEventListener('change', function() {
        const selected = this.options[this.selectedIndex];
        if (selected.value) {
            const minSalary = parseFloat(selected.dataset.minSalary) || 0;
            const maxSalary = parseFloat(selected.dataset.maxSalary) || 0;
            
            // Set salary to minimum of range
            salaryInput.value = minSalary;
            salaryRangeText.textContent = 'Salary range: KES ' + minSalary.toLocaleString() + ' - ' + maxSalary.toLocaleString();
        } else {
            salaryRangeText.textContent = '';
        }
    });
});
</script>
@endsection
@endsection
