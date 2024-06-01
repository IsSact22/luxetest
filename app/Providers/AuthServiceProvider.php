<?php

namespace App\Providers;

use App\Models\Aircraft;
use App\Policies\AircraftPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Aircraft::class => AircraftPolicy::class,
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
