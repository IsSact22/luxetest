<?php

namespace App\Services;

use App\Helpers\HasUpdated;
use App\Helpers\LogHelper;
use App\Http\Responses\ApiErrorResponse;
use App\Interfaces\PermissionServiceInterface;
use App\Models\User;
use App\Traits\ModelNotFoundExceptionData;
use App\Traits\QueryExceptionDataTrait;
use Exception;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Spatie\Permission\Exceptions\PermissionAlreadyExists;
use Spatie\Permission\Exceptions\PermissionDoesNotExist;
use Spatie\Permission\Exceptions\RoleDoesNotExist;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

use function App\Helpers\AfterCatchUnknown;

class PermissionService implements PermissionServiceInterface
{
    use ModelNotFoundExceptionData;
    use QueryExceptionDataTrait;

    protected User $user;

    protected Role $role;

    protected Permission $permission;

    /**
     * Obtener todos los roles.
     */
    public function getRoles(Request $request): LengthAwarePaginator|ApiErrorResponse
    {
        try {
            $roles = Role::query()
                ->when($request->get('search'), function ($query, string $search) {
                    $query->where('id', $search)
                        ->orWhere('name', 'LIKE', $search.'%');
                })
                ->paginate()
                ->withQueryString();

            if ($request->has('search') && $roles->isEmpty()) {
                throw new RoleDoesNotExist('Role dont exist');
            }

            return $roles;
        } catch (RoleDoesNotExist $e) {
            return new ApiErrorResponse(
                $e,
                $e->getMessage(),
                ResponseAlias::HTTP_NOT_FOUND
            );

        } catch (Exception $e) {
            LogHelper::logError($e);

            return AfterCatchUnknown();
        }
    }

    /**
     * Crear un nuevo rol.
     */
    public function createRole(array $data): \Spatie\Permission\Contracts\Role|Role|ApiErrorResponse
    {
        try {
            return Role::create($data);
        } catch (QueryException $e) {
            return $this->getQueryExceptionData($e);
        } catch (Exception $e) {
            LogHelper::logError($e);

            return AfterCatchUnknown();
        }
    }

    /**
     * Actualizar un rol existente.
     */
    public function updateRole(array $data, int $id): array|ApiErrorResponse
    {
        try {
            $role = Role::findOrFail($id);
            $role->update($data);

            return HasUpdated::getModel($role);

        } catch (ModelNotFoundException $e) {
            return $this->getModelNotFoundExceptionData($e);
        } catch (QueryException $e) {
            return $this->getQueryExceptionData($e);
        } catch (Exception $e) {
            LogHelper::logError($e);

            return AfterCatchUnknown();
        }
    }

    /**
     * Eliminar un rol por su ID.
     */
    public function deleteRole(int $id): bool|ApiErrorResponse
    {
        try {
            $role = Role::findOrFail($id);
            $role->delete();

            return true;
        } catch (ModelNotFoundException $e) {
            return $this->getModelNotFoundExceptionData($e);
        } catch (QueryException $e) {
            return $this->getQueryExceptionData($e);
        } catch (Exception $e) {
            LogHelper::logError($e);

            return AfterCatchUnknown();
        }

    }

    public function getPermissions(Request $request): LengthAwarePaginator|ApiErrorResponse
    {
        try {
            $permissions = Permission::query()
                ->when($request->get('search'), function ($query, string $search) {
                    $query->where('id', $search)
                        ->orWhere('name', 'LIKE', $search.'%');
                })
                ->paginate()
                ->withQueryString();
            if ($request->has('search') && $permissions->isEmpty()) {
                throw new PermissionDoesNotExist('Permission dont exist');
            }

            return $permissions;
        } catch (PermissionDoesNotExist $e) {
            return new ApiErrorResponse(
                $e,
                $e->getMessage(),
                ResponseAlias::HTTP_NOT_FOUND
            );
        } catch (Exception $e) {
            LogHelper::logError($e);

            return AfterCatchUnknown();
        }
    }

    public function createPermission(array $data): \Spatie\Permission\Contracts\Permission|Permission|ApiErrorResponse
    {
        try {
            return Permission::create($data);
        } catch (PermissionAlreadyExists $e) {
            return new ApiErrorResponse(
                $e,
                $e->getMessage(),
                ResponseAlias::HTTP_CONFLICT
            );
        } catch (Exception $e) {
            LogHelper::logError($e);

            return AfterCatchUnknown();
        }
    }

    public function updatePermission(Request $request, int $id): array|ApiErrorResponse
    {
        try {
            $permission = Permission::findOrFail($id);
            $permission->update($request->all());

            return HasUpdated::getModel($permission);

        } catch (ModelNotFoundException $e) {
            return $this->getModelNotFoundExceptionData($e);
        } catch (QueryException $e) {
            return $this->getQueryExceptionData($e);
        } catch (Exception $e) {
            LogHelper::logError($e);

            return AfterCatchUnknown();
        }
    }

    public function deletePermission(int $id): bool|ApiErrorResponse
    {
        try {
            $permission = Permission::findOrFail($id);
            $permission->delete();

            return true;
        } catch (ModelNotFoundException $e) {
            return $this->getModelNotFoundExceptionData($e);
        } catch (QueryException $e) {
            return $this->getQueryExceptionData($e);
        } catch (Exception $e) {
            LogHelper::logError($e);

            return AfterCatchUnknown();
        }
    }
}
