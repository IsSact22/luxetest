<?php

namespace App\Policies;

use App\Models\ModelAircraft;
use App\Models\User;

class ModelAircraftPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole(['admin', 'super-admin', 'cam']) && $user->can('read-model-aircraft');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, ModelAircraft $modelAircraft): bool
    {
        return $user->hasAnyRole(['admin', 'super-admin', 'cam']) && $user->can('read-model-aircraft');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasAnyRole(['admin', 'super-admin', 'cam']) && $user->can('create-model-aircraft');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, ModelAircraft $modelAircraft): bool
    {
        return $user->hasAnyRole(['admin', 'super-admin', 'cam']) && $user->can('update-model-aircraft');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, ModelAircraft $modelAircraft): bool
    {
        return $user->hasAnyRole(['admin', 'super-admin', 'cam']) && $user->can('delete-model-aircraft');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, ModelAircraft $modelAircraft): bool
    {
        return $user->hasAnyRole(['admin', 'super-admin', 'cam']) && $user->can('delete-model-aircraft');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, ModelAircraft $modelAircraft): bool
    {
        return $user->can('force-delete');
    }
}
