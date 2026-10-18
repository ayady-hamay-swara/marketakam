<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StoreSeeder extends Seeder
{
    public function run(): void
    {
        $ownerId = DB::table('users')->where('role', 'owner')->value('id') ?? 2;

        DB::table('stores')->insert([
            [
                'owner_user_id' => $ownerId,
                'name' => 'Main Branch',
                'status' => 'active',
                'subscription_plan' => 'starter',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'owner_user_id' => $ownerId,
                'name' => 'Downtown Branch',
                'status' => 'active',
                'subscription_plan' => 'pro',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
