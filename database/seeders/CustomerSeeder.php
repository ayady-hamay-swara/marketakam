<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CustomerSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('customers')->insert([
            [
                'store_id' => 1,
                'name' => 'Ali Hassan',
                'phone' => '07500000001',
                'email' => 'ali@example.com',
                'address' => 'Erbil Center',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'store_id' => 1,
                'name' => 'Nawzad Karim',
                'phone' => '07500000002',
                'email' => null,
                'address' => 'Kirkuk',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'store_id' => 1,
                'name' => 'Zana Qadir',
                'phone' => '07500000003',
                'email' => 'zana@example.com',
                'address' => null,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
