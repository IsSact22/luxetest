<?php

namespace App\Providers;

use App\Contracts\PermissionRepositoryInterface;
use App\Repositories\PermissionRepository;
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
        $this->app->bind(PermissionRepositoryInterface::class, PermissionRepository::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
