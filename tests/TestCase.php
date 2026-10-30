<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        // Configure migration paths for testing
        $this->artisan('migrate', [
            '--path' => [
                'database/migrations/central',
                'database/migrations/tenant/Core',
                'database/migrations/tenant/Modules/Market',
            ],
            '--force' => true,
        ]);
    }
}
