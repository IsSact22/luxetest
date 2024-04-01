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
        return $user->hasAnyRole(['super-admin', 'admin', 'cam', 'owner', 'crew']);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Camo $camo): bool
    {

        if ($user->hasAnyRole(['super-admin', 'admin', 'cam'])) {
            return true;
        } elseif (($user->hasAnyRole(['owner', 'crew']) && $user->id === $camo->owner_id) || ($user->hasRole('crew') && $user->owner->id === $camo->owner_id)) {
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
        return $user->hasRole(['super-admin', 'admin', 'cam']);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Camo $camo): bool
    {
        return $user->hasRole(['super-admin', 'admin', 'cam']);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Camo $camo): bool
    {
        return $user->hasRole(['super-admin', 'admin']);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Camo $camo): bool
    {
        return $user->hasRole(['super-admin', 'admin']);
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Camo $camo): bool
    {
        return $user->hasRole(['super-admin', 'admin']);
    }
}
