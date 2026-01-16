<?php

namespace App\Services;

use App\Models\{Employee, Payroll, Attendance, StatutorySetting};
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PayrollCalculationService
{
    private $statutorySettings;

    public function __construct()
    {
        $this->statutorySettings = StatutorySetting::where('is_active', true)
            ->pluck('setting_value', 'setting_name')->toArray();
    }

    public function processPayroll($month, $year)
    {
        // Check if payroll already processed for this month
        $existingCount = Payroll::where('pay_period_month', $month)
            ->where('pay_period_year', $year)
            ->count();
        
        if ($existingCount > 0) {
            return [
                'success' => false, 
                'message' => "Payroll for " . date('F Y', mktime(0, 0, 0, $month, 1, $year)) . " has already been processed for {$existingCount} employees. Cannot process again."
            ];
        }

        DB::beginTransaction();
        try {
            $employees = Employee::where('is_active', true)
                ->where('employment_status', 'Active')->get();

            $results = [];
            foreach ($employees as $employee) {
                $payroll = $this->processEmployeePayroll($employee, $month, $year);
                $results[] = ['employee' => $employee->full_name, 'net' => $payroll->net_salary];
            }

            DB::commit();
            return ['success' => true, 'processed' => count($results), 'results' => $results];
        } catch (\Exception $e) {
            DB::rollBack();
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    public function processEmployeePayroll(Employee $employee, $month, $year)
    {
        // Calculate based on DAYS WORKED
        $attendanceData = $this->calculateDaysWorked($employee, $month, $year);
        $grossData = $this->calculateGrossSalary($employee, $attendanceData, $month, $year);
        
        // Complex statutory deductions
        $nssf = $this->calculateNSSF($grossData['gross_salary']);
        $shif = $this->calculateSHIF($grossData['gross_salary']);
        $taxableIncome = $grossData['gross_salary'] - $nssf - ($this->statutorySettings['housing_levy_rate'] ?? 1.5) / 100 * $grossData['gross_salary'];
        $paye = $this->calculatePAYE($taxableIncome, $nssf);
        $housingLevy = $this->calculateHousingLevy($grossData['gross_salary']);

        $totalDeductions = $nssf + $shif + $paye + $housingLevy;
        $netSalary = $grossData['gross_salary'] - $totalDeductions;

        return Payroll::create([
            'employee_id' => $employee->id,
            'pay_period_month' => $month,
            'pay_period_year' => $year,
            'basic_salary' => $employee->basic_salary,
            'days_worked' => $attendanceData['days_worked'],
            'days_absent' => $attendanceData['days_absent'],
            'days_leave' => $attendanceData['days_leave'],
            'overtime_hours' => $attendanceData['overtime_hours'],
            'overtime_pay' => $grossData['overtime_pay'],
            'gross_salary' => $grossData['gross_salary'],
            'nssf_deduction' => $nssf,
            'shif_deduction' => $shif,
            'paye_deduction' => $paye,
            'housing_levy' => $housingLevy,
            'other_deductions' => 0,
            'total_deductions' => $totalDeductions,
            'net_salary' => $netSalary,
            'status' => 'Processed',
            'processed_by' => auth()->id(),
        ]);
    }

    private function calculateDaysWorked(Employee $employee, $month, $year)
    {
        $attendance = Attendance::where('employee_id', $employee->id)
            ->whereMonth('date', $month)
            ->whereYear('date', $year)
            ->get();

        $daysWorked = $attendance->whereIn('status', ['Present', 'Late'])->count();
        $daysAbsent = $attendance->where('status', 'Absent')->count();
        $daysLeave = $attendance->where('status', 'Leave')->count();
        $overtimeHours = $attendance->sum('overtime_hours');

        // Calculate working days in month (excluding weekends)
        $workingDays = $this->getWorkingDaysInMonth($month, $year);

        return [
            'days_worked' => $daysWorked,
            'days_absent' => $daysAbsent,
            'days_leave' => $daysLeave,
            'working_days' => $workingDays,
            'overtime_hours' => $overtimeHours,
        ];
    }

    private function getWorkingDaysInMonth($month, $year)
    {
        $date = Carbon::create($year, $month, 1);
        $daysInMonth = $date->daysInMonth;
        $workingDays = 0;

        for ($day = 1; $day <= $daysInMonth; $day++) {
            $currentDate = Carbon::create($year, $month, $day);
            if (!$currentDate->isWeekend()) {
                $workingDays++;
            }
        }

        return $workingDays;
    }

    private function calculateGrossSalary(Employee $employee, $attendanceData, $month, $year)
    {
        $basicSalary = $employee->basic_salary;
        $workingDays = $attendanceData['working_days'];
        $daysWorked = $attendanceData['days_worked'] + $attendanceData['days_leave']; // Leave is paid
        
        // Pro-rate salary based on days worked (if not full month)
        if ($workingDays > 0) {
            $dailyRate = $basicSalary / $workingDays;
            $proRatedSalary = $dailyRate * $daysWorked;
        } else {
            $proRatedSalary = $basicSalary;
        }

        // Calculate overtime pay (1.5x hourly rate)
        $hourlyRate = $basicSalary / ($workingDays * 8); // Assuming 8-hour workday
        $overtimePay = $attendanceData['overtime_hours'] * $hourlyRate * 1.5;

        $grossSalary = $proRatedSalary + $overtimePay;

        return [
            'gross_salary' => round($grossSalary, 2),
            'overtime_pay' => round($overtimePay, 2),
            'daily_rate' => round($dailyRate ?? 0, 2),
        ];
    }

    private function calculateNSSF($grossSalary)
    {
        // NSSF Kenya Tier I & II calculation
        $tier1Limit = $this->statutorySettings['nssf_tier1_max'] ?? 7000;
        $tier2Limit = $this->statutorySettings['nssf_tier2_threshold'] ?? 36000;
        $rate = ($this->statutorySettings['nssf_rate'] ?? 6.0) / 100;
        $maxContribution = $this->statutorySettings['nssf_tier2_max'] ?? 2160;

        // Tier I: 6% of first KES 7,000 = max KES 420
        $tier1 = min($grossSalary, $tier1Limit) * $rate;
        
        // Tier II: 6% of (gross - 7,000) up to KES 36,000
        $tier2 = 0;
        if ($grossSalary > $tier1Limit) {
            $tier2Amount = min($grossSalary - $tier1Limit, $tier2Limit - $tier1Limit);
            $tier2 = $tier2Amount * $rate;
        }

        $total = $tier1 + $tier2;
        return round(min($total, $maxContribution), 2);
    }

    private function calculateSHIF($grossSalary)
    {
        // SHIF: 2.75% of gross salary
        $rate = ($this->statutorySettings['shif_rate'] ?? 2.75) / 100;
        return round($grossSalary * $rate, 2);
    }

    private function calculateHousingLevy($grossSalary)
    {
        // Housing Levy: 1.5% of gross salary
        $rate = ($this->statutorySettings['housing_levy_rate'] ?? 1.5) / 100;
        return round($grossSalary * $rate, 2);
    }

    private function calculatePAYE($taxableIncome, $nssf)
    {
        // Kenya PAYE Tax Bands (Monthly) - 2024
        $bands = [
            ['limit' => 24000, 'rate' => 10],    // First KES 24,000 at 10%
            ['limit' => 32333, 'rate' => 25],    // Next KES 8,333 at 25%
            ['limit' => 500000, 'rate' => 30],   // Next at 30%
            ['limit' => 800000, 'rate' => 32.5], // Next at 32.5%
            ['limit' => PHP_INT_MAX, 'rate' => 35], // Above at 35%
        ];

        $personalRelief = $this->statutorySettings['personal_relief'] ?? 2400;
        $insuranceRelief = min(($this->statutorySettings['shif_rate'] ?? 2.75) / 100 * $taxableIncome * 0.15, 5000);

        $tax = 0;
        $remaining = $taxableIncome;
        $previousLimit = 0;

        foreach ($bands as $band) {
            if ($remaining <= 0) break;
            
            $bandWidth = $band['limit'] - $previousLimit;
            $taxableInBand = min($remaining, $bandWidth);
            $tax += ($taxableInBand * $band['rate']) / 100;
            $remaining -= $taxableInBand;
            $previousLimit = $band['limit'];
        }

        // Apply reliefs
        $netTax = $tax - $personalRelief - $insuranceRelief;

        return round(max(0, $netTax), 2);
    }
}
