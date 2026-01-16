@extends('layouts.app')
@section('title', 'Positions')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2>Positions</h2>
        <p class="text-muted mb-0">Manage job positions and salary ranges</p>
    </div>
    <a href="{{ route('positions.create') }}" class="btn btn-primary">
        <i class="fas fa-plus me-2"></i>Add Position
    </a>
</div>

<div class="table-card">
    <table class="table datatable">
        <thead>
            <tr>
                <th>Position</th>
                <th>Department</th>
                <th>Salary Range</th>
                <th>Employees</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($positions as $position)
            <tr>
                <td>
                    <div style="font-weight: 600;">{{ $position->title }}</div>
                    @if($position->description)
                    <small class="text-muted">{{ Str::limit($position->description, 40) }}</small>
                    @endif
                </td>
                <td>
                    <span class="badge bg-secondary">{{ $position->department->name ?? '-' }}</span>
                </td>
                <td>
                    <span class="text-success fw-bold">KES {{ number_format($position->min_salary) }}</span>
                    - 
                    <span class="text-success fw-bold">{{ number_format($position->max_salary) }}</span>
                </td>
                <td>
                    <span class="badge bg-info">{{ $position->employees_count ?? $position->employees->count() }}</span>
                </td>
                <td>
                    <span class="badge bg-{{ $position->is_active ? 'success' : 'secondary' }}">
                        {{ $position->is_active ? 'Active' : 'Inactive' }}
                    </span>
                </td>
                <td>
                    <div class="d-flex gap-1">
                        <a href="{{ route('positions.edit', $position->id) }}" class="btn btn-sm btn-info" title="Edit">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form action="{{ route('positions.destroy', $position->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Delete this position?')" title="Delete">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
