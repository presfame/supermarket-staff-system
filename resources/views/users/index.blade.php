@extends('layouts.app')
@section('title','Users')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Users</h2>
    <a href="{{ route('users.create') }}" class="btn btn-primary">New User</a>
</div>

<div class="table-card">
    @if(session('success'))
        <div class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 1200;">
            <div id="flashToast" class="toast align-items-center text-bg-success border-0" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="d-flex">
                    <div class="toast-body">
                        {{ session('success') }}
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
            </div>
        </div>
    @endif
    @php $roles = $users->pluck('role')->unique(); @endphp
    <div class="row mb-3 g-2">
        <div class="col-auto">
            <select id="roleFilter" class="form-select">
                <option value="">All Roles</option>
                @foreach($roles as $role)
                    <option value="{{ $role }}">{{ ucfirst($role) }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-auto">
            <select id="activeFilter" class="form-select">
                <option value="">All</option>
                <option value="Yes">Active</option>
                <option value="No">Inactive</option>
            </select>
        </div>
        <div class="col"></div>
    </div>

    <table id="usersTable" class="table table-striped">
        <thead><tr><th>#</th><th>Name</th><th>Email</th><th>Role</th><th>Employee</th><th>Active</th><th>Actions</th></tr></thead>
        <tbody>
        @foreach($users as $u)
            <tr>
                <td>{{ $u->id }}</td>
                <td>{{ $u->name }}</td>
                <td>{{ $u->email }}</td>
                <td>{{ $u->role }}</td>
                <td>{{ $u->employee->full_name ?? '—' }}</td>
                <td>{{ $u->is_active ? 'Yes' : 'No' }}</td>
                <td>
                    <a href="{{ route('users.edit', $u->id) }}" class="btn btn-sm btn-secondary">Edit</a>
                    <form action="{{ route('users.destroy', $u->id) }}" method="POST" style="display:inline-block">
                        @csrf @method('DELETE')
                        <button class="btn btn-sm btn-danger" onclick="return confirm('Delete user?')">Delete</button>
                    </form>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
@section('scripts')
@if(session('success'))
<script>
document.addEventListener('DOMContentLoaded', function() {
    var toastEl = document.getElementById('flashToast');
    if (toastEl) {
        var bsToast = new bootstrap.Toast(toastEl, { delay: 4000 });
        bsToast.show();
    }
});
</script>
@endif
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize DataTable
    if (typeof $ !== 'undefined' && $.fn.dataTable) {
        var table = $('#usersTable').DataTable({
            pageLength: 25,
            responsive: true,
        });

        // Role filter
        var roleSelect = document.getElementById('roleFilter');
        if (roleSelect) {
            roleSelect.addEventListener('change', function() {
                var val = this.value || '';
                table.column(3).search(val).draw();
            });
        }

        // Active filter (Yes/No)
        var activeSelect = document.getElementById('activeFilter');
        if (activeSelect) {
            activeSelect.addEventListener('change', function() {
                var val = this.value || '';
                table.column(5).search(val).draw();
            });
        }
    }
});
</script>
@endsection

@endsection
