<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            StoreSeeder::class,
            UserSeeder::class,
            EmployeeSeeder::class,
            CustomerSeeder::class,
            ProductSeeder::class,
            SettingsSeeder::class,
            OrderSeeder::class,
            OrderItemSeeder::class,
            DebtSeeder::class,
            DebtPaymentSeeder::class,
        ]);
    }
}
