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

        $password = env('MARKETAKAM_DEMO_PASSWORD');

        if (! is_string($password) || $password === '') {
            throw new RuntimeException('Set MARKETAKAM_DEMO_PASSWORD in your .env before running this seeder.');
        }

        $now = now();

        // Demo owner and cashier for the Ahmed Market example.
        $this->upsertUser('ahmed-owner', 'ahmed@example.test', $password, $now);
        $this->upsertUser('dara-cashier', 'dara@example.test', $password, $now);

        $ahmedId = DB::table('users')->where('email', 'ahmed@example.test')->value('id');
        $daraId = DB::table('users')->where('email', 'dara@example.test')->value('id');

        $ahmedBusinessId = $this->upsertBusiness(
            $ahmedId,
            'Ahmed Market Demo',
            'market',
            $now
        );

        $this->upsertMembership($ahmedBusinessId, $ahmedId, 'owner', $now);
        $this->upsertMembership($ahmedBusinessId, $daraId, 'cashier', $now);

        $this->upsertTenantDatabase(
            $ahmedBusinessId,
            'tenant_ahmed_market_demo',
            'marketakam_ahmed_market_demo',
            $now
        );

        // Your own user, business-owner membership, and separate tenant database.
        $this->upsertUser('ayad-owner', 'ayad@example.test', $password, $now);
        $ayadId = DB::table('users')->where('email', 'ayad@example.test')->value('id');

        $ayadBusinessId = $this->upsertBusiness(
            $ayadId,
            'Ayad Market',
            'market',
            $now
        );

        $this->upsertMembership($ayadBusinessId, $ayadId, 'owner', $now);

        $this->upsertTenantDatabase(
            $ayadBusinessId,
            'tenant_ayad',
            'marketakam_ayad',
            $now
        );

        $this->command?->info('Global records seeded successfully.');
        $this->command?->info('Ayad owner login: ayad@example.test');
        $this->command?->info('Ahmed demo owner: ahmed@example.test; cashier: dara@example.test');
        $this->command?->warn('All three demo users use MARKETAKAM_DEMO_PASSWORD from your .env.');
        $this->command?->warn('Both tenant database records are marked provisioning until their tenant migrations are run.');
    }

    private function upsertUser(string $username, string $email, string $password, $now): void
    {
        DB::table('users')->updateOrInsert(
            ['email' => $email],
            [
                'username' => $username,
                'password' => Hash::make($password),
                'email_verified_at' => $now,
                'updated_at' => $now,
                'created_at' => $now,
            ]
        );
    }

    private function upsertBusiness(int $ownerId, string $name, string $type, $now): int
    {
        DB::table('businesses')->updateOrInsert(
            ['owner_user_id' => $ownerId, 'name' => $name],
            [
                'business_type' => $type,
                'status' => 'active',
                'subscription_plan' => 'basic',
                'updated_at' => $now,
                'created_at' => $now,
            ]
        );

        return (int) DB::table('businesses')
            ->where('owner_user_id', $ownerId)
            ->where('name', $name)
            ->value('id');
    }

    private function upsertMembership(int $businessId, int $userId, string $role, $now): void
    {
        DB::table('business_memberships')->updateOrInsert(
            ['business_id' => $businessId, 'user_id' => $userId],
            [
                'role' => $role,
                'status' => 'active',
                'updated_at' => $now,
                'created_at' => $now,
            ]
        );
    }

    private function upsertTenantDatabase(
        int $businessId,
        string $connectionName,
        string $databaseName,
        $now
    ): void {
        DB::table('tenant_databases')->updateOrInsert(
            ['business_id' => $businessId],
            [
                'connection_name' => $connectionName,
                'database_name' => $databaseName,
                'status' => 'provisioning',
                'updated_at' => $now,
                'created_at' => $now,
            ]
        );
    }
}
