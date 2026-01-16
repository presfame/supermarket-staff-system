@extends('layouts.app')
@section('title', $shift->name . ' Schedule')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2>{{ $shift->name }} - Weekly Schedule</h2>
        <p class="text-muted mb-0">
            <i class="fas fa-clock me-1"></i>
            {{ \Carbon\Carbon::parse($shift->start_time)->format('h:i A') }} - 
            {{ \Carbon\Carbon::parse($shift->end_time)->format('h:i A') }}
            <span class="badge bg-primary ms-2">{{ $shift->hours }} hours</span>
        </p>
    </div>
    <div class="d-flex gap-2 align-items-center">
        <a href="{{ route('shifts.schedule', [$shift->id, 'week' => $weekStart->copy()->subWeek()->format('Y-m-d')]) }}" class="btn btn-secondary">
            <i class="fas fa-chevron-left"></i>
        </a>
        <span class="fw-bold">{{ $weekStart->format('d M') }} - {{ $weekEnd->format('d M Y') }}</span>
        <a href="{{ route('shifts.schedule', [$shift->id, 'week' => $weekStart->copy()->addWeek()->format('Y-m-d')]) }}" class="btn btn-secondary">
            <i class="fas fa-chevron-right"></i>
        </a>
        <a href="{{ route('shifts.index') }}" class="btn btn-secondary ms-2">
            <i class="fas fa-arrow-left me-2"></i>Back
        </a>
    </div>
</div>

<div class="row">
    <div class="col-md-9">
        <div class="table-card">
            <table class="table">
                <thead>
                    <tr>
                        @for($day = 0; $day < 7; $day++)
                        @php $date = $weekStart->copy()->addDays($day); @endphp
                        <th class="text-center {{ $date->isToday() ? 'bg-primary text-white' : '' }}" style="min-width: 120px;">
                            <div>{{ $date->format('D') }}</div>
                            <small>{{ $date->format('d M') }}</small>
                        </th>
                        @endfor
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        @for($day = 0; $day < 7; $day++)
                        @php 
                            $date = $weekStart->copy()->addDays($day);
                            $dateKey = $date->format('Y-m-d');
                            $daySchedules = $schedules[$dateKey] ?? collect();
                        @endphp
                        <td class="p-2 {{ $date->isToday() ? 'bg-light' : '' }}" style="vertical-align: top; min-height: 150px;">
                            @foreach($daySchedules as $schedule)
                            <div class="mb-2 p-2" style="background: #dbeafe; border-radius: 6px; font-size: 12px;">
                                <div style="font-weight: 600;">{{ $schedule->employee->full_name }}</div>
                                <small class="text-muted">{{ $schedule->employee->department->name ?? '' }}</small>
                                <form action="{{ route('shifts.remove-schedule', $shift->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    <input type="hidden" name="schedule_id" value="{{ $schedule->id }}">
                                    <button type="submit" class="btn btn-link btn-sm p-0 text-danger float-end" style="font-size: 10px;" onclick="return confirm('Remove?')">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </form>
                            </div>
                            @endforeach
                            
                            @if($date >= today())
                            <button class="btn btn-sm btn-outline-primary w-100" style="font-size: 11px;" 
                                    onclick="showAddModal('{{ $dateKey }}', '{{ $date->format('D, d M') }}')">
                                <i class="fas fa-plus"></i> Add
                            </button>
                            @endif
                        </td>
                        @endfor
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="table-card">
            <h6 class="mb-3"><i class="fas fa-info-circle me-2 text-primary"></i>Shift Info</h6>
            <table class="table table-sm">
                <tr>
                    <td class="text-muted">Start Time</td>
                    <td class="fw-bold">{{ \Carbon\Carbon::parse($shift->start_time)->format('h:i A') }}</td>
                </tr>
                <tr>
                    <td class="text-muted">End Time</td>
                    <td class="fw-bold">{{ \Carbon\Carbon::parse($shift->end_time)->format('h:i A') }}</td>
                </tr>
                <tr>
                    <td class="text-muted">Duration</td>
                    <td class="fw-bold">{{ $shift->hours }} hours</td>
                </tr>
            </table>
            @if($shift->description)
            <p class="text-muted" style="font-size: 12px;">{{ $shift->description }}</p>
            @endif
        </div>
    </div>
</div>

<!-- Add Schedule Modal -->
<div class="modal fade" id="addScheduleModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="{{ route('shifts.add-schedule', $shift->id) }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-calendar-plus me-2"></i>Schedule Employees</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="schedule_date" id="scheduleDate">
                    <div class="alert alert-info mb-3">
                        <i class="fas fa-calendar me-2"></i>
                        <strong>Date:</strong> <span id="scheduleDateDisplay"></span>
                    </div>
                    
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <label class="form-label fw-bold mb-0">Select Employees</label>
                        <div>
                            <button type="button" class="btn btn-sm btn-outline-primary" onclick="selectAll()">Select All</button>
                            <button type="button" class="btn btn-sm btn-outline-secondary" onclick="deselectAll()">Deselect All</button>
                        </div>
                    </div>
                    
                    <div style="max-height: 300px; overflow-y: auto; border: 1px solid var(--border-color); border-radius: 8px; padding: 12px;">
                        @foreach($employees as $emp)
                        <div class="form-check mb-2">
                            <input class="form-check-input employee-checkbox" type="checkbox" name="employee_ids[]" value="{{ $emp->id }}" id="emp{{ $emp->id }}">
                            <label class="form-check-label" for="emp{{ $emp->id }}">
                                <strong>{{ $emp->full_name }}</strong>
                                <small class="text-muted">({{ $emp->department->name ?? 'N/A' }})</small>
                            </label>
                        </div>
                        @endforeach
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary"><i class="fas fa-plus me-2"></i>Add to Schedule</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function showAddModal(date, displayDate) {
    document.getElementById('scheduleDate').value = date;
    document.getElementById('scheduleDateDisplay').textContent = displayDate;
    // Reset all checkboxes when opening modal
    document.querySelectorAll('.employee-checkbox').forEach(cb => cb.checked = false);
    new bootstrap.Modal(document.getElementById('addScheduleModal')).show();
}

function selectAll() {
    document.querySelectorAll('.employee-checkbox').forEach(cb => cb.checked = true);
}

function deselectAll() {
    document.querySelectorAll('.employee-checkbox').forEach(cb => cb.checked = false);
}
</script>
@endsection
