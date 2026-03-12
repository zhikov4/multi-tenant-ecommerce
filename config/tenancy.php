<?php

declare(strict_types=1);

use App\Models\Tenant;

return [
    'tenant_model' => Tenant::class,
    'id_generator' => Stancl\Tenancy\UUIDGenerator::class,
    'domain_model' => Stancl\Tenancy\Database\Models\Domain::class,
    'central_domains' => [
        '127.0.0.1',
        'localhost',
    ],
    'bootstrappers' => [
        Stancl\Tenancy\Bootstrappers\DatabaseTenancyBootstrapper::class,
        Stancl\Tenancy\Bootstrappers\CacheTenancyBootstrapper::class,
        Stancl\Tenancy\Bootstrappers\FilesystemTenancyBootstrapper::class,
        Stancl\Tenancy\Bootstrappers\QueueTenancyBootstrapper::class,
    ],
    'database' => [
        'central_connection' => 'mysql',
        'template_tenant_connection' => null,
        'prefix' => 'tenant',
        'suffix' => '',
        'managers' => [
            'mysql' => 'Stancl\Tenancy\TenantDatabaseManagers\MySQLDatabaseManager',
        ],
    ],
    'cache' => [
        'tag_base' => 'tenant',
    ],
    'filesystem' => [
        'suffix_base' => 'tenant',
        'disks' => [
            'local',
            'public',
        ],
        'root_override' => [
            'local' => 'tenant%storage_key%',
            'public' => 'public/tenant%storage_key%',
        ],
    ],
    'features' => [
        Stancl\Tenancy\Features\ViteBundler::class,
    ],
];