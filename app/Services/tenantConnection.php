<?php

namespace App\Services;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class TenantConnection
{
    public function connect(string $databaseName): void
    {
        if (! preg_match('/^[a-zA-Z0-9_]+$/', $databaseName)) {
            throw new RuntimeException('Invalid tenant database name.');
        }

        Config::set(
            'database.connections.tenant.database',
            $databaseName
        );

        DB::purge('tenant');

        DB::reconnect('tenant');

        // Verify that Laravel connected to the intended database.
        $actualDatabase = DB::connection('tenant')->getDatabaseName();

        if ($actualDatabase !== $databaseName) {
            DB::purge('tenant');

            throw new RuntimeException(
                'Tenant database connection verification failed.'
            );
        }
    }

    public function disconnect(): void
    {
        DB::purge('tenant');

        Config::set('database.connections.tenant.database', null);
    }
}
