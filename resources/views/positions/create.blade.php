@extends('layouts.app')
@section('title', 'Add Position')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Add Position</h2>
    <a href="{{ route('positions.index') }}" class="btn btn-secondary">Back to List</a>
</div>

<div class="table-card">
    <form action="{{ route('positions.store') }}" method="POST">
        @csrf
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Position Title <span class="text-danger">*</span></label>
                <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title') }}" required>
                @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Department <span class="text-danger">*</span></label>
                <select name="department_id" class="form-select @error('department_id') is-invalid @enderror" required>
                    <option value="">Select Department</option>
                    @foreach($departments as $department)
                        <option value="{{ $department->id }}" {{ old('department_id') == $department->id ? 'selected' : '' }}>{{ $department->name }}</option>
                    @endforeach
                </select>
                @error('department_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-12 mb-3">
                <label class="form-label">Description</label>
                <textarea name="description" class="form-control" rows="3">{{ old('description') }}</textarea>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Minimum Salary (KES)</label>
                <input type="number" step="0.01" name="min_salary" class="form-control" value="{{ old('min_salary') }}">
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Maximum Salary (KES)</label>
                <input type="number" step="0.01" name="max_salary" class="form-control" value="{{ old('max_salary') }}">
            </div>
            <div class="col-md-6 mb-3">
                <div class="form-check">
                    <input type="checkbox" name="is_active" class="form-check-input" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                    <label class="form-check-label">Active</label>
                </div>
            </div>
        </div>
        <div class="d-flex justify-content-end gap-2">
            <a href="{{ route('positions.index') }}" class="btn btn-secondary">Cancel</a>
            <button type="submit" class="btn btn-primary"><i class="fas fa-save me-2"></i>Save Position</button>
        </div>
    </form>
</div>
@endsection
