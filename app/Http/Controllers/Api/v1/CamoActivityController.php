<?php

namespace App\Http\Controllers\Api\v1;

use App\Exceptions\RepositoryException;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCamoActivityRequest;
use App\Http\Requests\UpdateCamoActivityRequest;
use App\Http\Resources\CamoActivityResource;
use App\Http\Resources\CamoResource;
use App\Http\Responses\ApiErrorResponse;
use App\Http\Responses\ApiSuccessResponse;
use App\Models\Camo;
use App\Models\CamoActivity;
use App\Repositories\CamoActivityRepository;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response as HttpResponse;
use Throwable;

class CamoActivityController extends Controller
{
    public function __construct(protected CamoActivityRepository $activity)
    {
        $this->middleware('auth:api');
    }

    /**
     * @OA\Get(
     *     path="/api/v1/camo-activities",
     *     tags={"CAMO Activities"},
     *     summary="List all CAMO activities",
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/CamoActivityResource")),
     *             @OA\Property(property="metaData", type="object")
     *         )
     *     )
     * )
     */
    public function index(Request $request)
    {
        try {
            $this->authorize('viewAny', CamoActivity::class);
            $activities = $this->activity->getAll($request);

            return new ApiSuccessResponse(
                data: CamoActivityResource::collection($activities),
                metaData: [
                    'total' => $activities->total(),
                    'per_page' => $activities->perPage(),
                    'current_page' => $activities->currentPage()
                ],
                statusCode: HttpResponse::HTTP_OK
            );

        } catch (AuthorizationException $e) {
            return new ApiErrorResponse(
                exception: $e,
                message: 'Unauthorized to view CAMO activities',
                statusCode: HttpResponse::HTTP_FORBIDDEN
            );

        } catch (Throwable $e) {
            return new ApiErrorResponse(
                exception: $e,
                message: 'Failed to retrieve CAMO activities',
                statusCode: HttpResponse::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    /**
     * @OA\Post(
     *     path="/api/v1/camo-activities",
     *     tags={"CAMO Activities"},
     *     summary="Create a new CAMO activity",
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/StoreCamoActivityRequest")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="CAMO activity created successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="data", ref="#/components/schemas/CamoActivityResource"),
     *             @OA\Property(property="metaData", type="object")
     *         )
     *     )
     * )
     */
    public function store(StoreCamoActivityRequest $request)
    {
        try {
            $this->authorize('create', CamoActivity::class);
            $activity = $this->activity->newModel($request->validated());

            return new ApiSuccessResponse(
                data: new CamoActivityResource($activity),
                metaData: ['action' => 'created', 'camo_id' => $activity->camo_id],
                statusCode: HttpResponse::HTTP_CREATED
            );

        } catch (AuthorizationException $e) {
            return new ApiErrorResponse(
                exception: $e,
                message: 'Unauthorized to create CAMO activity',
                statusCode: HttpResponse::HTTP_FORBIDDEN
            );

        } catch (Throwable $e) {
            return new ApiErrorResponse(
                exception: $e,
                message: 'Failed to create CAMO activity',
                statusCode: HttpResponse::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    /**
     * @OA\Get(
     *     path="/api/v1/camo-activities/{id}",
     *     tags={"CAMO Activities"},
     *     summary="Get CAMO activity details",
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
     *             @OA\Property(property="data", ref="#/components/schemas/CamoActivityResource"),
     *             @OA\Property(property="metaData", type="object")
     *         )
     *     )
     * )
     */
    public function show(string $id)
    {
        try {
            $camoActivity = $this->activity->getById($id);
            $this->authorize('view', $camoActivity);

            return new ApiSuccessResponse(
                data: new CamoActivityResource($camoActivity),
                metaData: ['camo_id' => $camoActivity->camo_id],
                statusCode: HttpResponse::HTTP_OK
            );

        } catch (ModelNotFoundException $e) {
            return new ApiErrorResponse(
                exception: $e,
                message: 'CAMO activity not found',
                statusCode: HttpResponse::HTTP_NOT_FOUND
            );

        } catch (AuthorizationException $e) {
            return new ApiErrorResponse(
                exception: $e,
                message: 'Unauthorized to view this CAMO activity',
                statusCode: HttpResponse::HTTP_FORBIDDEN
            );

        } catch (Throwable $e) {
            return new ApiErrorResponse(
                exception: $e,
                message: 'Failed to retrieve CAMO activity',
                statusCode: HttpResponse::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    /**
     * @OA\Put(
     *     path="/api/v1/camo-activities/{id}",
     *     tags={"CAMO Activities"},
     *     summary="Update CAMO activity",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/UpdateCamoActivityRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="CAMO activity updated successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="data", ref="#/components/schemas/CamoActivityResource"),
     *             @OA\Property(property="metaData", type="object")
     *         )
     *     )
     * )
     */
    public function update(UpdateCamoActivityRequest $request, string $id)
    {
        try {
            $camoActivity = $this->activity->getById($id);
            $this->authorize('update', $camoActivity);

            $updatedActivity = $this->activity->updateModel($request->validated(), $id);

            return new ApiSuccessResponse(
                data: new CamoActivityResource($updatedActivity),
                metaData: [
                    'action' => 'updated',
                    'camo_id' => $updatedActivity->camo_id,
                    'updated_at' => now()->toDateTimeString()
                ],
                statusCode: HttpResponse::HTTP_OK
            );

        } catch (ModelNotFoundException $e) {
            return new ApiErrorResponse(
                exception: $e,
                message: 'CAMO activity not found for update',
                statusCode: HttpResponse::HTTP_NOT_FOUND
            );

        } catch (AuthorizationException $e) {
            return new ApiErrorResponse(
                exception: $e,
                message: 'Unauthorized to update this CAMO activity',
                statusCode: HttpResponse::HTTP_FORBIDDEN
            );

        } catch (RepositoryException $e) {
            return new ApiErrorResponse(
                exception: $e,
                message: $e->getMessage(),
                statusCode: HttpResponse::HTTP_INTERNAL_SERVER_ERROR
            );

        } catch (Throwable $e) {
            return new ApiErrorResponse(
                exception: $e,
                message: 'Failed to update CAMO activity',
                statusCode: HttpResponse::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    /**
     * @OA\Delete(
     *     path="/api/v1/camo-activities/{id}",
     *     tags={"CAMO Activities"},
     *     summary="Delete CAMO activity",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="CAMO activity deleted successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="data", type="null"),
     *             @OA\Property(property="metaData", type="object")
     *         )
     *     )
     * )
     */
    public function destroy(string $id)
    {
        try {
            $camoActivity = $this->activity->getById($id);
            $this->authorize('delete', $camoActivity);

            $this->activity->deleteModel($id);

            return new ApiSuccessResponse(
                data: null,
                metaData: [
                    'action' => 'deleted',
                    'camo_id' => $camoActivity->camo_id,
                    'deleted_at' => now()->toDateTimeString()
                ],
                statusCode: HttpResponse::HTTP_NO_CONTENT
            );

        } catch (ModelNotFoundException $e) {
            return new ApiErrorResponse(
                exception: $e,
                message: 'CAMO activity not found for deletion',
                statusCode: HttpResponse::HTTP_NOT_FOUND
            );

        } catch (AuthorizationException $e) {
            return new ApiErrorResponse(
                exception: $e,
                message: 'Unauthorized to delete this CAMO activity',
                statusCode: HttpResponse::HTTP_FORBIDDEN
            );

        } catch (Throwable $e) {
            return new ApiErrorResponse(
                exception: $e,
                message: 'Failed to delete CAMO activity',
                statusCode: HttpResponse::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }
}
