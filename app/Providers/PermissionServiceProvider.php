<?php

namespace App\Providers;

use App\Interfaces\PermissionServiceInterface;
use App\Services\PermissionService;
use Illuminate\Support\ServiceProvider;

class PermissionServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
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
