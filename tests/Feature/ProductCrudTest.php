<?php

namespace Tests\Feature;

use App\Models\Tenant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Stancl\Tenancy\Facades\Tenancy;
use Tests\TestCase;

class ProductCrudTest extends TestCase
{
    use RefreshDatabase;

    private Tenant $tenant;

    protected function setUp(): void
    {
        parent::setUp();

        $this->tenant = Tenant::create(['id' => 'product-test-store']);
        $this->tenant->domains()->create(['domain' => 'product-test-store.localhost']);
    }

    protected function tearDown(): void
    {
        tenancy()->end();
        $this->tenant->delete();
        parent::tearDown();
    }

    public function test_products_page_is_accessible_on_tenant_domain(): void
    {
        $response = $this->get('http://product-test-store.localhost/products');
        $response->assertStatus(200);
    }

    public function test_product_can_be_created_in_tenant_database(): void
    {
        tenancy()->initialize($this->tenant);

        $user = User::factory()->create();

        $productData = [
            'name'        => 'Test Product',
            'description' => 'A test product description',
            'price'       => 29.99,
            'stock'       => 100,
            'category'    => 'Electronics',
            'sku'         => 'TEST-001',
            'is_active'   => true,
        ];

        $response = $this->actingAs($user)
            ->post('http://product-test-store.localhost/products', $productData);

        $this->assertDatabaseHas('products', [
            'name'  => 'Test Product',
            'price' => 29.99,
        ]);

        tenancy()->end();
    }

    public function test_product_can_be_updated(): void
    {
        tenancy()->initialize($this->tenant);

        $user = User::factory()->create();

        $product = \App\Models\Product::create([
            'name'        => 'Old Name',
            'description' => 'Old description',
            'price'       => 10.00,
            'stock'       => 50,
            'category'    => 'General',
            'sku'         => 'OLD-001',
            'is_active'   => true,
        ]);

        $response = $this->actingAs($user)
            ->put("http://product-test-store.localhost/products/{$product->id}", [
                'name'        => 'New Name',
                'description' => 'New description',
                'price'       => 20.00,
                'stock'       => 75,
                'category'    => 'General',
                'sku'         => 'OLD-001',
                'is_active'   => true,
            ]);

        $this->assertDatabaseHas('products', [
            'id'    => $product->id,
            'name'  => 'New Name',
            'price' => 20.00,
        ]);

        tenancy()->end();
    }

    public function test_product_can_be_deleted(): void
    {
        tenancy()->initialize($this->tenant);

        $user = User::factory()->create();

        $product = \App\Models\Product::create([
            'name'        => 'Delete Me',
            'description' => 'Will be deleted',
            'price'       => 5.00,
            'stock'       => 10,
            'category'    => 'Test',
            'sku'         => 'DEL-001',
            'is_active'   => true,
        ]);

        $this->actingAs($user)
            ->delete("http://product-test-store.localhost/products/{$product->id}");

        $this->assertDatabaseMissing('products', ['id' => $product->id]);

        tenancy()->end();
    }

    public function test_products_are_isolated_between_tenants(): void
    {
        $tenantB = Tenant::create(['id' => 'isolation-store-b']);
        $tenantB->domains()->create(['domain' => 'isolation-store-b.localhost']);

        tenancy()->initialize($this->tenant);
        \App\Models\Product::create([
            'name'        => 'Store A Product',
            'description' => 'Belongs to store A',
            'price'       => 15.00,
            'stock'       => 30,
            'category'    => 'A',
            'sku'         => 'A-001',
            'is_active'   => true,
        ]);
        tenancy()->end();

        tenancy()->initialize($tenantB);
        $productsInB = \App\Models\Product::where('name', 'Store A Product')->get();
        tenancy()->end();

        $this->assertCount(0, $productsInB);

        $tenantB->delete();
    }

    public function test_product_requires_valid_data(): void
    {
        tenancy()->initialize($this->tenant);

        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->post('http://product-test-store.localhost/products', [
                'name'  => '',
                'price' => -1,
            ]);

        $response->assertSessionHasErrors(['name', 'price']);

        tenancy()->end();
    }
}