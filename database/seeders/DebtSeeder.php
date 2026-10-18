<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DebtSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('debts')->insert([
            [
                'store_id' => 1,
                'customer_id' => 1,
                'total_amount' => 2500,
                'paid_amount' => 1200,
                'remaining_amount' => 1300,
                'status' => 'partial',
                'notes' => 'Paid in two installments',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'store_id' => 1,
                'customer_id' => 2,
                'total_amount' => 5000,
                'paid_amount' => 0,
                'remaining_amount' => 5000,
                'status' => 'unpaid',
                'notes' => 'Pending payment',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
