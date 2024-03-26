<?php

namespace App\Policies;

use App\Models\CamoActivity;
use App\Models\User;

class CamoActivityPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        //
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, CamoActivity $camoActivity): bool
    {
        if ($user->hasAnyRole(['super-admin', 'admin', 'cam'])) {
            return true;
        } elseif ($user->hasAnyRole(['owner', 'crew'])) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasAnyRole(['super-admin', 'admin', 'cam']);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, CamoActivity $camoActivity): bool
    {
        if ($user->hasAnyRole(['super-admin', 'admin', 'cam'])) {
            return true;
        } elseif ($user->hasRole('owner') && $camoActivity->camo->owner_id === $user->id) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, CamoActivity $camoActivity): bool
    {
        return $user->hasAnyRole(['super-admin', 'admin']);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, CamoActivity $camoActivity): bool
    {
        return $user->hasAnyRole(['super-admin', 'admin']);
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, CamoActivity $camoActivity): bool
    {
        return $user->hasAnyRole(['super-admin', 'admin']);
    }
}
