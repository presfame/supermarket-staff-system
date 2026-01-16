<?php

namespace App\Http\Controllers;

use App\Models\{Employee, Attendance, Payroll, Department};
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        // Redirect normal employees to their home page
        if ($user->role === 'employee') {
            return view('employee.home');
        }

        // Admin/HR/Supervisor dashboard
        $totalEmployees = Employee::count();
        $activeEmployees = Employee::where('employment_status', 'Active')->where('is_active', true)->count();

        $todayAttendanceCount = Attendance::whereDate('date', Carbon::today())->where('status', 'Present')->count();
        $todayAttendance = $activeEmployees > 0 ? round(($todayAttendanceCount / $activeEmployees) * 100, 1) : 0;

        $monthlyPayroll = Payroll::where('pay_period_month', now()->month)
            ->where('pay_period_year', now()->year)->sum('gross_salary');

        $recentEmployees = Employee::with(['department', 'position'])
            ->where('is_active', true)->orderBy('created_at', 'desc')->take(5)->get();

        $departments = Department::withCount('employees')->where('is_active', true)->get();

        return view('dashboard.index', compact(
            'totalEmployees', 'activeEmployees', 'todayAttendance',
            'monthlyPayroll', 'recentEmployees', 'departments'
        ));
    }
}
