<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->command?->warn('No default seeders are called. Run Global\GlobalDatabaseSeeder on the global database or Tenant\TenantDatabaseSeeder on a tenant database.');
    }
}
