<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class TenantIsolationTest extends TestCase
{
    public function test_tenant_prefix_generates_unique_database_names(): void
    {
        $prefix = config('tenancy.database.prefix');
        $suffix = config('tenancy.database.suffix');

        $dbA = $prefix . 'store-a' . $suffix;
        $dbB = $prefix . 'store-b' . $suffix;

        $this->assertNotEquals($dbA, $dbB);
        $this->assertStringStartsWith($prefix, $dbA);
        $this->assertStringStartsWith($prefix, $dbB);
    }

    public function test_tenant_connection_config_exists(): void
    {
        $connection = config('tenancy.database.template_tenant_connection');
        $config     = config('database.connections.' . $connection);

        $this->assertNotNull($config);
        $this->assertEquals('mysql', $config['driver']);
    }

    public function test_central_connection_config_exists(): void
    {
        $connection = config('tenancy.database.central_connection');
        $config     = config('database.connections.' . $connection);

        $this->assertNotNull($config);
        $this->assertEquals('mysql', $config['driver']);
    }

    public function test_tenant_and_central_connections_are_different(): void
    {
        $central = config('tenancy.database.central_connection');
        $tenant  = config('tenancy.database.template_tenant_connection');

        $this->assertNotEquals($central, $tenant);
    }

    public function test_products_in_separate_databases_are_isolated(): void
    {
        $prefix     = config('tenancy.database.prefix');
        $suffix     = config('tenancy.database.suffix');
        $baseConfig = config('database.connections.' . config('tenancy.database.template_tenant_connection'));

        $dbA = $prefix . 'test-iso-a' . $suffix;
        $dbB = $prefix . 'test-iso-b' . $suffix;

        DB::statement("CREATE DATABASE IF NOT EXISTS `{$dbA}`");
        DB::statement("CREATE DATABASE IF NOT EXISTS `{$dbB}`");

        config(['database.connections.conn_iso_a' => array_merge($baseConfig, ['database' => $dbA])]);
        config(['database.connections.conn_iso_b' => array_merge($baseConfig, ['database' => $dbB])]);

        DB::connection('conn_iso_a')->getSchemaBuilder()->dropIfExists('products');
        DB::connection('conn_iso_b')->getSchemaBuilder()->dropIfExists('products');

        foreach (['conn_iso_a', 'conn_iso_b'] as $conn) {
            DB::connection($conn)->getSchemaBuilder()->create('products', function ($table) {
                $table->id();
                $table->string('name');
                $table->decimal('price', 10, 2)->default(0);
                $table->integer('stock')->default(0);
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        }

        DB::connection('conn_iso_a')->table('products')->insert([
            'name' => 'Exclusive Product', 'price' => 99, 'stock' => 1,
            'is_active' => true, 'created_at' => now(), 'updated_at' => now(),
        ]);

        $found = DB::connection('conn_iso_b')->table('products')
            ->where('name', 'Exclusive Product')->first();

        $this->assertNull($found);

        DB::purge('conn_iso_a');
        DB::purge('conn_iso_b');
        DB::statement("DROP DATABASE IF EXISTS `{$dbA}`");
        DB::statement("DROP DATABASE IF EXISTS `{$dbB}`");
    }
}
