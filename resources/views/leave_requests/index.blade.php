@extends('layouts.app')
@section('title', 'Leave Requests')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Leave Requests</h2>
    <a href="{{ route('leave-requests.create') }}" class="btn btn-primary">New Request</a>
    
</div>

<div class="table-card">
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-striped">
        <thead>
            <tr>
                <th>#</th>
                <th>Employee</th>
                <th>Period</th>
                <th>Reason</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($requests as $req)
            <tr>
                <td>{{ $req->id }}</td>
                <td>{{ $req->employee->full_name ?? '—' }}</td>
                <td>{{ $req->start_date }} → {{ $req->end_date }}</td>
                <td>{{ Str::limit($req->reason, 80) }}</td>
                <td>{{ $req->status }}</td>
                <td>
                    @if(in_array(auth()->user()->role, ['admin','hr']) && $req->status == 'Pending')
                        <form action="{{ route('leave-requests.approve', $req->id) }}" method="POST" style="display:inline-block">
                            @csrf
                            <button class="btn btn-sm btn-success">Approve</button>
                        </form>

                        <form action="{{ route('leave-requests.reject', $req->id) }}" method="POST" style="display:inline-block; margin-left:6px">
                            @csrf
                            <button class="btn btn-sm btn-danger">Reject</button>
                        </form>
                    @else
                        —
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
