<?php

namespace App\Http\Controllers\Api\v1;

use App\Contracts\RoleRepositoryInterface;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreRoleRequest;
use App\Http\Requests\UpdateRoleRequest;
use App\Http\Resources\RoleResource;
use App\Http\Responses\ApiErrorResponse;
use App\Http\Responses\ApiSuccessResponse;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Symfony\Component\HttpFoundation\Response as HttpResponse;
use Throwable;

class RoleController extends Controller
{
    public function __construct(protected RoleRepositoryInterface $roleRepository)
    {
        $this->middleware('auth:api');
        $this->authorizeResource(Role::class, 'role');
    }

    /**
     * @OA\Get(
     *     path="/api/v1/roles",
     *     tags={"Roles"},
     *     summary="List all roles",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="page",
     *         in="query",
     *         description="Page number",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="per_page",
     *         in="query",
     *         description="Items per page",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(ref="#/components/schemas/RoleResource")
     *             ),
     *             @OA\Property(
     *                 property="meta",
     *                 type="object",
     *                 @OA\Property(property="total", type="integer"),
     *                 @OA\Property(property="per_page", type="integer"),
     *                 @OA\Property(property="current_page", type="integer")
     *             )
     *         )
     *     )
     * )
     */
    public function index(Request $request)
    {
        try {
            $roles = $this->roleRepository->getAll($request);

            return new ApiSuccessResponse(
                data: RoleResource::collection($roles),
                metaData: [
                    'total' => $roles->total(),
                    'per_page' => $roles->perPage(),
                    'current_page' => $roles->currentPage()
                ],
                statusCode: HttpResponse::HTTP_OK
            );

        } catch (Throwable $e) {
            return new ApiErrorResponse(
                exception: $e,
                message: 'Failed to retrieve roles',
                statusCode: HttpResponse::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    /**
     * @OA\Post(
     *     path="/api/v1/roles",
     *     tags={"Roles"},
     *     summary="Create a new role",
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/StoreRoleRequest")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Role created successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="data",
     *                 ref="#/components/schemas/RoleResource"
     *             ),
     *             @OA\Property(
     *                 property="meta",
     *                 type="object",
     *                 @OA\Property(property="message", type="string")
     *             )
     *         )
     *     )
     * )
     */
    public function store(StoreRoleRequest $request)
    {
        try {
            $payload = $request->validated();
            $role = $this->roleRepository->newModel($payload);
            $role->syncPermissions($payload['permissions']);

            return new ApiSuccessResponse(
                data: new RoleResource($role),
                metaData: ['message' => 'Role created successfully'],
                statusCode: HttpResponse::HTTP_CREATED
            );

        } catch (AuthorizationException $e) {
            return new ApiErrorResponse(
                exception: $e,
                message: 'Unauthorized to create role',
                statusCode: HttpResponse::HTTP_FORBIDDEN
            );
            
        } catch (Throwable $e) {
            return new ApiErrorResponse(
                exception: $e,
                message: 'Failed to create role',
                statusCode: HttpResponse::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    /**
     * @OA\Get(
     *     path="/api/v1/roles/{id}",
     *     tags={"Roles"},
     *     summary="Get role details",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="data",
     *                 ref="#/components/schemas/RoleResource"
     *             )
     *         )
     *     )
     * )
     */
    public function show(string $id)
    {
        try {
            $role = $this->roleRepository->getById($id);

            return new ApiSuccessResponse(
                data: new RoleResource($role),
                statusCode: HttpResponse::HTTP_OK
            );

        } catch (ModelNotFoundException $e) {
            return new ApiErrorResponse(
                exception: $e,
                message: 'Role not found',
                statusCode: HttpResponse::HTTP_NOT_FOUND
            );
            
        } catch (Throwable $e) {
            return new ApiErrorResponse(
                exception: $e,
                message: 'Failed to retrieve role',
                statusCode: HttpResponse::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    /**
     * @OA\Put(
     *     path="/api/v1/roles/{id}",
     *     tags={"Roles"},
     *     summary="Update role",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/UpdateRoleRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Role updated successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="data",
     *                 ref="#/components/schemas/RoleResource"
     *             ),
     *             @OA\Property(
     *                 property="meta",
     *                 type="object",
     *                 @OA\Property(property="message", type="string")
     *             )
     *         )
     *     )
     * )
     */
    public function update(UpdateRoleRequest $request, string $id)
    {
        try {
            $payload = $request->validated();
            $role = $this->roleRepository->updateModel($payload, $id);
            $role->syncPermissions($payload['permissions']);

            return new ApiSuccessResponse(
                data: new RoleResource($role),
                metaData: ['message' => 'Role updated successfully'],
                statusCode: HttpResponse::HTTP_OK
            );

        } catch (ModelNotFoundException $e) {
            return new ApiErrorResponse(
                exception: $e,
                message: 'Role not found',
                statusCode: HttpResponse::HTTP_NOT_FOUND
            );
            
        } catch (AuthorizationException $e) {
            return new ApiErrorResponse(
                exception: $e,
                message: 'Unauthorized to update role',
                statusCode: HttpResponse::HTTP_FORBIDDEN
            );
            
        } catch (Throwable $e) {
            return new ApiErrorResponse(
                exception: $e,
                message: 'Failed to update role',
                statusCode: HttpResponse::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    /**
     * @OA\Delete(
     *     path="/api/v1/roles/{id}",
     *     tags={"Roles"},
     *     summary="Delete role",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Role deleted successfully"
     *     )
     * )
     */
    public function destroy(string $id)
    {
        try {
            $this->roleRepository->deleteModel($id);

            return new ApiSuccessResponse(
                data: null,
                metaData: ['message' => 'Role deleted successfully'],
                statusCode: HttpResponse::HTTP_NO_CONTENT
            );

        } catch (ModelNotFoundException $e) {
            return new ApiErrorResponse(
                exception: $e,
                message: 'Role not found',
                statusCode: HttpResponse::HTTP_NOT_FOUND
            );
            
        } catch (AuthorizationException $e) {
            return new ApiErrorResponse(
                exception: $e,
                message: 'Unauthorized to delete role',
                statusCode: HttpResponse::HTTP_FORBIDDEN
            );
            
        } catch (Throwable $e) {
            return new ApiErrorResponse(
                exception: $e,
                message: 'Failed to delete role',
                statusCode: HttpResponse::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }
}