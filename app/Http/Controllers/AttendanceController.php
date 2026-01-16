<?php

namespace App\Http\Controllers;

use App\Models\{Attendance, Employee};
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AttendanceController extends Controller
{
    public function index(Request $request)
    {
        $filterType = $request->get('filter_type', 'date');
        $date = $request->get('date', now()->format('Y-m-d'));
        $month = $request->get('month', now()->month);
        $year = $request->get('year', now()->year);

        $query = Attendance::with(['employee.department', 'shift']);

        if ($filterType === 'month') {
            $query->whereMonth('date', $month)->whereYear('date', $year);
        } else {
            $query->whereDate('date', $date);
        }

        $attendances = $query->orderBy('date', 'desc')->orderBy('created_at', 'desc')->get();

        // Calculate summary
        $summary = [
            'present' => $attendances->where('status', 'Present')->count(),
            'late' => $attendances->where('status', 'Late')->count(),
            'absent' => $attendances->where('status', 'Absent')->count(),
            'leave' => $attendances->where('status', 'Leave')->count(),
        ];

        return view('attendance.index', compact('attendances', 'summary', 'filterType', 'date', 'month', 'year'));
    }

    public function create()
    {
        $employees = Employee::where('is_active', true)
            ->where('employment_status', 'Active')
            ->with('department')
            ->orderBy('first_name')
            ->get();
        $shifts = \App\Models\Shift::where('is_active', true)->get();
        return view('attendance.create', compact('employees', 'shifts'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'shift_id' => 'nullable|exists:shifts,id',
            'date' => 'required|date',
            'clock_in' => 'nullable|date_format:H:i',
            'clock_out' => 'nullable|date_format:H:i|after:clock_in',
            'status' => 'required|in:Present,Absent,Late,Half-day,Leave',
            'remarks' => 'nullable|string|max:255',
        ]);

        // Check for existing record
        $existing = Attendance::where('employee_id', $request->employee_id)
            ->whereDate('date', $request->date)
            ->first();

        if ($existing) {
            return back()->with('error', 'Attendance already recorded for this employee on this date!');
        }

        if ($request->clock_out && $request->clock_in) {
            $hours = $this->calculateHours($request->clock_in, $request->clock_out);
            $validated['hours_worked'] = $hours['hours_worked'];
            $validated['overtime_hours'] = $hours['overtime_hours'];
        }

        $validated['recorded_by'] = Auth::id();
        Attendance::create($validated);

        return redirect()->route('attendance.index')->with('success', 'Attendance recorded successfully!');
    }

    public function edit($id)
    {
        $attendance = Attendance::with('employee')->findOrFail($id);
        $employees = Employee::where('is_active', true)->get();
        return view('attendance.edit', compact('attendance', 'employees'));
    }

    public function update(Request $request, $id)
    {
        $attendance = Attendance::findOrFail($id);
        
        $validated = $request->validate([
            'clock_in' => 'nullable|date_format:H:i',
            'clock_out' => 'nullable|date_format:H:i',
            'status' => 'required|in:Present,Absent,Late,Half-day,Leave',
            'remarks' => 'nullable|string|max:255',
        ]);

        if ($request->clock_out && $request->clock_in) {
            $hours = $this->calculateHours($request->clock_in, $request->clock_out);
            $validated['hours_worked'] = $hours['hours_worked'];
            $validated['overtime_hours'] = $hours['overtime_hours'];
        }

        $attendance->update($validated);

        return redirect()->route('attendance.index')->with('success', 'Attendance updated!');
    }

    // Employee self clock-in
    public function clockIn(Request $request)
    {
        $user = auth()->user();
        if (!$user->employee_id) {
            return back()->with('error', 'No employee profile linked to your account.');
        }

        $today = now()->format('Y-m-d');
        $existing = Attendance::where('employee_id', $user->employee_id)
            ->whereDate('date', $today)
            ->first();

        if ($existing) {
            return back()->with('error', 'You have already clocked in today.');
        }

        $clockInTime = now()->format('H:i');
        $status = Carbon::parse($clockInTime)->gt(Carbon::parse('08:30')) ? 'Late' : 'Present';

        Attendance::create([
            'employee_id' => $user->employee_id,
            'date' => $today,
            'clock_in' => $clockInTime,
            'status' => $status,
            'recorded_by' => $user->id,
        ]);

        return back()->with('success', 'Clocked in at ' . $clockInTime);
    }

    // Employee self clock-out
    public function clockOut(Request $request)
    {
        $user = auth()->user();
        if (!$user->employee_id) {
            return back()->with('error', 'No employee profile linked.');
        }

        $today = now()->format('Y-m-d');
        $attendance = Attendance::where('employee_id', $user->employee_id)
            ->whereDate('date', $today)
            ->first();

        if (!$attendance) {
            return back()->with('error', 'No clock-in record found for today.');
        }

        if ($attendance->clock_out) {
            return back()->with('error', 'You have already clocked out.');
        }

        $clockOutTime = now()->format('H:i');
        $hours = $this->calculateHours($attendance->clock_in, $clockOutTime);

        $attendance->update([
            'clock_out' => $clockOutTime,
            'hours_worked' => $hours['hours_worked'],
            'overtime_hours' => $hours['overtime_hours'],
        ]);

        return back()->with('success', 'Clocked out at ' . $clockOutTime . '. Worked ' . $hours['hours_worked'] . ' hours.');
    }

    private function calculateHours($clockIn, $clockOut)
    {
        $start = Carbon::parse($clockIn);
        $end = Carbon::parse($clockOut);
        $totalHours = $end->diffInMinutes($start) / 60;

        return [
            'hours_worked' => round(min($totalHours, 8), 2),
            'overtime_hours' => round(max(0, $totalHours - 8), 2)
        ];
    }
}
