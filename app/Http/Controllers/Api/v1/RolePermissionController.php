<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePermissionRequest;
use App\Http\Requests\StoreRoleRequest;
use App\Http\Responses\ApiErrorResponse;
use App\Http\Responses\ApiSuccessResponse;
use App\Services\PermissionService;
use Illuminate\Http\Request;

class RolePermissionController extends Controller
{
    public function __construct(protected PermissionService $permissionService)
    {
        parent::__construct();
    }

    public function getRoles(): ApiSuccessResponse|ApiErrorResponse
    {
        return $this->permissionService->getRoles();
    }

    public function storeRole(StoreRoleRequest $request): ApiSuccessResponse|ApiErrorResponse
    {
        return $this->permissionService->createRole($request->all());
    }

    public function getPermissions(): ApiErrorResponse|ApiSuccessResponse
    {
        return $this->permissionService->getPermissions();
    }

    public function storePermission(StorePermissionRequest $request): ApiErrorResponse|ApiSuccessResponse
    {
        return $this->permissionService->createPermission($request->all());
    }

    public function updateRole(Request $request, int $id): void
    {
        //
    }

    public function updatePermission(Request $request, int $id): void
    {
        //
    }

    public function deleteRole(int $id)
    {

    }

    public function deletePermission(int $id)
    {

    }
}
