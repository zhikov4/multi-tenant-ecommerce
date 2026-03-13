<?php

declare(strict_types=1);

return [
    'tenant_model' => \App\Models\Tenant::class,
    'id_generator' => \Stancl\Tenancy\UUIDGenerator::class,

    // JURUS ANTI-404: Daftarkan semua host pusat di sini
    'central_domains' => [
        '127.0.0.1',
        'localhost',
        'localhost:8000',
    ],

    'database' => [
        'central_connection' => env('DB_CONNECTION', 'mysql'),
        'template_tenant_connection' => null,
        'prefix' => 'tenant',
        'suffix' => '',
        'managers' => [
            'mysql' => \Stancl\Tenancy\TenantDatabaseManagers\MySQLDatabaseManager::class,
        ],
    ],

    'routes' => false, // Kita handle rute manual di web.php dan tenant.php
    'assets' => ['asset_helper' => false, 'central_host' => 'localhost'],
    'storage' => ['suffix_base' => 'tenant', 'disks' => ['local', 'public']],
    'resource_splitting' => ['migrations' => ['tenant_path' => database_path('migrations/tenant')]],
    
    'bootstrappers' => [
        \Stancl\Tenancy\Bootstrappers\DatabaseTenancyBootstrapper::class,
        \Stancl\Tenancy\Bootstrappers\CacheTenancyBootstrapper::class,
        \Stancl\Tenancy\Bootstrappers\FilesystemTenancyBootstrapper::class,
        \Stancl\Tenancy\Bootstrappers\QueueTenancyBootstrapper::class,
    ],
];