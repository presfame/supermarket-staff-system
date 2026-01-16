<?php

namespace App\Http\Controllers;

use App\Models\{Employee, Attendance, Payroll, Department, Shift};
use Carbon\Carbon;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index()
    {
        return view('reports.index');
    }

    public function attendance(Request $request)
    {
        $month = $request->get('month', now()->month);
        $year = $request->get('year', now()->year);
        $departmentId = $request->get('department');

        $query = Employee::where('is_active', true)
            ->with(['department', 'attendances' => function($q) use ($month, $year) {
                $q->whereMonth('date', $month)->whereYear('date', $year);
            }]);

        if ($departmentId) {
            $query->where('department_id', $departmentId);
        }

        $employees = $query->get()->map(function($emp) {
            $attendances = $emp->attendances;
            return [
                'employee' => $emp,
                'present' => $attendances->whereIn('status', ['Present', 'Late'])->count(),
                'late' => $attendances->where('status', 'Late')->count(),
                'absent' => $attendances->where('status', 'Absent')->count(),
                'leave' => $attendances->where('status', 'Leave')->count(),
                'overtime' => $attendances->sum('overtime_hours'),
            ];
        });

        $departments = Department::where('is_active', true)->get();

        // Summary stats
        $summary = [
            'total_present' => $employees->sum('present'),
            'total_late' => $employees->sum('late'),
            'total_absent' => $employees->sum('absent'),
            'total_leave' => $employees->sum('leave'),
            'total_overtime' => $employees->sum('overtime'),
        ];

        return view('reports.attendance', compact('employees', 'departments', 'month', 'year', 'departmentId', 'summary'));
    }

    public function payroll(Request $request)
    {
        $month = $request->get('month', now()->month);
        $year = $request->get('year', now()->year);

        $payrolls = Payroll::with('employee.department')
            ->where('pay_period_month', $month)
            ->where('pay_period_year', $year)
            ->get();

        $summary = [
            'total_gross' => $payrolls->sum('gross_salary'),
            'total_nssf' => $payrolls->sum('nssf_deduction'),
            'total_shif' => $payrolls->sum('shif_deduction'),
            'total_paye' => $payrolls->sum('paye_deduction'),
            'total_housing' => $payrolls->sum('housing_levy'),
            'total_deductions' => $payrolls->sum('total_deductions'),
            'total_net' => $payrolls->sum('net_salary'),
            'employees_count' => $payrolls->count(),
        ];

        // Department breakdown
        $departmentBreakdown = $payrolls->groupBy('employee.department.name')->map(function($items, $dept) {
            return [
                'department' => $dept ?: 'Unknown',
                'count' => $items->count(),
                'gross' => $items->sum('gross_salary'),
                'net' => $items->sum('net_salary'),
            ];
        })->values();

        return view('reports.payroll', compact('payrolls', 'month', 'year', 'summary', 'departmentBreakdown'));
    }

    public function performance(Request $request)
    {
        $month = $request->get('month', now()->month);
        $year = $request->get('year', now()->year);

        $employees = Employee::where('is_active', true)
            ->with(['department', 'position', 'attendances' => function($q) use ($month, $year) {
                $q->whereMonth('date', $month)->whereYear('date', $year);
            }])
            ->get()
            ->map(function($emp) {
                $attendances = $emp->attendances;
                $totalRecords = $attendances->count();
                $present = $attendances->whereIn('status', ['Present', 'Late'])->count();
                $late = $attendances->where('status', 'Late')->count();
                
                // Calculate attendance rate
                $attendanceRate = $totalRecords > 0 ? round(($present / $totalRecords) * 100, 1) : 0;
                
                // Calculate punctuality (present but not late)
                $onTime = $present - $late;
                $punctualityRate = $present > 0 ? round(($onTime / $present) * 100, 1) : 0;

                return [
                    'employee' => $emp,
                    'total_days' => $totalRecords,
                    'present' => $present,
                    'late' => $late,
                    'absent' => $attendances->where('status', 'Absent')->count(),
                    'overtime_hours' => $attendances->sum('overtime_hours'),
                    'attendance_rate' => $attendanceRate,
                    'punctuality_rate' => $punctualityRate,
                ];
            })
            ->sortByDesc('attendance_rate');

        return view('reports.performance', compact('employees', 'month', 'year'));
    }
}
