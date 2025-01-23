<?php

namespace App\Providers;

use App\Contracts\AdminRateRepositoryInterface;
use App\Contracts\AircraftRepositoryInterface;
use App\Contracts\BrandAircraftRepositoryInterface;
use App\Contracts\CamoActivityRepositoryInterface;
use App\Contracts\CamoRepositoryInterface;
use App\Contracts\EngineTypeRepositoryInterface;
use App\Contracts\LaborRateRepositoryInterface;
use App\Contracts\ModelAircraftRepositoryInterface;
use App\Contracts\PermissionRepositoryInterface;
use App\Contracts\RoleRepositoryInterface;
use App\Contracts\UserRepositoryInterface;
use App\Repositories\AdminRateRepository;
use App\Repositories\AircraftRepository;
use App\Repositories\BrandAircraftRepository;
use App\Repositories\CamoActivityRepository;
use App\Repositories\CamoRepository;
use App\Repositories\EngineTypeRepository;
use App\Repositories\LaborRateRepository;
use App\Repositories\ModelAircraftRepository;
use App\Repositories\PermissionRepository;
use App\Repositories\RoleRepository;
use App\Repositories\UserRepository;
use Illuminate\Support\ServiceProvider;
use Override;

class RepositoryProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    #[Override]
    public function register(): void
    {
        $this->app->bind(AdminRateRepositoryInterface::class, AdminRateRepository::class);
        $this->app->bind(AircraftRepositoryInterface::class, AircraftRepository::class);
        $this->app->bind(BrandAircraftRepositoryInterface::class, BrandAircraftRepository::class);
        $this->app->bind(CamoActivityRepositoryInterface::class, CamoActivityRepository::class);
        $this->app->bind(CamoRepositoryInterface::class, CamoRepository::class);
        $this->app->bind(EngineTypeRepositoryInterface::class, EngineTypeRepository::class);
        $this->app->bind(LaborRateRepositoryInterface::class, LaborRateRepository::class);
        $this->app->bind(ModelAircraftRepositoryInterface::class, ModelAircraftRepository::class);
        $this->app->bind(PermissionRepositoryInterface::class, PermissionRepository::class);
        $this->app->bind(RoleRepositoryInterface::class, RoleRepository::class);
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
