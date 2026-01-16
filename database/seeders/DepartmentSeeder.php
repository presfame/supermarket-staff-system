<?php

namespace Database\Seeders;

use App\Models\Department;
use Illuminate\Database\Seeder;

class DepartmentSeeder extends Seeder
{
    public function run(): void
    {
        $departments = [
            ['name' => 'Sales', 'code' => 'SAL', 'description' => 'Sales and customer service'],
            ['name' => 'Stock/Inventory', 'code' => 'STK', 'description' => 'Stock management'],
            ['name' => 'Security', 'code' => 'SEC', 'description' => 'Security services'],
            ['name' => 'Cleaning', 'code' => 'CLN', 'description' => 'Cleaning and maintenance'],
            ['name' => 'Administration', 'code' => 'ADM', 'description' => 'Administrative functions'],
        ];

        foreach ($departments as $dept) {
            Department::create($dept);
        }
    }
}
