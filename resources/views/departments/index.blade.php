@extends('layouts.app')
@section('title', 'Departments')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2>Departments</h2>
        <p class="text-muted mb-0">Manage organizational departments</p>
    </div>
    <a href="{{ route('departments.create') }}" class="btn btn-primary">
        <i class="fas fa-plus me-2"></i>Add Department
    </a>
</div>

<div class="table-card">
    <table class="table datatable">
        <thead>
            <tr>
                <th>Code</th>
                <th>Name</th>
                <th>Description</th>
                <th>Employees</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($departments as $department)
            <tr>
                <td><span class="badge bg-primary">{{ $department->code }}</span></td>
                <td>
                    <div style="font-weight: 600;">{{ $department->name }}</div>
                </td>
                <td class="text-muted">{{ Str::limit($department->description, 50) }}</td>
                <td>
                    <span class="badge bg-info">{{ $department->employees_count ?? $department->employees->count() }} employees</span>
                </td>
                <td>
                    <span class="badge bg-{{ $department->is_active ? 'success' : 'secondary' }}">
                        {{ $department->is_active ? 'Active' : 'Inactive' }}
                    </span>
                </td>
                <td>
                    <div class="d-flex gap-1">
                        <a href="{{ route('departments.edit', $department->id) }}" class="btn btn-sm btn-info" title="Edit">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form action="{{ route('departments.destroy', $department->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Delete this department?')" title="Delete">
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
