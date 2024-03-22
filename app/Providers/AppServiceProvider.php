<?php

namespace App\Providers;

use App\Contracts\CamoActivityRepositoryInterface;
use App\Contracts\CamoRepositoryInterface;
use App\Contracts\UserRepositoryInterface;
use App\Repositories\CamoActivityRepository;
use App\Repositories\CamoRepository;
use App\Repositories\UserRepository;
use Illuminate\Support\ServiceProvider;
use Override;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    #[Override]
    public function register(): void
    {
        if ($this->app->isLocal()) {
            $this->app->register(\Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider::class);
        }

        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind(CamoRepositoryInterface::class, CamoRepository::class);
        $this->app->bind(CamoActivityRepositoryInterface::class, CamoActivityRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
