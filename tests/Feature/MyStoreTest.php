<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Tenant;
use App\Models\Product;

class MyStoreTest extends TestCase
{
    public function test_tenant_databases_are_strictly_isolated(): void
    {
        // Setup tenants
        $tenant1 = Tenant::create(['id' => 'test_store_1']);
        $tenant1->domains()->create(['domain' => 'test1.localhost');

        $tenant2 = Tenant::create(['id' => 'test_store_2'']);
        $tenant2->domains()->create(['domain' => 'test2.localhost');

        // Insert into tenant 1
        tenancy()->initialize($tenant1);
        Product::create([
            'name' => 'Secret Product 1',
            'description' => 'Test',
            'price' => 100,
            'stock' => 10,
        ]);
        $this->assertDatabaseHas('products', ['name' => 'Secret Product 1']);
        tenancy()->end();

        // Check in tenant 2
        tenancy()->initialize($tenant2);
        $this->assertDatabaseMissing('products', ['name' => 'Secret Product 1']);
        tenancy()->end();

        // Cleanup
        $tenant1->delete();
        $tenant2->delete();
    }
}