<?php

namespace App\Http\Controllers\Api\v1;

use App\Contracts\EngineTypeRepositoryInterface;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreEngineTypeRequest;
use App\Http\Requests\UpdateEngineTypeRequest;
use App\Http\Resources\EngineTypeResource;
use App\Http\Responses\ApiErrorResponse;
use App\Http\Responses\ApiSuccessResponse;
use App\Models\EngineType;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response as HttpResponse;
use Throwable;

class EngineTypeController extends Controller
{
    public function __construct(protected EngineTypeRepositoryInterface $engineTypeRepository)
    {
        $this->middleware('auth:api');
    }

    /**
     * @OA\Get(
     *     path="/api/v1/engine-types",
     *     tags={"Engine Types"},
     *     summary="List all engine types",
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
     *                 @OA\Items(ref="#/components/schemas/EngineTypeResource")
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
            $this->authorize('viewAny', EngineType::class);
            $engineTypes = $this->engineTypeRepository->getAll($request);

            return new ApiSuccessResponse(
                data: EngineTypeResource::collection($engineTypes),
                metaData: [
                    'total' => $engineTypes->total(),
                    'per_page' => $engineTypes->perPage(),
                    'current_page' => $engineTypes->currentPage()
                ],
                statusCode: HttpResponse::HTTP_OK
            );

        } catch (AuthorizationException $e) {
            return new ApiErrorResponse(
                exception: $e,
                message: 'Unauthorized to view engine types',
                statusCode: HttpResponse::HTTP_FORBIDDEN
            );

        } catch (Throwable $e) {
            return new ApiErrorResponse(
                exception: $e,
                message: 'Failed to retrieve engine types',
                statusCode: HttpResponse::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    /**
     * @OA\Post(
     *     path="/api/v1/engine-types",
     *     tags={"Engine Types"},
     *     summary="Create a new engine type",
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/StoreEngineTypeRequest")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Engine type created successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="data",
     *                 ref="#/components/schemas/EngineTypeResource"
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
    public function store(StoreEngineTypeRequest $request)
    {
        try {
            $this->authorize('create', EngineType::class);
            $engineType = $this->engineTypeRepository->newEngineType($request->validated());

            return new ApiSuccessResponse(
                data: new EngineTypeResource($engineType),
                metaData: ['action' => 'created'],
                statusCode: HttpResponse::HTTP_CREATED
            );

        } catch (AuthorizationException $e) {
            return new ApiErrorResponse(
                exception: $e,
                message: 'Unauthorized to create engine type',
                statusCode: HttpResponse::HTTP_FORBIDDEN
            );

        } catch (Throwable $e) {
            return new ApiErrorResponse(
                exception: $e,
                message: 'Failed to create engine type',
                statusCode: HttpResponse::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    /**
     * @OA\Get(
     *     path="/api/v1/engine-types/{id}",
     *     tags={"Engine Types"},
     *     summary="Get engine type details",
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
     *                 ref="#/components/schemas/EngineTypeResource"
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
            $engineType = $this->engineTypeRepository->getById($id);
            $this->authorize('view', $engineType);

            return new ApiSuccessResponse(
                data: new EngineTypeResource($engineType),
                metaData: ['fetched' => now()->toDateTimeString()],
                statusCode: HttpResponse::HTTP_OK
            );

        } catch (ModelNotFoundException $e) {
            return new ApiErrorResponse(
                exception: $e,
                message: 'Engine type not found',
                statusCode: HttpResponse::HTTP_NOT_FOUND
            );

        } catch (AuthorizationException $e) {
            return new ApiErrorResponse(
                exception: $e,
                message: 'Unauthorized to view this engine type',
                statusCode: HttpResponse::HTTP_FORBIDDEN
            );

        } catch (Throwable $e) {
            return new ApiErrorResponse(
                exception: $e,
                message: 'Failed to retrieve engine type',
                statusCode: HttpResponse::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    /**
     * @OA\Put(
     *     path="/api/v1/engine-types/{id}",
     *     tags={"Engine Types"},
     *     summary="Update engine type",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/UpdateEngineTypeRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Engine type updated successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="data",
     *                 ref="#/components/schemas/EngineTypeResource"
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
    public function update(UpdateEngineTypeRequest $request, int $id)
    {
        try {
            $engineType = $this->engineTypeRepository->getById($id);
            $this->authorize('update', $engineType);

            $updatedEngineType = $this->engineTypeRepository->updateEngineType($request->validated(), $id);

            return new ApiSuccessResponse(
                data: new EngineTypeResource($updatedEngineType),
                metaData: [
                    'action' => 'updated',
                    'updated_at' => now()->toDateTimeString()
                ],
                statusCode: HttpResponse::HTTP_OK
            );

        } catch (ModelNotFoundException $e) {
            return new ApiErrorResponse(
                exception: $e,
                message: 'Engine type not found for update',
                statusCode: HttpResponse::HTTP_NOT_FOUND
            );

        } catch (AuthorizationException $e) {
            return new ApiErrorResponse(
                exception: $e,
                message: 'Unauthorized to update this engine type',
                statusCode: HttpResponse::HTTP_FORBIDDEN
            );

        } catch (Throwable $e) {
            return new ApiErrorResponse(
                exception: $e,
                message: 'Failed to update engine type',
                statusCode: HttpResponse::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    /**
     * @OA\Delete(
     *     path="/api/v1/engine-types/{id}",
     *     tags={"Engine Types"},
     *     summary="Delete engine type",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Engine type deleted successfully",
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
            $engineType = $this->engineTypeRepository->getById($id);
            $this->authorize('delete', $engineType);

            $this->engineTypeRepository->deleteEngineType($id);

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
                message: 'Engine type not found for deletion',
                statusCode: HttpResponse::HTTP_NOT_FOUND
            );

        } catch (AuthorizationException $e) {
            return new ApiErrorResponse(
                exception: $e,
                message: 'Unauthorized to delete this engine type',
                statusCode: HttpResponse::HTTP_FORBIDDEN
            );

        } catch (Throwable $e) {
            return new ApiErrorResponse(
                exception: $e,
                message: 'Failed to delete engine type',
                statusCode: HttpResponse::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }
}
