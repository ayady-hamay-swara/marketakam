<?php

namespace Database\Seeders\Tenant;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class TenantDatabaseSeeder extends Seeder
{
    public function run(): void
    {
        if (! app()->environment(['local', 'development', 'testing'])) {
            throw new RuntimeException('Demo seeding is blocked outside local/development/testing.');
        }
        $now = now();
        foreach ([['MAIN', 'Main Branch'], ['BR02', 'Second Branch']] as [$code, $name]) {
            DB::table('branches')->updateOrInsert(['code' => $code], [
                'name' => $name, 'status' => 'active', 'updated_at' => $now, 'created_at' => $now,
            ]);
        }
        $mainId = DB::table('branches')->where('code', 'MAIN')->value('id');
        $secondId = DB::table('branches')->where('code', 'BR02')->value('id');
        $cashierId = env('MARKETAKAM_DEMO_CASHIER_USER_ID');
        if ($cashierId) {
            DB::table('branch_user_assignments')->updateOrInsert(
                ['user_id' => (int) $cashierId, 'branch_id' => $mainId],
                ['updated_at' => $now, 'created_at' => $now]
            );
        }
        foreach (['Drinks', 'Dairy', 'Rice and Grains', 'Household'] as $category) {
            DB::table('categories')->updateOrInsert(['name' => $category], [
                'is_active' => true, 'updated_at' => $now, 'created_at' => $now,
            ]);
        }
        $categoryIds = DB::table('categories')->pluck('id', 'name');
        $products = [
            ['RICE-5KG','Rice 5 kg','100000000001','Rice and Grains',9000,7500,'bag',5,'kg'],
            ['MILK-1L','Milk 1 L','100000000002','Dairy',1500,1200,'piece',1,'L'],
            ['SUGAR-1KG','Sugar 1 kg','100000000003','Rice and Grains',1750,1400,'bag',1,'kg'],
            ['WATER-1L','Water 1 L','100000000004','Drinks',500,300,'piece',1,'L'],
        ];
        foreach ($products as [$code,$name,$barcode,$category,$price,$cost,$unit,$size,$sizeUnit]) {
            DB::table('products')->updateOrInsert(['code' => $code], [
                'category_id' => $categoryIds[$category], 'name' => $name, 'barcode' => $barcode,
                'selling_price' => $price, 'cost_price' => $cost, 'unit' => $unit,
                'is_active' => true, 'updated_at' => $now, 'created_at' => $now,
            ]);
            $productId = DB::table('products')->where('code', $code)->value('id');
            DB::table('market_product_details')->updateOrInsert(['product_id' => $productId], [
                'sold_by_weight' => false, 'package_size' => $size, 'package_unit' => $sizeUnit,
                'updated_at' => $now, 'created_at' => $now,
            ]);
            foreach ([$mainId, $secondId] as $branchId) {
                DB::table('branch_inventory')->updateOrInsert(
                    ['branch_id' => $branchId, 'product_id' => $productId],
                    ['quantity' => $branchId === $mainId ? 30 : 12, 'min_stock' => 5,
                     'updated_at' => $now, 'created_at' => $now]
                );
            }
        }
        $this->command?->info('Tenant market demo records seeded.');
    }
}
