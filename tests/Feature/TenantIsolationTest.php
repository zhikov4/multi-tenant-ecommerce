<?php

namespace Tests\Feature;

use App\Models\Tenant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Stancl\Tenancy\Facades\Tenancy;
use Tests\TestCase;

class TenantIsolationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        config(['tenancy.bootstrappers' => [
            \Stancl\Tenancy\Bootstrappers\DatabaseTenancyBootstrapper::class,
        ]]);
    }

    public function test_tenant_can_be_created_with_domain(): void
    {
        $tenant = Tenant::create(['id' => 'storealpha']);
        $tenant->domains()->create(['domain' => 'storealpha.localhost']);

        $this->assertDatabaseHas('tenants', ['id' => 'storealpha']);
        $this->assertDatabaseHas('domains', ['domain' => 'storealpha.localhost']);
    }

    public function test_two_tenants_can_be_created_independently(): void
    {
        $tenantA = Tenant::create(['id' => 'store-a']);
        $tenantA->domains()->create(['domain' => 'store-a.localhost']);

        $tenantB = Tenant::create(['id' => 'store-b']);
        $tenantB->domains()->create(['domain' => 'store-b.localhost']);

        $this->assertDatabaseHas('tenants', ['id' => 'store-a']);
        $this->assertDatabaseHas('tenants', ['id' => 'store-b']);

        $this->assertEquals(1, $tenantA->domains()->count());
        $this->assertEquals(1, $tenantB->domains()->count());
    }

    public function test_tenant_domain_lookup_works(): void
    {
        $tenant = Tenant::create(['id' => 'mystore']);
        $tenant->domains()->create(['domain' => 'mystore.localhost']);

        $found = Tenant::whereHas('domains', function ($q) {
            $q->where('domain', 'mystore.localhost');
        })->first();

        $this->assertNotNull($found);
        $this->assertEquals('mystore', $found->id);
    }

    public function test_tenant_database_is_provisioned_on_create(): void
    {
        $tenant = Tenant::create(['id' => 'provision-test']);

        $databaseName = 'tenant' . $tenant->id;

        $databases = \DB::select('SHOW DATABASES LIKE ?', [$databaseName]);

        $this->assertNotEmpty($databases);

        $tenant->delete();
    }

    public function test_multiple_tenants_have_separate_databases(): void
    {
        $tenantA = Tenant::create(['id' => 'isolation-a']);
        $tenantB = Tenant::create(['id' => 'isolation-b']);

        $this->assertNotEquals(
            $tenantA->database()->getName(),
            $tenantB->database()->getName()
        );

        $tenantA->delete();
        $tenantB->delete();
    }

    public function test_central_database_is_not_affected_by_tenant_context(): void
    {
        $user = User::factory()->create(['email' => 'central@example.com']);

        $tenant = Tenant::create(['id' => 'ctx-test']);

        tenancy()->initialize($tenant);

        $centralUsers = \DB::connection('mysql')->table('users')
            ->where('email', 'central@example.com')
            ->get();

        tenancy()->end();

        $this->assertCount(1, $centralUsers);

        $tenant->delete();
    }

    public function test_deleting_tenant_removes_its_database(): void
    {
        $tenant = Tenant::create(['id' => 'delete-test']);
        $tenant->domains()->create(['domain' => 'delete-test.localhost']);

        $databaseName = 'tenant' . $tenant->id;

        $before = \DB::select('SHOW DATABASES LIKE ?', [$databaseName]);
        $this->assertNotEmpty($before);

        $tenant->delete();

        $after = \DB::select('SHOW DATABASES LIKE ?', [$databaseName]);
        $this->assertEmpty($after);
    }
}