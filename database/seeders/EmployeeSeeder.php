<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EmployeeSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('employees')->insert([
            [
                'store_id' => 1,
                'employee_code' => 'E001',
                'name' => 'Rebaz Ali',
                'position' => 'OWNER',
                'email' => 'rebaz@marketakam.local',
                'phone' => '07700000001',
                'address' => 'Erbil',
                'salary' => 1500000,
                'hire_date' => '2024-01-15',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'store_id' => 1,
                'employee_code' => 'E002',
                'name' => 'Sara Noor',
                'position' => 'MANAGER',
                'email' => 'sara@marketakam.local',
                'phone' => '07700000002',
                'address' => 'Sulaymaniyah',
                'salary' => 1200000,
                'hire_date' => '2024-02-01',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'store_id' => 1,
                'employee_code' => 'E003',
                'name' => 'Dana Yousif',
                'position' => 'CASHIER',
                'email' => 'dana@marketakam.local',
                'phone' => '07700000003',
                'address' => 'Duhok',
                'salary' => 850000,
                'hire_date' => '2024-03-10',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
