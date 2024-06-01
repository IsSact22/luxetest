<?php

namespace App\Policies;

use App\Models\BrandAircraft;
use App\Models\User;

class BrandAircraftPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole(['super-admin', 'admin', 'cam']) && $user->can('read-brand-aircraft');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, BrandAircraft $brandAircraft): bool
    {
        return $user->hasAnyRole(['super-admin', 'admin', 'cam']) && $user->can('read-brand-aircraft');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasAnyRole(['super-admin', 'admin', 'cam']) && $user->can('create-brand-aircraft');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, BrandAircraft $brandAircraft): bool
    {
        return $user->hasAnyRole(['super-admin', 'admin', 'cam']) && $user->can('update-brand-aircraft');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, BrandAircraft $brandAircraft): bool
    {
        return $user->hasAnyRole(['super-admin', 'admin', 'cam']) && $user->can('delete-brand-aircraft');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, BrandAircraft $brandAircraft): bool
    {
        return $user->hasAnyRole(['super-admin', 'admin', 'cam']) && $user->can('delete-brand-aircraft');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, BrandAircraft $brandAircraft): bool
    {
        return $user->hasAnyRole(['super-admin', 'admin', 'cam']) && $user->can('force-delete');
    }
}
