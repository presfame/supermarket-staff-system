@extends('layouts.app')
@section('title', 'Edit Shift')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Edit Shift</h2>
    <a href="{{ route('shifts.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left me-2"></i>Back
    </a>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="table-card">
            <form action="{{ route('shifts.update', $shift->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Shift Name *</label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" 
                               value="{{ old('name', $shift->name) }}">
                        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Working Hours *</label>
                        <div class="input-group">
                            <input type="number" name="hours" class="form-control" value="{{ old('hours', $shift->hours) }}" min="1" max="12">
                            <span class="input-group-text">hours</span>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Start Time *</label>
                        <input type="time" name="start_time" class="form-control" 
                               value="{{ old('start_time', \Carbon\Carbon::parse($shift->start_time)->format('H:i')) }}">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">End Time *</label>
                        <input type="time" name="end_time" class="form-control" 
                               value="{{ old('end_time', \Carbon\Carbon::parse($shift->end_time)->format('H:i')) }}">
                    </div>
                    <div class="col-12 mb-3">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-control" rows="2">{{ old('description', $shift->description) }}</textarea>
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2 mt-3">
                    <a href="{{ route('shifts.index') }}" class="btn btn-secondary">Cancel</a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>Update Shift
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
