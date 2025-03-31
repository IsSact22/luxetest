<?php

namespace App\Http\Controllers\Api\v1;

use App\Contracts\ModelAircraftRepositoryInterface;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreModelAircraftRequest;
use App\Http\Requests\UpdateModelAircraftRequest;
use App\Http\Resources\ModelAircraftResource;
use App\Http\Responses\ApiErrorResponse;
use App\Http\Responses\ApiSuccessResponse;
use App\Models\ModelAircraft;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response as HttpResponse;
use Throwable;

class ModelAircraftController extends Controller
{
    public function __construct(protected ModelAircraftRepositoryInterface $modelAircraftRepository)
    {
        $this->middleware('auth:api');
    }

    /**
     * @OA\Get(
     *     path="/api/v1/model-aircrafts",
     *     tags={"Model Aircrafts"},
     *     summary="List all aircraft models",
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/ModelAircraftResource")),
     *             @OA\Property(property="metaData", type="object")
     *         )
     *     )
     * )
     */
    public function index(Request $request)
    {
        try {
            $this->authorize('viewAny', ModelAircraft::class);
            $models = $this->modelAircraftRepository->getAll($request);

            return new ApiSuccessResponse(
                data: ModelAircraftResource::collection($models),
                metaData: [
                    'total' => $models->total(),
                    'per_page' => $models->perPage(),
                    'current_page' => $models->currentPage()
                ],
                statusCode: HttpResponse::HTTP_OK
            );

        } catch (AuthorizationException $e) {
            return new ApiErrorResponse(
                exception: $e,
                message: 'Unauthorized to view aircraft models',
                statusCode: HttpResponse::HTTP_FORBIDDEN
            );

        } catch (Throwable $e) {
            return new ApiErrorResponse(
                exception: $e,
                message: 'Failed to retrieve aircraft models',
                statusCode: HttpResponse::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    /**
     * @OA\Post(
     *     path="/api/v1/model-aircrafts",
     *     tags={"Model Aircrafts"},
     *     summary="Create a new aircraft model",
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/StoreModelAircraftRequest")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Aircraft model created successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="data", ref="#/components/schemas/ModelAircraftResource"),
     *             @OA\Property(property="metaData", type="object")
     *         )
     *     )
     * )
     */
    public function store(StoreModelAircraftRequest $request)
    {
        try {
            $this->authorize('create', ModelAircraft::class);
            $model = $this->modelAircraftRepository->newModelAircraft($request->validated());

            return new ApiSuccessResponse(
                data: new ModelAircraftResource($model),
                metaData: ['action' => 'created'],
                statusCode: HttpResponse::HTTP_CREATED
            );

        } catch (AuthorizationException $e) {
            return new ApiErrorResponse(
                exception: $e,
                message: 'Unauthorized to create aircraft model',
                statusCode: HttpResponse::HTTP_FORBIDDEN
            );

        } catch (Throwable $e) {
            return new ApiErrorResponse(
                exception: $e,
                message: 'Failed to create aircraft model',
                statusCode: HttpResponse::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    /**
     * @OA\Get(
     *     path="/api/v1/model-aircrafts/{id}",
     *     tags={"Model Aircrafts"},
     *     summary="Get aircraft model details",
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
     *             @OA\Property(property="data", ref="#/components/schemas/ModelAircraftResource"),
     *             @OA\Property(property="metaData", type="object")
     *         )
     *     )
     * )
     */
    public function show(int $id)
    {
        try {
            $model = $this->modelAircraftRepository->getById($id);
            $this->authorize('view', $model);

            return new ApiSuccessResponse(
                data: new ModelAircraftResource($model),
                metaData: ['fetched' => now()->toDateTimeString()],
                statusCode: HttpResponse::HTTP_OK
            );

        } catch (ModelNotFoundException $e) {
            return new ApiErrorResponse(
                exception: $e,
                message: 'Aircraft model not found',
                statusCode: HttpResponse::HTTP_NOT_FOUND
            );

        } catch (AuthorizationException $e) {
            return new ApiErrorResponse(
                exception: $e,
                message: 'Unauthorized to view this aircraft model',
                statusCode: HttpResponse::HTTP_FORBIDDEN
            );

        } catch (Throwable $e) {
            return new ApiErrorResponse(
                exception: $e,
                message: 'Failed to retrieve aircraft model',
                statusCode: HttpResponse::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    /**
     * @OA\Put(
     *     path="/api/v1/model-aircrafts/{id}",
     *     tags={"Model Aircrafts"},
     *     summary="Update aircraft model",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/UpdateModelAircraftRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Aircraft model updated successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="data", ref="#/components/schemas/ModelAircraftResource"),
     *             @OA\Property(property="metaData", type="object")
     *         )
     *     )
     * )
     */
    public function update(UpdateModelAircraftRequest $request, int $id)
    {
        try {
            $model = $this->modelAircraftRepository->getById($id);
            $this->authorize('update', $model);

            $updatedModel = $this->modelAircraftRepository->updateModelAircraft($request->validated(), $id);

            return new ApiSuccessResponse(
                data: new ModelAircraftResource($updatedModel),
                metaData: ['action' => 'updated', 'at' => now()->toDateTimeString()],
                statusCode: HttpResponse::HTTP_OK
            );

        } catch (ModelNotFoundException $e) {
            return new ApiErrorResponse(
                exception: $e,
                message: 'Aircraft model not found for update',
                statusCode: HttpResponse::HTTP_NOT_FOUND
            );

        } catch (AuthorizationException $e) {
            return new ApiErrorResponse(
                exception: $e,
                message: 'Unauthorized to update this aircraft model',
                statusCode: HttpResponse::HTTP_FORBIDDEN
            );

        } catch (Throwable $e) {
            return new ApiErrorResponse(
                exception: $e,
                message: 'Failed to update aircraft model',
                statusCode: HttpResponse::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    /**
     * @OA\Delete(
     *     path="/api/v1/model-aircrafts/{id}",
     *     tags={"Model Aircrafts"},
     *     summary="Delete aircraft model",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Aircraft model deleted successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="data", type="null"),
     *             @OA\Property(property="metaData", type="object")
     *         )
     *     )
     * )
     */
    public function destroy(int $id)
    {
        try {
            $model = $this->modelAircraftRepository->getById($id);
            $this->authorize('delete', $model);

            $this->modelAircraftRepository->deleteModelAircraft($id);

            return new ApiSuccessResponse(
                data: null,
                metaData: ['action' => 'deleted', 'at' => now()->toDateTimeString()],
                statusCode: HttpResponse::HTTP_NO_CONTENT
            );

        } catch (ModelNotFoundException $e) {
            return new ApiErrorResponse(
                exception: $e,
                message: 'Aircraft model not found for deletion',
                statusCode: HttpResponse::HTTP_NOT_FOUND
            );

        } catch (AuthorizationException $e) {
            return new ApiErrorResponse(
                exception: $e,
                message: 'Unauthorized to delete this aircraft model',
                statusCode: HttpResponse::HTTP_FORBIDDEN
            );

        } catch (Throwable $e) {
            return new ApiErrorResponse(
                exception: $e,
                message: 'Failed to delete aircraft model',
                statusCode: HttpResponse::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }
}
