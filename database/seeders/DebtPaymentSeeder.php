<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DebtPaymentSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('debt_payments')->insert([
            [
                'store_id' => 1,
                'debt_id' => 1,
                'customer_id' => 1,
                'amount' => 1200,
                'payment_method' => 'CASH',
                'paid_at' => now()->subDay(),
                'note' => 'First payment',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'store_id' => 1,
                'debt_id' => 1,
                'customer_id' => 1,
                'amount' => 500,
                'payment_method' => 'CARD',
                'paid_at' => now(),
                'note' => 'Second payment today',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
