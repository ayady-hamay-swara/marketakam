<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\Store;
use App\Models\User;
use App\Models\BranchInventory;
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

        // New schema: product exists (no store_id), inventory tracked in branch_inventory
        $this->assertDatabaseHas('products', ['code' => 'P-9001']);
        $this->assertDatabaseHas('branch_inventory', [
            'branch_id' => $store->id,
            'quantity' => 25,
            'min_stock' => 10,
        ]);

        $listResponse = $this->getJson('/api/items');
        $listResponse->assertOk()->assertJsonFragment(['code' => 'P-9001']);
    }

    public function test_arabic_and_kurdish_digits_are_normalized_for_duplicates(): void
    {
        $store = Store::create([
            'name' => 'Main Store',
            'status' => 'active',
        ]);

        $user = User::unguarded(function () use ($store) {
            return User::create([
                'username' => 'cashier3',
                'email' => 'cashier3@example.com',
                'password' => bcrypt('secret123'),
                'role' => 'cashier',
                'current_store_id' => $store->id,
            ]);
        });

        $this->actingAs($user);

        $this->postJson('/api/items', [
            'code' => 'P-١٢٣',
            'name' => 'Arabic Digit Test',
            'category' => 'Food',
            'price' => 1500,
            'stock' => 10,
            'min_stock' => 2,
            'barcode' => '١٢٣٤٥',
            'notes' => 'first item',
            'is_active' => true,
        ])->assertOk();

        // New schema: check product and branch_inventory
        $this->assertDatabaseHas('products', ['code' => 'P-123', 'barcode' => '12345']);
        $this->assertDatabaseHas('branch_inventory', [
            'branch_id' => $store->id,
            'quantity' => 10,
            'min_stock' => 2,
        ]);

        $duplicateResponse = $this->postJson('/api/items', [
            'code' => 'P-۱۲۳',
            'name' => 'Kurdish Digit Duplicate',
            'category' => 'Food',
            'price' => 2000,
            'stock' => 5,
            'min_stock' => 1,
            'barcode' => '۱۲۳۴۵',
            'notes' => 'duplicate item',
            'is_active' => true,
        ]);

        $duplicateResponse->assertStatus(422);
    }

    public function test_categories_endpoint_exposes_default_category_options(): void
    {
        $store = Store::create([
            'name' => 'Main Store',
            'status' => 'active',
        ]);

        $user = User::unguarded(function () use ($store) {
            return User::create([
                'username' => 'cashier2',
                'email' => 'cashier2@example.com',
                'password' => bcrypt('secret123'),
                'role' => 'cashier',
                'current_store_id' => $store->id,
            ]);
        });

        $this->actingAs($user);

        $response = $this->getJson('/api/categories');

        $response->assertOk()
            ->assertJsonFragment(['Pieces'])
            ->assertJsonFragment(['Weight']);
    }
}
