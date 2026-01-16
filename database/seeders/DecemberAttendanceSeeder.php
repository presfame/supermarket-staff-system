<?php

namespace Database\Seeders;

use App\Models\{Attendance, Employee, Shift};
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class DecemberAttendanceSeeder extends Seeder
{
    /**
     * Seed December 2025 attendance (up to current date)
     */
    public function run(): void
    {
        $employees = Employee::all();
        $shifts = Shift::all();
        
        if ($employees->isEmpty() || $shifts->isEmpty()) {
            return;
        }

        // Public holidays in December 2025
        $publicHolidays = ['2025-12-12', '2025-12-25', '2025-12-26'];

        // Seed December 1-19, 2025
        foreach ($employees as $employee) {
            $assignedShift = $shifts->random();

            for ($day = 1; $day <= 19; $day++) {
                $date = Carbon::create(2025, 12, $day);
                $dateStr = $date->format('Y-m-d');

                // Skip weekends
                if ($date->isWeekend()) continue;
                
                // Skip public holidays
                if (in_array($dateStr, $publicHolidays)) continue;

                // Skip if attendance already exists
                if (Attendance::where('employee_id', $employee->id)->where('date', $dateStr)->exists()) {
                    continue;
                }

                $status = $this->determineStatus();
                $clockData = $this->generateClockTimes($assignedShift, $status);

                Attendance::create([
                    'employee_id' => $employee->id,
                    'shift_id' => $assignedShift->id,
                    'date' => $dateStr,
                    'clock_in' => $clockData['clock_in'],
                    'clock_out' => $clockData['clock_out'],
                    'hours_worked' => $clockData['hours_worked'],
                    'overtime_hours' => $clockData['overtime_hours'],
                    'status' => $status,
                    'recorded_by' => 1,
                ]);
            }
        }
    }

    private function determineStatus(): string
    {
        $rand = rand(1, 100);
        if ($rand <= 85) return 'Present';
        if ($rand <= 92) return 'Late';
        if ($rand <= 96) return 'Absent';
        return 'Leave';
    }

    private function generateClockTimes($shift, $status): array
    {
        if (in_array($status, ['Absent', 'Leave'])) {
            return ['clock_in' => null, 'clock_out' => null, 'hours_worked' => 0, 'overtime_hours' => 0];
        }

        $shiftStart = Carbon::parse($shift->start_time);
        $shiftEnd = Carbon::parse($shift->end_time);

        if ($status == 'Late') {
            $clockIn = $shiftStart->copy()->addMinutes(rand(10, 45));
        } else {
            $clockIn = $shiftStart->copy()->subMinutes(rand(0, 15));
        }

        if (rand(1, 10) <= 2) {
            $clockOut = $shiftEnd->copy()->addHours(rand(1, 3));
        } else {
            $clockOut = $shiftEnd->copy()->addMinutes(rand(0, 15));
        }

        $totalMinutes = $clockIn->diffInMinutes($clockOut);
        $hoursWorked = min($totalMinutes / 60, 8);
        $overtimeHours = max(0, ($totalMinutes / 60) - 8);

        return [
            'clock_in' => $clockIn->format('H:i:s'),
            'clock_out' => $clockOut->format('H:i:s'),
            'hours_worked' => round($hoursWorked, 2),
            'overtime_hours' => round($overtimeHours, 2),
        ];
    }
}
