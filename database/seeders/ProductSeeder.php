<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('products')->insert([
            [
                'store_id' => 1,
                'code' => 'P-1001',
                'name' => 'Milk 1L',
                'barcode' => '890123456001',
                'category' => 'Food',
                'price' => 1800,
                'stock' => 35,
                'min_stock' => 10,
                'notes' => 'Fresh milk',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'store_id' => 1,
                'code' => 'P-1002',
                'name' => 'Rice 5kg',
                'barcode' => '890123456002',
                'category' => 'Food',
                'price' => 7800,
                'stock' => 18,
                'min_stock' => 12,
                'notes' => null,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'store_id' => 1,
                'code' => 'P-1003',
                'name' => 'Soap',
                'barcode' => '890123456003',
                'category' => 'Household',
                'price' => 1200,
                'stock' => 8,
                'min_stock' => 10,
                'notes' => 'Low stock alert',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'store_id' => 1,
                'code' => 'P-1004',
                'name' => 'Coca Cola 330ml',
                'barcode' => '890123456004',
                'category' => 'Drinks',
                'price' => 900,
                'stock' => 40,
                'min_stock' => 12,
                'notes' => null,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
