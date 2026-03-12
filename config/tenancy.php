<?php
declare(strict_types=1);
return [
    'tenant_model' => \App\Models\Tenant::class,
    'id_generator' => \Stancl\Tenancy\UUIDGenerator::class,
    'central_domains' => ['localhost', '127.0.0.1'],
    'bootstrappers' => [
        \Stancl\Tenancy\Bootstrappers\DatabaseTenancyBootstrapper::class,
        \Stancl\Tenancy\Bootstrappers\CacheTenancyBootstrapper::class,
        \Stancl\Tenancy\Bootstrappers\FilesystemTenancyBootstrapper::class,
        \Stancl\Tenancy\Bootstrappers\QueueTenancyBootstrapper::class,
    ],
    'database' => [
        'central_connection' => 'mysql',
        'template_tenant_connection' => null,
        'prefix' => 'tenant',
        'suffix' => '',
        'managers' => [
            'mysql' => \Stancl\Tenancy\Database\TenantDatabaseManagers\MySQLDatabaseManager::class,
        ],
    ],
    'routes' => true,
];
