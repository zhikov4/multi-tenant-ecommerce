<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Event;
use Stancl\JobPipeline\JobPipeline;
use Stancl\Tenancy\Events;
use Stancl\Tenancy\Jobs;
use Stancl\Tenancy\Listeners;
use Stancl\Tenancy\Middleware;

class TenancyServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(
            \Stancl\Tenancy\Contracts\TenantWithDatabase::class,
            \App\Models\Tenant::class
        );
    }

    public function boot(): void
    {
        $this->bootEvents();
        $this->makeTenancyMiddlewareHighestPriority();
    }

    protected function bootEvents(): void
    {
        Event::listen(
            Events\TenantCreated::class,
            JobPipeline::make([
                Jobs\CreateDatabase::class,
                Jobs\MigrateDatabase::class,
            ])->send(function (Events\TenantCreated $event) {
                return $event->tenant;
            })->shouldBeQueued(false)->toListener()
        );

        Event::listen(
            Events\TenantDeleted::class,
            JobPipeline::make([
                Jobs\DeleteDatabase::class,
            ])->send(function (Events\TenantDeleted $event) {
                return $event->tenant;
            })->shouldBeQueued(false)->toListener()
        );

        Event::listen(Events\TenancyInitialized::class, Listeners\BootstrapTenancy::class);
        Event::listen(Events\TenancyEnded::class, Listeners\RevertToCentralContext::class);
    }

    protected function makeTenancyMiddlewareHighestPriority(): void
    {
        $tenancyMiddleware = [
            Middleware\InitializeTenancyByDomain::class,
            Middleware\InitializeTenancyBySubdomain::class,
            Middleware\InitializeTenancyByDomainOrSubdomain::class,
            Middleware\InitializeTenancyByPath::class,
            Middleware\InitializeTenancyByRequestData::class,
        ];

        foreach (array_reverse($tenancyMiddleware) as $middleware) {
            $this->app[\Illuminate\Contracts\Http\Kernel::class]
                ->prependToMiddlewarePriority($middleware);
        }
    }
}
