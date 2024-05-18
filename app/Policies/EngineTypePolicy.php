<?php

namespace App\Policies;

use App\Models\EngineType;
use App\Models\User;

class EngineTypePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole(['admin', 'super-admin']) && $user->can('backoffice-read');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, EngineType $engineType): bool
    {
        return $user->hasAnyRole(['admin', 'super-admin']) && $user->can('backoffice-read');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasAnyRole(['admin', 'super-admin']) && $user->can('backoffice-create');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, EngineType $engineType): bool
    {
        return $user->hasAnyRole(['admin', 'super-admin']) && $user->can('backoffice-update');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, EngineType $engineType): bool
    {
        return $user->hasAnyRole(['admin', 'super-admin']) && $user->can('backoffice-delete');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, EngineType $engineType): bool
    {
        return $user->hasAnyRole(['admin', 'super-admin']) && $user->can('backoffice-restore');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, EngineType $engineType): bool
    {
        return $user->hasAnyRole(['admin', 'super-admin']) && $user->can('force-delete');
    }
}
