<?php

namespace App\Providers;

use App\Interfaces\PermissionServiceInterface;
use App\Services\PermissionService;
use Illuminate\Support\ServiceProvider;
use Override;

class PermissionServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    #[Override]
    public function register(): void
    {
        $this->app->bind(PermissionServiceInterface::class, PermissionService::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
