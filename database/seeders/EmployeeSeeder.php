<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EmployeeSeeder extends Seeder
{
    public function run(): void
    {
        // Get user IDs for the employees
        $rebazId = DB::table('users')->where('username', 'rebaz')->value('id');
        $saraId = DB::table('users')->where('username', 'sara')->value('id');
        $danaId = DB::table('users')->where('username', 'dana')->value('id');

        // If users don't exist, create them
        if (!$rebazId) {
            $rebazId = DB::table('users')->insertGetId([
                'username' => 'rebaz',
                'email' => 'rebaz@marketakam.local',
                'password' => bcrypt('Ayad1234'),
                'role' => 'owner',
                'current_store_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
        if (!$saraId) {
            $saraId = DB::table('users')->insertGetId([
                'username' => 'sara',
                'email' => 'sara@marketakam.local',
                'password' => bcrypt('Ayad1234'),
                'role' => 'manager',
                'current_store_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
        if (!$danaId) {
            $danaId = DB::table('users')->insertGetId([
                'username' => 'dana',
                'email' => 'dana@marketakam.local',
                'password' => bcrypt('Ayad1234'),
                'role' => 'cashier',
                'current_store_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Get branch ID (assuming branch with code 'MAIN' exists)
        $branchId = DB::table('branches')->where('code', 'MAIN')->value('id');
        if (!$branchId) {
            $branchId = DB::table('branches')->insertGetId([
                'name' => 'Main Branch',
                'code' => 'MAIN',
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        DB::table('employees')->insert([
            [
                'user_id' => $rebazId,
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
                'user_id' => $saraId,
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
                'user_id' => $danaId,
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

        // Assign employees to branch
        foreach ([$rebazId, $saraId, $danaId] as $userId) {
            DB::table('branch_user_assignments')->insertOrIgnore([
                'user_id' => $userId,
                'branch_id' => $branchId,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
