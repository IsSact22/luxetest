<?php

namespace App\Policies;

use App\Models\Camo;
use App\Models\User;

class CamoPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole(['super-admin', 'admin', 'cam']) && $user->can('read-camo');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Camo $camo): bool
    {
        if ($user->hasAnyRole(['super-admin', 'admin']) && $user->can('read-camo')) {
            return true;
        }

        if ($user->hasRole('cam') && ($camo->cam_id === $user->id) && $user->can('read-camo')) {
            return true;
        }

        return $user->hasAnyRole(['owner', 'crew']) && ($user->id === $camo->owner_id || $user->owner_id === $camo->owner_id);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasRole('cam') && $user->can('create-camo');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Camo $camo): bool
    {
        return $user->can('update-camo') && $user->id === $camo->cam_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Camo $camo): bool
    {
        return $user->hasAnyRole(['super-admin', 'admin', 'cam']) && $user->can('delete-camo');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Camo $camo): bool
    {
        return $user->hasAnyRole(['super-admin', 'admin', 'cam']) && $user->can('delete-camo') && $user->id === $camo->cam_id;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Camo $camo): bool
    {
        return $user->can('force-delete');
    }
}
