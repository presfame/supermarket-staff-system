<?php

namespace App\Http\Controllers;

use App\Models\LeaveRequest;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LeaveRequestController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        if (in_array($user->role, ['admin', 'hr'])) {
            $requests = LeaveRequest::with(['employee', 'approver'])->orderBy('created_at', 'desc')->get();
        } else {
            $employee = Employee::where('email', $user->email)->first();
            $requests = $employee ? LeaveRequest::where('employee_id', $employee->id)->orderBy('created_at', 'desc')->get() : collect();
        }

        return view('leave_requests.index', compact('requests'));
    }

    public function create()
    {
        $user = Auth::user();

        if (in_array($user->role, ['admin', 'hr'])) {
            $employees = Employee::orderBy('first_name')->get();
            return view('leave_requests.create', ['employees' => $employees]);
        }

        return view('leave_requests.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'reason' => 'required|string|max:2000',
        ]);

        $user = Auth::user();

        if (in_array($user->role, ['admin', 'hr']) && $request->filled('employee_id')) {
            $request->validate(['employee_id' => 'required|exists:employees,id']);
            $validated['employee_id'] = $request->input('employee_id');
        } else {
            $employee = Employee::where('email', $user->email)->first();
            if (!$employee) {
                return back()->withErrors(['email' => 'No employee record found for your account.']);
            }
            $validated['employee_id'] = $employee->id;
        }

        LeaveRequest::create($validated);

        return redirect()->route('leave-requests.index')->with('success', 'Leave request submitted.');
    }

    public function approve($id)
    {
        $user = Auth::user();
        if (!in_array($user->role, ['admin', 'hr'])) {
            abort(403);
        }

        $lr = LeaveRequest::findOrFail($id);
        $lr->update(['status' => 'Approved', 'approved_by' => $user->id]);
        return back()->with('success', 'Leave request approved.');
    }

    public function reject($id, Request $request)
    {
        $user = Auth::user();
        if (!in_array($user->role, ['admin', 'hr'])) {
            abort(403);
        }

        $lr = LeaveRequest::findOrFail($id);
        $lr->update(['status' => 'Rejected', 'approved_by' => $user->id, 'notes' => $request->input('notes')]);
        return back()->with('success', 'Leave request rejected.');
    }
}
