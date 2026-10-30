<?php

namespace Database\Seeders\Tenant;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TenantDatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();
        foreach ([['MAIN', 'Main Branch', 'Demo address'], ['BR02', 'Second Branch', 'Demo address 2']] as [$code, $name, $address]) {
            DB::table('branches')->updateOrInsert(['code' => $code], [
                'name' => $name, 'address' => $address, 'phone' => null, 'status' => 'active',
                'updated_at' => $now, 'created_at' => $now,
            ]);
        }
        $main = DB::table('branches')->where('code', 'MAIN')->value('id');
        $second = DB::table('branches')->where('code', 'BR02')->value('id');

        // Optional: set this to the global user ID of the demo cashier to assign them to MAIN.
        // The tenant DB cannot safely infer a global user ID without a configured global connection.
        $cashierUserId = env('MARKETAKAM_DEMO_CASHIER_USER_ID');
        if (is_numeric($cashierUserId)) {
            DB::table('branch_user_assignments')->updateOrInsert(
                ['user_id' => (int) $cashierUserId, 'branch_id' => $main],
                ['updated_at' => $now, 'created_at' => $now]
            );
        }

        foreach (['Drinks', 'Dairy', 'Rice and Grains', 'Household'] as $name) {
            DB::table('categories')->updateOrInsert(['name' => $name], ['is_active' => true, 'updated_at' => $now, 'created_at' => $now]);
        }
        $cats = DB::table('categories')->pluck('id', 'name');
        $products = [
            ['RICE-5KG', 'Rice 5 kg', '100000000001', 'Rice and Grains', 10000, 8500, 'piece'],
            ['MILK-1L', 'Milk 1 L', '100000000002', 'Dairy', 1000, 750, 'piece'],
            ['SUGAR-1KG', 'Sugar 1 kg', '100000000003', 'Rice and Grains', 2000, 1500, 'piece'],
            ['WATER-1L', 'Water 1 L', '100000000004', 'Drinks', 500, 300, 'piece'],
        ];
        foreach ($products as [$code, $name, $barcode, $category, $price, $cost, $unit]) {
            DB::table('products')->updateOrInsert(['code' => $code], [
                'name' => $name, 'barcode' => $barcode, 'category_id' => $cats[$category],
                'selling_price' => $price, 'cost_price' => $cost, 'unit' => $unit,
                'notes' => null, 'is_active' => true, 'updated_at' => $now, 'created_at' => $now,
            ]);
        }
        $ids = DB::table('products')->pluck('id', 'code');
        $stocks = [
            [$main, 'RICE-5KG', 100, 10], [$second, 'RICE-5KG', 35, 10],
            [$main, 'MILK-1L', 50, 5], [$second, 'MILK-1L', 12, 5],
            [$main, 'SUGAR-1KG', 40, 5], [$second, 'SUGAR-1KG', 20, 5],
            [$main, 'WATER-1L', 200, 24], [$second, 'WATER-1L', 90, 24],
        ];
        foreach ($stocks as [$branchId, $code, $quantity, $minStock]) {
            DB::table('branch_inventory')->updateOrInsert(['branch_id' => $branchId, 'product_id' => $ids[$code]], [
                'quantity' => $quantity, 'min_stock' => $minStock, 'updated_at' => $now, 'created_at' => $now,
            ]);
        }
        $this->command?->info('Tenant demo data created: two branches, categories, products, and stock.');
    }
}
