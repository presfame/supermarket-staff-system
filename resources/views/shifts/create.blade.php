@extends('layouts.app')
@section('title', 'Create Shift')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Create New Shift</h2>
    <a href="{{ route('shifts.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left me-2"></i>Back
    </a>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="table-card">
            <form action="{{ route('shifts.store') }}" method="POST">
                @csrf
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Shift Name *</label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" 
                               value="{{ old('name') }}" placeholder="e.g., Morning Shift">
                        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Working Hours *</label>
                        <div class="input-group">
                            <input type="number" name="hours" class="form-control" value="{{ old('hours', 8) }}" min="1" max="12">
                            <span class="input-group-text">hours</span>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Start Time *</label>
                        <input type="time" name="start_time" class="form-control @error('start_time') is-invalid @enderror" 
                               value="{{ old('start_time', '08:00') }}">
                        @error('start_time')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">End Time *</label>
                        <input type="time" name="end_time" class="form-control @error('end_time') is-invalid @enderror" 
                               value="{{ old('end_time', '17:00') }}">
                        @error('end_time')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-12 mb-3">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-control" rows="2" placeholder="Optional description">{{ old('description') }}</textarea>
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2 mt-3">
                    <a href="{{ route('shifts.index') }}" class="btn btn-secondary">Cancel</a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>Create Shift
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
