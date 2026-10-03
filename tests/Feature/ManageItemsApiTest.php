<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\Store;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ManageItemsApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_cashier_can_create_and_fetch_items(): void
    {
        $store = Store::create([
            'name' => 'Main Store',
            'status' => 'active',
        ]);

        $user = User::unguarded(function () use ($store) {
            return User::create([
                'username' => 'cashier1',
                'email' => 'cashier1@example.com',
                'password' => bcrypt('secret123'),
                'role' => 'cashier',
                'current_store_id' => $store->id,
            ]);
        });

        $this->actingAs($user);

        $payload = [
            'code' => 'P-9001',
            'name' => 'Test Product',
            'category' => 'Food',
            'price' => 1500,
            'stock' => 25,
            'min_stock' => 10,
            'barcode' => '9001',
            'notes' => 'Test notes',
            'is_active' => true,
        ];

        $createResponse = $this->postJson('/api/items', $payload);
        $createResponse->assertOk();

        $this->assertDatabaseHas('products', ['code' => 'P-9001', 'store_id' => $store->id]);

        $listResponse = $this->getJson('/api/items');
        $listResponse->assertOk()->assertJsonFragment(['code' => 'P-9001']);
    }
}
