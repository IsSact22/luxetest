<?php

namespace App\Repositories;

use App\Contracts\PermissionServiceInterface;
use App\Helpers\HasUpdated;
use App\Helpers\LogHelper;
use App\Http\Responses\ApiErrorResponse;
use App\Models\User;
use App\Traits\ModelNotFoundExceptionData;
use App\Traits\QueryExceptionDataTrait;
use ErrorException;
use Exception;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Override;
use Spatie\Permission\Exceptions\PermissionAlreadyExists;
use Spatie\Permission\Exceptions\PermissionDoesNotExist;
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

    #[Override]
    public function getRoles(Request $request): LengthAwarePaginator|ApiErrorResponse
    {
        try {
            return Role::query()
                ->when($request->get('search'), function ($query, string $search) {
                    $query->where('id', $search)
                        ->orWhere('name', 'LIKE', $search.'%');
                })
                ->paginate()
                ->withQueryString();

        } catch (Exception $e) {
            LogHelper::logError($e);

            return AfterCatchUnknown();
        }
    }

    #[Override]
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

    #[Override]
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

    #[Override]
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

    #[Override]
    public function getPermissions(Request $request): LengthAwarePaginator|ApiErrorResponse
    {
        try {
            return Permission::query()
                ->when($request->get('search'), function ($query, string $search) {
                    $query->where('id', $search)
                        ->orWhere('name', 'LIKE', $search.'%');
                })
                ->paginate()
                ->withQueryString();

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

    #[Override]
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

    #[Override]
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

    #[Override]
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

    #[Override]
    public function assignPermissions(array $permissions, int $id, string $guard)
    {
        try {
            $role = Role::findById($id, $guard);
            $role->syncPermissions($permissions);

            return $role;
        } catch (ErrorException $e) {
            return new ApiErrorResponse($e, $e->getMessage());
        } catch (Exception $e) {
            LogHelper::logError($e);

            return AfterCatchUnknown();
        }

    }

    #[Override]
    public function revokePermissions(array $permissions, int $id, string $guard)
    {
        try {
            $role = Role::findById($id);
            $role->revokePermissionTo($permissions);

            return $role;
        } catch (ErrorException $e) {
            return new ApiErrorResponse($e, $e->getMessage());
        } catch (Exception $e) {
            LogHelper::logError($e);

            return AfterCatchUnknown();
        }
    }
}
