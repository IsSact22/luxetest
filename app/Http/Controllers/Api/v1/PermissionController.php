<?php

namespace App\Http\Controllers\Api\v1;

use App\Contracts\PermissionRepositoryInterface;
use App\Http\Controllers\Controller;
use App\Http\Requests\StorePermissionRequest;
use App\Http\Requests\UpdatePermissionRequest;
use App\Http\Resources\PermissionResource;
use App\Http\Responses\ApiErrorResponse;
use App\Http\Responses\ApiSuccessResponse;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Symfony\Component\HttpFoundation\Response as HttpResponse;
use Throwable;

class PermissionController extends Controller
{
    public function __construct(protected PermissionRepositoryInterface $permissionRepository)
    {
        $this->middleware('auth:api');
    }

    /**
     * @OA\Get(
     *     path="/api/v1/permissions",
     *     tags={"Permissions"},
     *     summary="List all permissions",
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
     *                 @OA\Items(ref="#/components/schemas/PermissionResource")
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
            $this->authorize('viewAny', Permission::class);
            $permissions = $this->permissionRepository->getAll($request);

            return new ApiSuccessResponse(
                data: PermissionResource::collection($permissions),
                metaData: [
                    'total' => $permissions->total(),
                    'per_page' => $permissions->perPage(),
                    'current_page' => $permissions->currentPage()
                ],
                statusCode: HttpResponse::HTTP_OK
            );

        } catch (AuthorizationException $e) {
            return new ApiErrorResponse(
                exception: $e,
                message: 'Unauthorized to view permissions',
                statusCode: HttpResponse::HTTP_FORBIDDEN
            );
            
        } catch (Throwable $e) {
            return new ApiErrorResponse(
                exception: $e,
                message: 'Failed to retrieve permissions',
                statusCode: HttpResponse::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    /**
     * @OA\Post(
     *     path="/api/v1/permissions",
     *     tags={"Permissions"},
     *     summary="Create a new permission",
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/StorePermissionRequest")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Permission created successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="data",
     *                 ref="#/components/schemas/PermissionResource"
     *             ),
     *             @OA\Property(
     *                 property="meta",
     *                 type="object",
     *                 @OA\Property(property="action", type="string")
     *             )
     *         )
     *     )
     * )
     */
    public function store(StorePermissionRequest $request)
    {
        try {
            $this->authorize('create', Permission::class);
            $permission = $this->permissionRepository->newModel($request->validated());

            return new ApiSuccessResponse(
                data: new PermissionResource($permission),
                metaData: ['action' => 'created'],
                statusCode: HttpResponse::HTTP_CREATED
            );

        } catch (AuthorizationException $e) {
            return new ApiErrorResponse(
                exception: $e,
                message: 'Unauthorized to create permission',
                statusCode: HttpResponse::HTTP_FORBIDDEN
            );
            
        } catch (Throwable $e) {
            return new ApiErrorResponse(
                exception: $e,
                message: 'Failed to create permission',
                statusCode: HttpResponse::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    /**
     * @OA\Get(
     *     path="/api/v1/permissions/{id}",
     *     tags={"Permissions"},
     *     summary="Get permission details",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="data",
     *                 ref="#/components/schemas/PermissionResource"
     *             ),
     *             @OA\Property(
     *                 property="meta",
     *                 type="object",
     *                 @OA\Property(property="fetched", type="string")
     *             )
     *         )
     *     )
     * )
     */
    public function show(int $id)
    {
        try {
            $permission = $this->permissionRepository->getById($id);
            $this->authorize('view', $permission);

            return new ApiSuccessResponse(
                data: new PermissionResource($permission),
                metaData: ['fetched' => now()->toDateTimeString()],
                statusCode: HttpResponse::HTTP_OK
            );

        } catch (ModelNotFoundException $e) {
            return new ApiErrorResponse(
                exception: $e,
                message: 'Permission not found',
                statusCode: HttpResponse::HTTP_NOT_FOUND
            );
            
        } catch (AuthorizationException $e) {
            return new ApiErrorResponse(
                exception: $e,
                message: 'Unauthorized to view this permission',
                statusCode: HttpResponse::HTTP_FORBIDDEN
            );
            
        } catch (Throwable $e) {
            return new ApiErrorResponse(
                exception: $e,
                message: 'Failed to retrieve permission',
                statusCode: HttpResponse::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    /**
     * @OA\Put(
     *     path="/api/v1/permissions/{id}",
     *     tags={"Permissions"},
     *     summary="Update permission",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/UpdatePermissionRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Permission updated successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="data",
     *                 ref="#/components/schemas/PermissionResource"
     *             ),
     *             @OA\Property(
     *                 property="meta",
     *                 type="object",
     *                 @OA\Property(property="action", type="string"),
     *                 @OA\Property(property="updated_at", type="string")
     *             )
     *         )
     *     )
     * )
     */
    public function update(UpdatePermissionRequest $request, int $id)
    {
        try {
            $permission = $this->permissionRepository->getById($id);
            $this->authorize('update', $permission);
            
            $updatedPermission = $this->permissionRepository->updateModel($request->validated(), $id);

            return new ApiSuccessResponse(
                data: new PermissionResource($updatedPermission),
                metaData: [
                    'action' => 'updated',
                    'updated_at' => now()->toDateTimeString()
                ],
                statusCode: HttpResponse::HTTP_OK
            );

        } catch (ModelNotFoundException $e) {
            return new ApiErrorResponse(
                exception: $e,
                message: 'Permission not found for update',
                statusCode: HttpResponse::HTTP_NOT_FOUND
            );
            
        } catch (AuthorizationException $e) {
            return new ApiErrorResponse(
                exception: $e,
                message: 'Unauthorized to update this permission',
                statusCode: HttpResponse::HTTP_FORBIDDEN
            );
            
        } catch (Throwable $e) {
            return new ApiErrorResponse(
                exception: $e,
                message: 'Failed to update permission',
                statusCode: HttpResponse::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    /**
     * @OA\Delete(
     *     path="/api/v1/permissions/{id}",
     *     tags={"Permissions"},
     *     summary="Delete permission",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Permission deleted successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="data", type="null"),
     *             @OA\Property(
     *                 property="meta",
     *                 type="object",
     *                 @OA\Property(property="action", type="string"),
     *                 @OA\Property(property="deleted_at", type="string")
     *             )
     *         )
     *     )
     * )
     */
    public function destroy(int $id)
    {
        try {
            $permission = $this->permissionRepository->getById($id);
            $this->authorize('delete', $permission);
            
            $this->permissionRepository->deleteModel($id);

            return new ApiSuccessResponse(
                data: null,
                metaData: [
                    'action' => 'deleted',
                    'deleted_at' => now()->toDateTimeString()
                ],
                statusCode: HttpResponse::HTTP_NO_CONTENT
            );

        } catch (ModelNotFoundException $e) {
            return new ApiErrorResponse(
                exception: $e,
                message: 'Permission not found for deletion',
                statusCode: HttpResponse::HTTP_NOT_FOUND
            );
            
        } catch (AuthorizationException $e) {
            return new ApiErrorResponse(
                exception: $e,
                message: 'Unauthorized to delete this permission',
                statusCode: HttpResponse::HTTP_FORBIDDEN
            );
            
        } catch (Throwable $e) {
            return new ApiErrorResponse(
                exception: $e,
                message: 'Failed to delete permission',
                statusCode: HttpResponse::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }
}