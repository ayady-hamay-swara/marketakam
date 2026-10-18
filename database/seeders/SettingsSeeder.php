<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SettingsSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('settings')->insert([
            ['store_id' => 1, 'key' => 'store_name', 'value' => 'Main Branch', 'created_at' => now(), 'updated_at' => now()],
            ['store_id' => 1, 'key' => 'cashier_name', 'value' => 'Dana Yousif', 'created_at' => now(), 'updated_at' => now()],
            ['store_id' => 1, 'key' => 'currency', 'value' => 'IQD', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
