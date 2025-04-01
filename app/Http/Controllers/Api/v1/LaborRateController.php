<?php

namespace App\Http\Controllers\Api\v1;

use App\Contracts\LaborRateRepositoryInterface;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreLaborRateRequest;
use App\Http\Requests\UpdateLaborRateRequest;
use App\Http\Resources\LaborRateResource;
use App\Http\Responses\ApiErrorResponse;
use App\Http\Responses\ApiSuccessResponse;
use App\Models\CamoActivity;
use App\Models\LaborRate;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response as HttpResponse;
use Throwable;

class LaborRateController extends Controller
{
    public function __construct(protected LaborRateRepositoryInterface $laborRateRepository)
    {
        $this->middleware('auth:api');
    }

    /**
     * @OA\Get(
     *     path="/api/v1/labor-rates",
     *     tags={"Labor Rates"},
     *     summary="List all labor rates",
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
     *                 @OA\Items(ref="#/components/schemas/LaborRateResource")
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
            $this->authorize('viewAny', LaborRate::class);
            $laborRates = $this->laborRateRepository->getAll($request);

            return new ApiSuccessResponse(
                data: LaborRateResource::collection($laborRates),
                metaData: [
                    'total' => $laborRates->total(),
                    'per_page' => $laborRates->perPage(),
                    'current_page' => $laborRates->currentPage()
                ],
                statusCode: HttpResponse::HTTP_OK
            );

        } catch (AuthorizationException $e) {
            return new ApiErrorResponse(
                exception: $e,
                message: 'Unauthorized to view labor rates',
                statusCode: HttpResponse::HTTP_FORBIDDEN
            );
            
        } catch (Throwable $e) {
            return new ApiErrorResponse(
                exception: $e,
                message: 'Failed to retrieve labor rates',
                statusCode: HttpResponse::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    /**
     * @OA\Post(
     *     path="/api/v1/labor-rates",
     *     tags={"Labor Rates"},
     *     summary="Create a new labor rate",
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/StoreLaborRateRequest")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Labor rate created successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="data",
     *                 ref="#/components/schemas/LaborRateResource"
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
    public function store(StoreLaborRateRequest $request)
    {
        try {
            $this->authorize('create', LaborRate::class);
            $laborRate = $this->laborRateRepository->newModel($request->validated());

            return new ApiSuccessResponse(
                data: new LaborRateResource($laborRate),
                metaData: ['action' => 'created'],
                statusCode: HttpResponse::HTTP_CREATED
            );

        } catch (AuthorizationException $e) {
            return new ApiErrorResponse(
                exception: $e,
                message: 'Unauthorized to create labor rate',
                statusCode: HttpResponse::HTTP_FORBIDDEN
            );
            
        } catch (Throwable $e) {
            return new ApiErrorResponse(
                exception: $e,
                message: 'Failed to create labor rate',
                statusCode: HttpResponse::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    /**
     * @OA\Get(
     *     path="/api/v1/labor-rates/{id}",
     *     tags={"Labor Rates"},
     *     summary="Get labor rate details",
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
     *                 ref="#/components/schemas/LaborRateResource"
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
            $laborRate = $this->laborRateRepository->getById($id);
            $this->authorize('view', $laborRate);

            return new ApiSuccessResponse(
                data: new LaborRateResource($laborRate),
                metaData: ['fetched' => now()->toDateTimeString()],
                statusCode: HttpResponse::HTTP_OK
            );

        } catch (ModelNotFoundException $e) {
            return new ApiErrorResponse(
                exception: $e,
                message: 'Labor rate not found',
                statusCode: HttpResponse::HTTP_NOT_FOUND
            );
            
        } catch (AuthorizationException $e) {
            return new ApiErrorResponse(
                exception: $e,
                message: 'Unauthorized to view this labor rate',
                statusCode: HttpResponse::HTTP_FORBIDDEN
            );
            
        } catch (Throwable $e) {
            return new ApiErrorResponse(
                exception: $e,
                message: 'Failed to retrieve labor rate',
                statusCode: HttpResponse::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    /**
     * @OA\Put(
     *     path="/api/v1/labor-rates/{id}",
     *     tags={"Labor Rates"},
     *     summary="Update labor rate",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/UpdateLaborRateRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Labor rate updated successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="data",
     *                 ref="#/components/schemas/LaborRateResource"
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
    public function update(UpdateLaborRateRequest $request, int $id)
    {
        try {
            $laborRate = $this->laborRateRepository->getById($id);
            $this->authorize('update', $laborRate);
            
            $updatedLaborRate = $this->laborRateRepository->updateModel($request->validated(), $id);

            return new ApiSuccessResponse(
                data: new LaborRateResource($updatedLaborRate),
                metaData: [
                    'action' => 'updated',
                    'updated_at' => now()->toDateTimeString()
                ],
                statusCode: HttpResponse::HTTP_OK
            );

        } catch (ModelNotFoundException $e) {
            return new ApiErrorResponse(
                exception: $e,
                message: 'Labor rate not found for update',
                statusCode: HttpResponse::HTTP_NOT_FOUND
            );
            
        } catch (AuthorizationException $e) {
            return new ApiErrorResponse(
                exception: $e,
                message: 'Unauthorized to update this labor rate',
                statusCode: HttpResponse::HTTP_FORBIDDEN
            );
            
        } catch (Throwable $e) {
            return new ApiErrorResponse(
                exception: $e,
                message: 'Failed to update labor rate',
                statusCode: HttpResponse::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    /**
     * @OA\Delete(
     *     path="/api/v1/labor-rates/{id}",
     *     tags={"Labor Rates"},
     *     summary="Delete labor rate",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Labor rate deleted successfully",
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
            $laborRate = $this->laborRateRepository->getById($id);
            $this->authorize('delete', $laborRate);
            
            $this->laborRateRepository->deleteModel($id);

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
                message: 'Labor rate not found for deletion',
                statusCode: HttpResponse::HTTP_NOT_FOUND
            );
            
        } catch (AuthorizationException $e) {
            return new ApiErrorResponse(
                exception: $e,
                message: 'Unauthorized to delete this labor rate',
                statusCode: HttpResponse::HTTP_FORBIDDEN
            );
            
        } catch (Throwable $e) {
            return new ApiErrorResponse(
                exception: $e,
                message: 'Failed to delete labor rate',
                statusCode: HttpResponse::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }
}