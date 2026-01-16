<?php

namespace Database\Seeders;

use App\Models\{Employee, Shift, ShiftSchedule};
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class ShiftScheduleSeeder extends Seeder
{
    public function run(): void
    {
        $employees = Employee::all();
        $shifts = Shift::all();
        
        if ($employees->isEmpty() || $shifts->isEmpty()) {
            return;
        }

        // Schedule for current week and next 2 weeks
        $startDate = Carbon::today()->startOfWeek();
        $endDate = $startDate->copy()->addWeeks(3);

        $currentDate = $startDate->copy();
        
        while ($currentDate < $endDate) {
            // Skip weekends (not scheduling for now, but you could include night shift)
            if (!$currentDate->isWeekend()) {
                foreach ($employees as $index => $employee) {
                    // Assign shifts based on employee index (rotate)
                    // This creates a somewhat random but consistent assignment
                    $shiftIndex = ($index + $currentDate->dayOfWeek) % $shifts->count();
                    $assignedShift = $shifts[$shiftIndex];

                    ShiftSchedule::create([
                        'employee_id' => $employee->id,
                        'shift_id' => $assignedShift->id,
                        'schedule_date' => $currentDate->format('Y-m-d'),
                        'status' => $currentDate->isPast() ? 'Completed' : 'Scheduled',
                        'created_by' => 1,
                    ]);
                }
            }
            
            $currentDate->addDay();
        }
    }
}
