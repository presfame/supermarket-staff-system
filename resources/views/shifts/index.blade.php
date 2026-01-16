@extends('layouts.app')
@section('title', 'Shift Management')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2>Shift Management</h2>
        <p class="text-muted mb-0">Manage work shifts and schedule employees</p>
    </div>
    <a href="{{ route('shifts.create') }}" class="btn btn-primary">
        <i class="fas fa-plus me-2"></i>Add Shift
    </a>
</div>

<!-- Today's Schedule Summary -->
<div class="table-card mb-4">
    <h5 class="mb-3"><i class="fas fa-calendar-day me-2 text-primary"></i>Today's Schedule - {{ now()->format('l, d M Y') }}</h5>
    <div class="row g-3">
        @foreach($shifts as $shift)
        <div class="col-md-3">
            <div style="border: 1px solid var(--border-color); border-radius: 10px; padding: 16px;">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <h6 class="mb-0">{{ $shift->name }}</h6>
                    <span class="badge bg-primary">{{ $shift->hours }}h</span>
                </div>
                <p class="text-muted mb-2" style="font-size: 12px;">
                    <i class="fas fa-clock me-1"></i>
                    {{ \Carbon\Carbon::parse($shift->start_time)->format('h:i A') }} - 
                    {{ \Carbon\Carbon::parse($shift->end_time)->format('h:i A') }}
                </p>
                <div class="d-flex align-items-center justify-content-between">
                    <span style="font-size: 13px;">
                        <i class="fas fa-users me-1 text-muted"></i>
                        <strong>{{ isset($todaySchedules[$shift->id]) ? $todaySchedules[$shift->id]->count() : 0 }}</strong> scheduled
                    </span>
                    <a href="{{ route('shifts.schedule', $shift->id) }}" class="btn btn-sm btn-info">
                        <i class="fas fa-calendar-alt"></i>
                    </a>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>

<!-- Shifts List -->
<div class="table-card">
    <h5 class="mb-3"><i class="fas fa-list me-2 text-primary"></i>All Shifts</h5>
    <table class="table">
        <thead>
            <tr>
                <th>Shift Name</th>
                <th>Time</th>
                <th>Duration</th>
                <th>Description</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($shifts as $shift)
            <tr>
                <td><strong>{{ $shift->name }}</strong></td>
                <td>
                    {{ \Carbon\Carbon::parse($shift->start_time)->format('h:i A') }} - 
                    {{ \Carbon\Carbon::parse($shift->end_time)->format('h:i A') }}
                </td>
                <td><span class="badge bg-primary">{{ $shift->hours }} hours</span></td>
                <td class="text-muted">{{ $shift->description ?? '-' }}</td>
                <td>
                    <div class="d-flex gap-1">
                        <a href="{{ route('shifts.schedule', $shift->id) }}" class="btn btn-sm btn-success" title="Schedule">
                            <i class="fas fa-calendar-alt"></i>
                        </a>
                        <a href="{{ route('shifts.edit', $shift->id) }}" class="btn btn-sm btn-info" title="Edit">
                            <i class="fas fa-edit"></i>
                        </a>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="text-center py-4 text-muted">No shifts defined yet</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
