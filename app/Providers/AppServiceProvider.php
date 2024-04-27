<?php

namespace App\Providers;

use App\Contracts\AircraftRepositoryInterface;
use App\Contracts\BrandAircraftRepositoryInterface;
use App\Contracts\CamoActivityRepositoryInterface;
use App\Contracts\CamoRateRepositoryInterface;
use App\Contracts\CamoRepositoryInterface;
use App\Contracts\EngineTypeRepositoryInterface;
use App\Contracts\ModelAircraftRepositoryInterface;
use App\Contracts\RoleRepositoryInterface;
use App\Contracts\UserRepositoryInterface;
use App\Repositories\AircraftRepository;
use App\Repositories\BrandAircraftRepository;
use App\Repositories\CamoActivityRepository;
use App\Repositories\CamoRateRepository;
use App\Repositories\CamoRepository;
use App\Repositories\EngineTypeRepository;
use App\Repositories\ModelAircraftRepository;
use App\Repositories\RoleRepository;
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
        $this->app->bind(RoleRepositoryInterface::class, RoleRepository::class);
        $this->app->bind(CamoRateRepositoryInterface::class, CamoRateRepository::class);
        $this->app->bind(EngineTypeRepositoryInterface::class, EngineTypeRepository::class);
        $this->app->bind(ModelAircraftRepositoryInterface::class, ModelAircraftRepository::class);
        $this->app->bind(BrandAircraftRepositoryInterface::class, BrandAircraftRepository::class);
        $this->app->bind(AircraftRepositoryInterface::class, AircraftRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
