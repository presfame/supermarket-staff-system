@extends('layouts.app')
@section('title', 'Employees')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2>Employees</h2>
        <p class="text-muted mb-0">Manage all staff members</p>
    </div>
    <a href="{{ route('employees.create') }}" class="btn btn-primary">
        <i class="fas fa-plus me-2"></i>Add Employee
    </a>
</div>

<div class="table-card">
    <table class="table datatable">
        <thead>
            <tr>
                <th>Employee</th>
                <th>ID Number</th>
                <th>Department</th>
                <th>Position</th>
                <th>Hire Date</th>
                <th>Salary</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($employees as $employee)
            <tr>
                <td>
                    <div class="d-flex align-items-center gap-2">
                        <div style="width: 40px; height: 40px; background: var(--primary); border-radius: 10px; display: flex; align-items: center; justify-content: center; color: white; font-weight: 600; font-size: 13px;">
                            {{ strtoupper(substr($employee->first_name, 0, 1) . substr($employee->last_name, 0, 1)) }}
                        </div>
                        <div>
                            <div style="font-weight: 600;">{{ $employee->full_name }}</div>
                            <small class="text-muted">{{ $employee->employee_number }}</small>
                        </div>
                    </div>
                </td>
                <td>{{ $employee->id_number }}</td>
                <td>{{ $employee->department->name ?? '-' }}</td>
                <td>{{ $employee->position->title ?? '-' }}</td>
                <td>
                    <span class="date-display">
                        <i class="fas fa-calendar"></i>
                        {{ $employee->date_of_hire ? $employee->date_of_hire->format('d M Y') : '-' }}
                    </span>
                </td>
                <td>KES {{ number_format($employee->basic_salary) }}</td>
                <td>
                    @php
                        $statusColors = ['Active' => 'success', 'Inactive' => 'secondary', 'Suspended' => 'danger'];
                    @endphp
                    <span class="badge bg-{{ $statusColors[$employee->employment_status] ?? 'secondary' }}">
                        {{ $employee->employment_status }}
                    </span>
                </td>
                <td>
                    <div class="d-flex gap-1">
                        <a href="{{ route('employees.show', $employee->id) }}" class="btn btn-sm btn-secondary" title="View">
                            <i class="fas fa-eye"></i>
                        </a>
                        <a href="{{ route('employees.edit', $employee->id) }}" class="btn btn-sm btn-info" title="Edit">
                            <i class="fas fa-edit"></i>
                        </a>
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
