<?php

namespace App\Providers;

use App\Models\Camo;
use App\Models\CamoActivity;
use App\Models\User;
use App\Policies\CamoActivityPolicy;
use App\Policies\CamoPolicy;
use App\Policies\RolePolicy;
use App\Policies\UserPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Spatie\Permission\Models\Role;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        User::class => UserPolicy::class,
        Role::class => RolePolicy::class,
        Camo::class => CamoPolicy::class,
        CamoActivity::class => CamoActivityPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();
        Gate::before(fn ($user, $ability) => $user->hasRole('super-admin') ? true : null);
    }

    protected function getDefaultGuardName(): string
    {
        return 'web';
    }
}
