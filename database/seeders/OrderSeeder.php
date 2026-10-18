<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OrderSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('orders')->insert([
            [
                'store_id' => 1,
                'order_number' => 'ORD-1001',
                'customer_id' => 1,
                'employee_id' => 3,
                'subtotal' => 5400,
                'discount_percent' => 0,
                'discount_amount' => 0,
                'total' => 5400,
                'payment_method' => 'CASH',
                'status' => 'completed',
                'sale_type' => 'sale',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'store_id' => 1,
                'order_number' => 'ORD-1002',
                'customer_id' => 2,
                'employee_id' => 3,
                'subtotal' => 9600,
                'discount_percent' => 5,
                'discount_amount' => 480,
                'total' => 9120,
                'payment_method' => 'CARD',
                'status' => 'completed',
                'sale_type' => 'sale',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
