<?php

namespace Database\Seeders;

use App\Models\{Employee, User};
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class EmployeeUserSeeder extends Seeder
{
    /**
     * Create user accounts for all employees
     * Password: password
     */
    public function run(): void
    {
        $employees = Employee::all();
        
        foreach ($employees as $employee) {
            // Skip if user already exists for this employee
            if (User::where('employee_id', $employee->id)->exists()) {
                continue;
            }

            // Skip if email already used
            if (User::where('email', $employee->email)->exists()) {
                continue;
            }

            User::create([
                'name' => $employee->full_name,
                'email' => $employee->email,
                'password' => Hash::make('password'),
                'role' => 'employee',
                'employee_id' => $employee->id,
            ]);
        }
    }
}
