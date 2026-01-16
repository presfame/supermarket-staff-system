<?php

namespace App\Http\Controllers;

use App\Models\Shift;
use App\Models\ShiftSchedule;
use App\Models\Employee;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ShiftController extends Controller
{
    public function index()
    {
        $shifts = Shift::where('is_active', true)->get();
        
        // Get today's schedule summary
        $todaySchedules = ShiftSchedule::where('schedule_date', today())
            ->with('employee', 'shift')
            ->get()
            ->groupBy('shift_id');
            
        return view('shifts.index', compact('shifts', 'todaySchedules'));
    }

    public function create()
    {
        return view('shifts.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:50',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i',
            'hours' => 'required|numeric|min:1|max:12',
            'description' => 'nullable|string|max:255',
        ]);

        Shift::create($validated);

        return redirect()->route('shifts.index')->with('success', 'Shift created successfully!');
    }

    public function show($id)
    {
        $shift = Shift::findOrFail($id);
        return redirect()->route('shifts.schedule', $id);
    }

    public function edit($id)
    {
        $shift = Shift::findOrFail($id);
        return view('shifts.edit', compact('shift'));
    }

    public function update(Request $request, $id)
    {
        $shift = Shift::findOrFail($id);
        
        $validated = $request->validate([
            'name' => 'required|string|max:50',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i',
            'hours' => 'required|numeric|min:1|max:12',
            'description' => 'nullable|string|max:255',
        ]);

        $shift->update($validated);

        return redirect()->route('shifts.index')->with('success', 'Shift updated!');
    }

    // Schedule management
    public function schedule(Request $request, $id)
    {
        $shift = Shift::findOrFail($id);
        $weekStart = $request->get('week') ? Carbon::parse($request->get('week'))->startOfWeek() : now()->startOfWeek();
        $weekEnd = $weekStart->copy()->endOfWeek();
        
        // Get schedule for the week
        $schedules = ShiftSchedule::where('shift_id', $id)
            ->whereBetween('schedule_date', [$weekStart, $weekEnd])
            ->with('employee.department')
            ->get()
            ->groupBy(function($item) {
                return $item->schedule_date->format('Y-m-d');
            });

        // Get available employees (not scheduled for this week in any shift)
        $employees = Employee::where('is_active', true)
            ->with('department')
            ->orderBy('first_name')
            ->get();

        return view('shifts.schedule', compact('shift', 'schedules', 'weekStart', 'weekEnd', 'employees'));
    }

    public function addSchedule(Request $request, $id)
    {
        $request->validate([
            'employee_ids' => 'required|array|min:1',
            'employee_ids.*' => 'exists:employees,id',
            'schedule_date' => 'required|date',
        ]);

        $shift = Shift::findOrFail($id);
        $added = 0;
        $skipped = 0;

        foreach ($request->employee_ids as $employeeId) {
            // Check if already scheduled
            $existing = ShiftSchedule::where('employee_id', $employeeId)
                ->where('schedule_date', $request->schedule_date)
                ->first();

            if ($existing) {
                $skipped++;
                continue;
            }

            ShiftSchedule::create([
                'employee_id' => $employeeId,
                'shift_id' => $id,
                'schedule_date' => $request->schedule_date,
                'status' => 'Scheduled',
                'created_by' => auth()->id(),
            ]);
            $added++;
        }

        if ($added > 0 && $skipped > 0) {
            return back()->with('success', "{$added} employee(s) scheduled. {$skipped} skipped (already scheduled).");
        } elseif ($added > 0) {
            return back()->with('success', "{$added} employee(s) scheduled successfully!");
        } else {
            return back()->with('error', 'All selected employees are already scheduled for this date.');
        }
    }

    public function removeSchedule(Request $request, $id)
    {
        $request->validate([
            'schedule_id' => 'required|exists:shift_schedules,id',
        ]);

        ShiftSchedule::where('id', $request->schedule_id)->delete();

        return back()->with('success', 'Schedule removed!');
    }

    // API endpoint for getting position salary
    public function getPositionSalary($positionId)
    {
        $position = \App\Models\Position::find($positionId);
        if ($position) {
            return response()->json([
                'min_salary' => $position->min_salary,
                'max_salary' => $position->max_salary,
            ]);
        }
        return response()->json(['error' => 'Position not found'], 404);
    }

    // Employee's own schedule view
    public function mySchedule(Request $request)
    {
        $user = auth()->user();
        
        if (!$user->employee_id) {
            return redirect()->route('dashboard')->with('error', 'No employee profile linked to your account.');
        }

        $employee = Employee::find($user->employee_id);
        $month = $request->get('month', now()->month);
        $year = $request->get('year', now()->year);

        $monthSchedules = ShiftSchedule::where('employee_id', $employee->id)
            ->whereMonth('schedule_date', $month)
            ->whereYear('schedule_date', $year)
            ->with('shift')
            ->orderBy('schedule_date')
            ->get();

        $upcomingShifts = ShiftSchedule::where('employee_id', $employee->id)
            ->where('schedule_date', '>=', today())
            ->with('shift')
            ->orderBy('schedule_date')
            ->take(7)
            ->get();

        $todayShift = ShiftSchedule::where('employee_id', $employee->id)
            ->where('schedule_date', today())
            ->with('shift')
            ->first();

        return view('shifts.my-schedule', compact('monthSchedules', 'upcomingShifts', 'todayShift', 'month', 'year'));
    }
}
