<?php

namespace Database\Seeders;

use App\Models\{Employee, Position};
use Illuminate\Database\Seeder;

class EmployeeSeeder extends Seeder
{
    public function run(): void
    {
        $employees = [
            // Sales Department (8)
            ['first_name' => 'Ann', 'last_name' => 'Chebet', 'gender' => 'Female', 'position' => 'Cashier'],
            ['first_name' => 'Lucy', 'last_name' => 'Wairimu', 'gender' => 'Female', 'position' => 'Cashier'],
            ['first_name' => 'Grace', 'last_name' => 'Wanjiku', 'gender' => 'Female', 'position' => 'Sales Associate'],
            ['first_name' => 'Michael', 'last_name' => 'Otieno', 'gender' => 'Male', 'position' => 'Sales Supervisor'],
            ['first_name' => 'Mary', 'last_name' => 'Akinyi', 'gender' => 'Female', 'position' => 'Customer Service Rep'],
            ['first_name' => 'Susan', 'last_name' => 'Wambui', 'gender' => 'Female', 'position' => 'Cashier'],
            ['first_name' => 'Jane', 'last_name' => 'Nyambura', 'gender' => 'Female', 'position' => 'Sales Associate'],
            ['first_name' => 'Kevin', 'last_name' => 'Omondi', 'gender' => 'Male', 'position' => 'Customer Service Rep'],
            
            // Stock/Inventory Department (7)
            ['first_name' => 'John', 'last_name' => 'Mwangi', 'gender' => 'Male', 'position' => 'Stock Clerk'],
            ['first_name' => 'David', 'last_name' => 'Kiprop', 'gender' => 'Male', 'position' => 'Inventory Controller'],
            ['first_name' => 'James', 'last_name' => 'Kamau', 'gender' => 'Male', 'position' => 'Warehouse Supervisor'],
            ['first_name' => 'Brian', 'last_name' => 'Kiptoo', 'gender' => 'Male', 'position' => 'Stock Clerk'],
            ['first_name' => 'Dennis', 'last_name' => 'Korir', 'gender' => 'Male', 'position' => 'Stock Clerk'],
            ['first_name' => 'Samuel', 'last_name' => 'Wafula', 'gender' => 'Male', 'position' => 'Inventory Controller'],
            ['first_name' => 'Patrick', 'last_name' => 'Mutua', 'gender' => 'Male', 'position' => 'Stock Clerk'],
            
            // Administration (5)
            ['first_name' => 'Sarah', 'last_name' => 'Muthoni', 'gender' => 'Female', 'position' => 'HR Officer'],
            ['first_name' => 'Joseph', 'last_name' => 'Kibet', 'gender' => 'Male', 'position' => 'Accountant'],
            ['first_name' => 'Esther', 'last_name' => 'Njoroge', 'gender' => 'Female', 'position' => 'HR Officer'],
            ['first_name' => 'Daniel', 'last_name' => 'Kimani', 'gender' => 'Male', 'position' => 'Accountant'],
            ['first_name' => 'Ruth', 'last_name' => 'Wangari', 'gender' => 'Female', 'position' => 'HR Officer'],
            
            // Security Department (4)
            ['first_name' => 'Peter', 'last_name' => 'Ochieng', 'gender' => 'Male', 'position' => 'Security Guard'],
            ['first_name' => 'George', 'last_name' => 'Nyaga', 'gender' => 'Male', 'position' => 'Security Guard'],
            ['first_name' => 'Stephen', 'last_name' => 'Macharia', 'gender' => 'Male', 'position' => 'Security Guard'],
            ['first_name' => 'Charles', 'last_name' => 'Oduor', 'gender' => 'Male', 'position' => 'Security Guard'],
            
            // Cleaning Department (4)
            ['first_name' => 'Faith', 'last_name' => 'Njeri', 'gender' => 'Female', 'position' => 'Cleaner'],
            ['first_name' => 'Elizabeth', 'last_name' => 'Mwende', 'gender' => 'Female', 'position' => 'Cleaner'],
            ['first_name' => 'Agnes', 'last_name' => 'Adhiambo', 'gender' => 'Female', 'position' => 'Cleaner'],
            ['first_name' => 'Joyce', 'last_name' => 'Kemunto', 'gender' => 'Female', 'position' => 'Cleaner'],
            
            // IT Department (2)
            ['first_name' => 'Victor', 'last_name' => 'Onyango', 'gender' => 'Male', 'position' => 'IT Support'],
            ['first_name' => 'Martin', 'last_name' => 'Wekesa', 'gender' => 'Male', 'position' => 'IT Support'],
        ];

        $year = date('Y');
        $counter = 1;

        foreach ($employees as $data) {
            $position = Position::where('title', $data['position'])->first();
            if (!$position) continue;

            $employeeNumber = 'MAN' . $year . str_pad($counter, 4, '0', STR_PAD_LEFT);
            $idNumber = '3' . rand(1000000, 9999999) . $counter;

            Employee::create([
                'employee_number' => $employeeNumber,
                'first_name' => $data['first_name'],
                'last_name' => $data['last_name'],
                'id_number' => $idNumber,
                'phone' => '07' . rand(10000000, 99999999),
                'email' => strtolower($data['first_name']) . '.' . strtolower($data['last_name']) . '@manimo.co.ke',
                'date_of_birth' => now()->subYears(rand(22, 45))->subDays(rand(1, 365)),
                'gender' => $data['gender'],
                'address' => 'Nairobi, Kenya',
                'department_id' => $position->department_id,
                'position_id' => $position->id,
                'date_of_hire' => now()->subMonths(rand(3, 36)),
                'employment_status' => 'Active',
                'employment_type' => 'Full-time',
                'pay_type' => 'Monthly',
                'basic_salary' => $position->min_salary + rand(0, ($position->max_salary - $position->min_salary)),
                'bank_name' => ['Equity Bank', 'KCB', 'Co-operative Bank', 'NCBA'][rand(0, 3)],
                'bank_account' => rand(1000000000, 9999999999),
                'bank_branch' => 'Nairobi Branch',
                'kra_pin' => 'A' . rand(100000000, 999999999) . chr(rand(65, 90)),
                'nssf_number' => rand(10000000, 99999999),
                'nhif_number' => rand(1000000, 9999999),
                'is_active' => true,
            ]);

            $counter++;
        }
    }
}
