<?php

namespace Database\Seeders\Global;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use RuntimeException;

class GlobalDatabaseSeeder extends Seeder
{
    public function run(): void
    {
        if (! app()->environment(['local', 'development', 'testing'])) {
            throw new RuntimeException('Demo seeding is blocked outside local/development/testing.');
        }
        $password = env('MARKETAKAM_DEMO_PASSWORD', 'Ayad1234');
        $now = now();
        DB::table('users')->updateOrInsert(['email' => 'ahmed@example.test'], [
            'username' => 'ahmed-owner', 'password' => Hash::make($password),
            'email_verified_at' => $now, 'updated_at' => $now, 'created_at' => $now,
        ]);
        DB::table('users')->updateOrInsert(['email' => 'dara@example.test'], [
            'username' => 'dara-cashier', 'password' => Hash::make($password),
            'email_verified_at' => $now, 'updated_at' => $now, 'created_at' => $now,
        ]);
        $ownerId = DB::table('users')->where('email', 'ahmed@example.test')->value('id');
        $cashierId = DB::table('users')->where('email', 'dara@example.test')->value('id');
        DB::table('businesses')->updateOrInsert(['name' => 'Ahmed Market Demo'], [
            'owner_user_id' => $ownerId, 'business_type' => 'market', 'status' => 'active',
            'subscription_plan' => 'basic', 'updated_at' => $now, 'created_at' => $now,
        ]);
        $businessId = DB::table('businesses')->where('name', 'Ahmed Market Demo')->value('id');
        foreach ([[$ownerId, 'owner'], [$cashierId, 'cashier']] as [$userId, $role]) {
            DB::table('business_memberships')->updateOrInsert(
                ['business_id' => $businessId, 'user_id' => $userId],
                ['role' => $role, 'status' => 'active', 'updated_at' => $now, 'created_at' => $now]
            );
        }
        DB::table('tenant_databases')->updateOrInsert(['business_id' => $businessId], [
            'connection_name' => 'tenant_ahmed_market_demo',
            'database_name' => 'marketakam_ahmed_market_demo', 'status' => 'provisioning',
            'updated_at' => $now, 'created_at' => $now,
        ]);
        $this->command?->info('Global demo records seeded. Owner: ahmed@example.test; cashier: dara@example.test.');
        $this->command?->warn('Set MARKETAKAM_DEMO_PASSWORD in .env. The fallback password is for local demo use only.');
    }
}
