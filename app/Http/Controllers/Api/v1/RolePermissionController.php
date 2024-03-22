<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePermissionRequest;
use App\Http\Requests\StoreRoleRequest;
use App\Http\Requests\UpdatePermissionRequest;
use App\Http\Requests\UpdateRoleRequest;
use App\Http\Resources\PermissionResource;
use App\Http\Resources\RoleResource;
use App\Http\Responses\ApiErrorResponse;
use App\Http\Responses\ApiSuccessResponse;
use App\Repositories\PermissionService;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class RolePermissionController extends Controller
{
    public function __construct(protected PermissionService $permissionService)
    {
        parent::__construct();
    }

    public function getRoles(Request $request): AnonymousResourceCollection
    {
        $roles = $this->permissionService->getRoles($request);

        return RoleResource::collection($roles);
    }

    public function storeRole(StoreRoleRequest $request): RoleResource
    {
        $role = $this->permissionService->createRole($request->all());

        return new RoleResource($role);
    }

    public function updateRole(UpdateRoleRequest $request, int $id): RoleResource
    {
        $role = $this->permissionService->updateRole($request->all(), $id);

        return new RoleResource($role);
    }

    public function deleteRole(int $id): ApiSuccessResponse|ApiErrorResponse
    {
        return $this->permissionService->deleteRole($id);
    }

    public function getPermissions(Request $request): AnonymousResourceCollection
    {
        $permissions = $this->permissionService->getPermissions($request);

        return PermissionResource::collection($permissions);
    }

    public function storePermission(StorePermissionRequest $request): PermissionResource
    {
        $permission = $this->permissionService->createPermission($request->all());

        return new PermissionResource($permission);
    }

    public function updatePermission(UpdatePermissionRequest $request, int $id): PermissionResource
    {
        $permission = $this->permissionService->updatePermission($request, $id);

        return new PermissionResource($permission);
    }

    public function deletePermission(int $id): bool|ApiErrorResponse
    {
        return $this->permissionService->deletePermission($id);
    }

    public function assignPermissions(Request $request, int $id, string $guard): RoleResource
    {
        $role = $this->permissionService->assignPermissions($request->all(), $id, $guard);

        return new RoleResource($role);
    }

    public function revokePermissions(Request $request, int $id): RoleResource
    {
        $role = $this->permissionService->revokePermissions($request, $id);

        return new RoleResource($role);
    }
}
