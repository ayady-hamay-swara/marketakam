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
        if (! app()->environment('local')) {
            throw new RuntimeException('GlobalDatabaseSeeder is for local development only.');
        }
        $password = 'Ayad1234';
        $now = now();
        foreach ([['Ahmed Ali', 'ahmed@example.test'], ['Dara Cashier', 'dara@example.test']] as [$name, $email]) {
            $username = strtolower(str_replace(' ', '-', $name));

            DB::table('users')->updateOrInsert(['email' => $email], [
                'username' => $username, 'password' => Hash::make($password), 'email_verified_at' => $now,
                'updated_at' => $now, 'created_at' => $now,
            ]);
        }
        $ownerId = DB::table('users')->where('email', 'ahmed@example.test')->value('id');
        $cashierId = DB::table('users')->where('email', 'dara@example.test')->value('id');
        DB::table('businesses')->updateOrInsert(['name' => 'Ahmed Market Demo'], [
            'owner_user_id' => $ownerId, 'business_type' => 'market', 'status' => 'active',
            'subscription_plan' => 'basic', 'updated_at' => $now, 'created_at' => $now,
        ]);
        $businessId = DB::table('businesses')->where('name', 'Ahmed Market Demo')->value('id');
        foreach ([[$ownerId, 'owner'], [$cashierId, 'cashier']] as [$userId, $role]) {
            DB::table('business_memberships')->updateOrInsert(['business_id' => $businessId, 'user_id' => $userId], [
                'role' => $role, 'status' => 'active', 'updated_at' => $now, 'created_at' => $now,
            ]);
        }
        DB::table('tenant_databases')->updateOrInsert(['business_id' => $businessId], [
            'connection_name' => 'tenant_ahmed_market_demo', 'database_name' => 'marketakam_ahmed_demo',
            'status' => 'provisioning', 'updated_at' => $now, 'created_at' => $now,
        ]);
        $this->command?->info('Demo global records created. Ahmed and Dara share the local-only password: Ayad1234');
    }
}
