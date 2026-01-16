<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            DepartmentSeeder::class,
            PositionSeeder::class,
            ShiftSeeder::class,
            StatutorySettingsSeeder::class,
            AdminUserSeeder::class,
            EmployeeSeeder::class,
            EmployeeUserSeeder::class,      // Creates user accounts for employees
            AttendanceSeeder::class,        // Jan-Nov 2025
            DecemberAttendanceSeeder::class, // December 2025
            JanuaryAttendanceSeeder::class,  // January 2026
            ShiftScheduleSeeder::class,
        ]);
    }
}
