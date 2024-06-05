<?php

namespace App\Providers;

use App\Models\AdminRate;
use App\Models\Aircraft;
use App\Models\BrandAircraft;
use App\Models\Camo;
use App\Models\CamoActivity;
use App\Models\EngineType;
use App\Models\LaborRate;
use App\Models\ModelAircraft;
use App\Models\User;
use App\Policies\AdminRatePolicy;
use App\Policies\AircraftPolicy;
use App\Policies\BrandAircraftPolicy;
use App\Policies\CamoActivityPolicy;
use App\Policies\CamoPolicy;
use App\Policies\EngineTypePolicy;
use App\Policies\LaborRatePolicy;
use App\Policies\ModelAircraftPolicy;
use App\Policies\PermissionPolicy;
use App\Policies\RolePolicy;
use App\Policies\UserPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        AdminRate::class => AdminRatePolicy::class,
        Aircraft::class => AircraftPolicy::class,
        BrandAircraft::class => BrandAircraftPolicy::class,
        CamoActivity::class => CamoActivityPolicy::class,
        Camo::class => CamoPolicy::class,
        EngineType::class => EngineTypePolicy::class,
        LaborRate::class => LaborRatePolicy::class,
        ModelAircraft::class => ModelAircraftPolicy::class,
        Permission::class => PermissionPolicy::class,
        Role::class => RolePolicy::class,
        User::class => UserPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();
        Gate::before(static fn ($user, $ability) => $user->hasRole('super-admin') ? true : null);
    }

    protected function getDefaultGuardName(): string
    {
        return 'web';
    }
}
