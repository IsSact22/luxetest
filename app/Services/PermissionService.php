<?php

namespace App\Services;

use App\Helpers\LogHelper;
use App\Http\Resources\PermissionResource;
use App\Http\Resources\RoleResource;
use App\Http\Responses\ApiErrorResponse;
use App\Http\Responses\ApiSuccessResponse;
use App\Interfaces\PermissionServiceInterface;
use App\Models\User;
use Exception;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

use function App\Helpers\AfterCatchUnknown;

class PermissionService implements PermissionServiceInterface
{
    protected User $user;

    protected Role $role;

    protected Permission $permission;

    /**
     * Obtener todos los roles.
     */
    public function getRoles(): ApiSuccessResponse|ApiErrorResponse
    {
        try {
            return new ApiSuccessResponse(
                RoleResource::collection(Role::all()) ?? [],
                ['message' => 'return resource Role'],
                ResponseAlias::HTTP_ACCEPTED
            );
        } catch (Exception $e) {
            LogHelper::logError($e);

            return AfterCatchUnknown();
        }

    }

    /**
     * Crear un nuevo rol.
     */
    public function createRole(array $data): ApiSuccessResponse|ApiErrorResponse
    {
        try {
            $role = Role::create($data);

            return new ApiSuccessResponse(
                new RoleResource($role),
                ['message' => 'User register successfully.'],
                ResponseAlias::HTTP_ACCEPTED
            );
        } catch (Exception $e) {
            LogHelper::logError($e);

            return AfterCatchUnknown();
        }
    }

    /**
     * Obtener un rol por su ID.
     */
    public function getRoleById(int $id): RoleResource
    {
        $role = Role::find($id);

        return new RoleResource($role);
    }

    public function getRoleByName(string $name): RoleResource
    {
        $role = Role::findByName($name);

        return new RoleResource($role);
    }

    /**
     * Actualizar un rol existente.
     */
    public function updateRole(int $id, array $data): bool
    {
        $role = Role::find($id);

        if ($role) {
            $role->update($data);

            return true;
        }

        return false;
    }

    /**
     * Eliminar un rol por su ID.
     */
    public function deleteRole(int $id): bool
    {
        $rol = Role::find($id);

        if ($rol) {
            $rol->delete();

            return true;
        }

        return false;
    }

    public function getPermissions(): ApiSuccessResponse|ApiErrorResponse
    {
        try {
            return new ApiSuccessResponse(
                PermissionResource::collection(Permission::all()) ?? [],
                ['message' => 'return resource Permissions'],
                ResponseAlias::HTTP_ACCEPTED
            );
        } catch (Exception $e) {
            LogHelper::logError($e);

            return AfterCatchUnknown();
        }
    }

    public function createPermission(array $data): ApiSuccessResponse|ApiErrorResponse
    {
        try {
            $permission = Permission::create($data);

            return new ApiSuccessResponse(
                new PermissionResource($permission),
                ['message' => 'Permission register successfully.'],
                ResponseAlias::HTTP_ACCEPTED
            );
        } catch (Exception $e) {
            LogHelper::logError($e);

            return AfterCatchUnknown();
        }
    }

    public function getPermissionByName(string $name): void
    {
        // TODO: Implement getPermissionByName() method.
    }

    public function getPermissionById(int $id): void
    {
        // TODO: Implement getPermissionById() method.
    }

    public function updatePermission(int $id, array $data): void
    {
        // TODO: Implement updatePermission() method.
    }

    public function deletePermission(int $id): void
    {
        // TODO: Implement deletePermission() method.
    }
}
