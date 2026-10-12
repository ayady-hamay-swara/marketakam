<?php

namespace Tests\Feature;

use App\Models\Store;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OwnerDashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_owner_dashboard_shows_selected_store_name(): void
    {
        $owner = User::unguarded(function () {
            return User::create([
                'username' => 'owner2',
                'email' => 'owner2@example.com',
                'password' => bcrypt('secret123'),
                'role' => 'owner',
            ]);
        });

        $store = Store::create([
            'owner_user_id' => $owner->id,
            'name' => 'Main Branch',
            'status' => 'active',
            'subscription_plan' => 'pro',
        ]);

        $this->actingAs($owner);

        $this->get('/dashboard?store_id=' . $store->id)
            ->assertOk()
            ->assertSeeText('Main Branch');
    }
}
