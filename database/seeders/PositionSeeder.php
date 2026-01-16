<?php

namespace Database\Seeders;

use App\Models\Position;
use App\Models\Department;
use Illuminate\Database\Seeder;

class PositionSeeder extends Seeder
{
    public function run(): void
    {
        $positions = [
            // Sales Department
            ['title' => 'Cashier', 'department' => 'SAL', 'min_salary' => 18000, 'max_salary' => 25000],
            ['title' => 'Sales Associate', 'department' => 'SAL', 'min_salary' => 16000, 'max_salary' => 22000],
            ['title' => 'Customer Service Rep', 'department' => 'SAL', 'min_salary' => 18000, 'max_salary' => 24000],
            ['title' => 'Sales Supervisor', 'department' => 'SAL', 'min_salary' => 30000, 'max_salary' => 45000],
            
            // Stock/Inventory Department
            ['title' => 'Stock Clerk', 'department' => 'STK', 'min_salary' => 15000, 'max_salary' => 20000],
            ['title' => 'Inventory Controller', 'department' => 'STK', 'min_salary' => 25000, 'max_salary' => 35000],
            ['title' => 'Warehouse Supervisor', 'department' => 'STK', 'min_salary' => 35000, 'max_salary' => 50000],
            
            // Security Department
            ['title' => 'Security Guard', 'department' => 'SEC', 'min_salary' => 15000, 'max_salary' => 22000],
            ['title' => 'Security Supervisor', 'department' => 'SEC', 'min_salary' => 28000, 'max_salary' => 40000],
            
            // Cleaning Department
            ['title' => 'Cleaner', 'department' => 'CLN', 'min_salary' => 12000, 'max_salary' => 18000],
            ['title' => 'Cleaning Supervisor', 'department' => 'CLN', 'min_salary' => 20000, 'max_salary' => 28000],
            
            // Administration Department
            ['title' => 'HR Officer', 'department' => 'ADM', 'min_salary' => 40000, 'max_salary' => 60000],
            ['title' => 'Accountant', 'department' => 'ADM', 'min_salary' => 45000, 'max_salary' => 70000],
            ['title' => 'Office Administrator', 'department' => 'ADM', 'min_salary' => 30000, 'max_salary' => 45000],
            ['title' => 'Store Manager', 'department' => 'ADM', 'min_salary' => 80000, 'max_salary' => 120000],
        ];

        foreach ($positions as $pos) {
            $department = Department::where('code', $pos['department'])->first();
            if ($department) {
                Position::create([
                    'title' => $pos['title'],
                    'department_id' => $department->id,
                    'min_salary' => $pos['min_salary'],
                    'max_salary' => $pos['max_salary'],
                    'is_active' => true,
                ]);
            }
        }
    }
}
