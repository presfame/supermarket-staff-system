<?php

namespace Database\Seeders;

use App\Models\Shift;
use Illuminate\Database\Seeder;

class ShiftSeeder extends Seeder
{
    public function run(): void
    {
        $shifts = [
            [
                'name' => 'Morning Shift',
                'start_time' => '06:00',
                'end_time' => '14:00',
                'hours' => 8,
                'description' => 'Early morning to afternoon shift for opening staff',
            ],
            [
                'name' => 'Day Shift',
                'start_time' => '08:00',
                'end_time' => '17:00',
                'hours' => 8,
                'description' => 'Standard daytime working hours',
            ],
            [
                'name' => 'Afternoon Shift',
                'start_time' => '14:00',
                'end_time' => '22:00',
                'hours' => 8,
                'description' => 'Afternoon to night shift',
            ],
            [
                'name' => 'Night Shift',
                'start_time' => '22:00',
                'end_time' => '06:00',
                'hours' => 8,
                'description' => 'Overnight security and restocking shift',
            ],
        ];

        foreach ($shifts as $shift) {
            Shift::create($shift);
        }
    }
}
