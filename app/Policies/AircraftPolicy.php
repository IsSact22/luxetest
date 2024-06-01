<?php

namespace App\Policies;

use App\Models\Aircraft;
use App\Models\User;

class AircraftPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole(['super-admin', 'admin', 'cam']) && $user->can('read-aircraft');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Aircraft $aircraft): bool
    {
        return $user->hasAnyRole(['super-admin', 'admin', 'cam']) && $user->can('read-aircraft');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasAnyRole(['super-admin', 'admin', 'cam']) && $user->can('create-aircraft');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Aircraft $aircraft): bool
    {
        return $user->hasAnyRole(['super-admin', 'admin', 'cam']) && $user->can('update-aircraft');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Aircraft $aircraft): bool
    {
        return $user->hasAnyRole(['super-admin', 'admin', 'cam']) && $user->can('delete-aircraft');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Aircraft $aircraft): bool
    {
        return $user->hasAnyRole(['super-admin', 'admin', 'cam']) && $user->can('delete-aircraft');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Aircraft $aircraft): bool
    {
        return $user->can('force-delete');
    }
}
