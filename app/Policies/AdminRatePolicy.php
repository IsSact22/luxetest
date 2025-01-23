<?php

namespace App\Policies;

use App\Models\AdminRate;
use App\Models\User;

class AdminRatePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole(['admin', 'super-admin']) && $user->can('read-rate');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, AdminRate $adminRate): bool
    {
        return $user->hasAnyRole(['admin', 'super-admin']) && $user->can('read-rate');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasAnyRole(['admin', 'super-admin']) && $user->can('create-rate');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, AdminRate $adminRate): bool
    {
        return $user->hasAnyRole(['admin', 'super-admin']) && $user->can('update-rate');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, AdminRate $adminRate): bool
    {
        return $user->hasAnyRole(['admin', 'super-admin']) && $user->can('delete-rate');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, AdminRate $adminRate): bool
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, AdminRate $adminRate): bool
    {
        return $user->hasAnyRole(['admin', 'super-admin']) && $user->can('delete-rate');
    }
}
