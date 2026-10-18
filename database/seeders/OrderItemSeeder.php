<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OrderItemSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('order_items')->insert([
            ['order_id' => 1, 'product_id' => 1, 'quantity' => 2, 'unit_price' => 1800, 'total' => 3600, 'created_at' => now(), 'updated_at' => now()],
            ['order_id' => 1, 'product_id' => 4, 'quantity' => 2, 'unit_price' => 900, 'total' => 1800, 'created_at' => now(), 'updated_at' => now()],
            ['order_id' => 2, 'product_id' => 2, 'quantity' => 1, 'unit_price' => 7800, 'total' => 7800, 'created_at' => now(), 'updated_at' => now()],
            ['order_id' => 2, 'product_id' => 3, 'quantity' => 1, 'unit_price' => 1200, 'total' => 1200, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
