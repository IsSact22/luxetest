<?php

namespace App\Http\Controllers\Api\v1;

use App\Contracts\BrandAircraftRepositoryInterface;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBrandAircraftRequest;
use App\Http\Requests\UpdateBrandAircraftRequest;
use App\Http\Resources\BrandAircraftResource;
use App\Http\Responses\ApiErrorResponse;
use App\Http\Responses\ApiSuccessResponse;
use App\Models\BrandAircraft;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response as HttpResponse;
use Throwable;

class BrandAircraftController extends Controller
{
    public function __construct(protected BrandAircraftRepositoryInterface $brandAircraftRepository)
    {
        $this->middleware('auth:api');
    }

    /**
     * @OA\Get(
     *     path="/api/v1/brand-aircrafts",
     *     tags={"Brand Aircrafts"},
     *     summary="List all brand aircrafts",
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/BrandAircraftResource")),
     *             @OA\Property(property="metaData", type="object")
     *         )
     *     )
     * )
     */
    public function index(Request $request)
    {
        try {
            $this->authorize('viewAny', BrandAircraft::class);
            $brandAircrafts = $this->brandAircraftRepository->getAll($request);

            return new ApiSuccessResponse(
                data: BrandAircraftResource::collection($brandAircrafts),
                metaData: [
                    'total' => $brandAircrafts->total(),
                    'per_page' => $brandAircrafts->perPage(),
                    'current_page' => $brandAircrafts->currentPage()
                ],
                statusCode: HttpResponse::HTTP_OK
            );

        } catch (AuthorizationException $e) {
            return new ApiErrorResponse(
                exception: $e,
                message: 'Unauthorized to view brand aircrafts',
                statusCode: HttpResponse::HTTP_FORBIDDEN
            );

        } catch (Throwable $e) {
            return new ApiErrorResponse(
                exception: $e,
                message: 'Failed to retrieve brand aircrafts',
                statusCode: HttpResponse::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    /**
     * @OA\Post(
     *     path="/api/v1/brand-aircrafts",
     *     tags={"Brand Aircrafts"},
     *     summary="Create a new brand aircraft",
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/StoreBrandAircraftRequest")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Brand aircraft created successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="data", ref="#/components/schemas/BrandAircraftResource"),
     *             @OA\Property(property="metaData", type="object")
     *         )
     *     )
     * )
     */
    public function store(StoreBrandAircraftRequest $request)
    {
        try {
            $this->authorize('create', BrandAircraft::class);
            $brandAircraft = $this->brandAircraftRepository->newBrandAircraft($request->validated());

            return new ApiSuccessResponse(
                data: new BrandAircraftResource($brandAircraft),
                metaData: ['action' => 'created'],
                statusCode: HttpResponse::HTTP_CREATED
            );

        } catch (AuthorizationException $e) {
            return new ApiErrorResponse(
                exception: $e,
                message: 'Unauthorized to create brand aircraft',
                statusCode: HttpResponse::HTTP_FORBIDDEN
            );

        } catch (Throwable $e) {
            return new ApiErrorResponse(
                exception: $e,
                message: 'Failed to create brand aircraft',
                statusCode: HttpResponse::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    /**
     * @OA\Get(
     *     path="/api/v1/brand-aircrafts/{id}",
     *     tags={"Brand Aircrafts"},
     *     summary="Get brand aircraft details",
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
     *             @OA\Property(property="data", ref="#/components/schemas/BrandAircraftResource"),
     *             @OA\Property(property="metaData", type="object")
     *         )
     *     )
     * )
     */
    public function show(int $id)
    {
        try {
            $brandAircraft = $this->brandAircraftRepository->getById($id);
            $this->authorize('view', $brandAircraft);

            return new ApiSuccessResponse(
                data: new BrandAircraftResource($brandAircraft),
                metaData: ['fetched' => now()->toDateTimeString()],
                statusCode: HttpResponse::HTTP_OK
            );

        } catch (ModelNotFoundException $e) {
            return new ApiErrorResponse(
                exception: $e,
                message: 'Brand aircraft not found',
                statusCode: HttpResponse::HTTP_NOT_FOUND
            );

        } catch (AuthorizationException $e) {
            return new ApiErrorResponse(
                exception: $e,
                message: 'Unauthorized to view this brand aircraft',
                statusCode: HttpResponse::HTTP_FORBIDDEN
            );

        } catch (Throwable $e) {
            return new ApiErrorResponse(
                exception: $e,
                message: 'Failed to retrieve brand aircraft',
                statusCode: HttpResponse::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    /**
     * @OA\Put(
     *     path="/api/v1/brand-aircrafts/{id}",
     *     tags={"Brand Aircrafts"},
     *     summary="Update brand aircraft",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/UpdateBrandAircraftRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Brand aircraft updated successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="data", ref="#/components/schemas/BrandAircraftResource"),
     *             @OA\Property(property="metaData", type="object")
     *         )
     *     )
     * )
     */
    public function update(UpdateBrandAircraftRequest $request, int $id)
    {
        try {
            $brandAircraft = $this->brandAircraftRepository->getById($id);
            $this->authorize('update', $brandAircraft);

            $updatedBrandAircraft = $this->brandAircraftRepository->updateBrandAircraft($request->validated(), $id);

            return new ApiSuccessResponse(
                data: new BrandAircraftResource($updatedBrandAircraft),
                metaData: ['action' => 'updated', 'at' => now()->toDateTimeString()],
                statusCode: HttpResponse::HTTP_OK
            );

        } catch (ModelNotFoundException $e) {
            return new ApiErrorResponse(
                exception: $e,
                message: 'Brand aircraft not found for update',
                statusCode: HttpResponse::HTTP_NOT_FOUND
            );

        } catch (AuthorizationException $e) {
            return new ApiErrorResponse(
                exception: $e,
                message: 'Unauthorized to update this brand aircraft',
                statusCode: HttpResponse::HTTP_FORBIDDEN
            );

        } catch (Throwable $e) {
            return new ApiErrorResponse(
                exception: $e,
                message: 'Failed to update brand aircraft',
                statusCode: HttpResponse::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    /**
     * @OA\Delete(
     *     path="/api/v1/brand-aircrafts/{id}",
     *     tags={"Brand Aircrafts"},
     *     summary="Delete brand aircraft",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Brand aircraft deleted successfully",
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
            $brandAircraft = $this->brandAircraftRepository->getById($id);
            $this->authorize('delete', $brandAircraft);

            $this->brandAircraftRepository->deleteBrandAircraft($id);

            return new ApiSuccessResponse(
                data: null,
                metaData: ['action' => 'deleted', 'at' => now()->toDateTimeString()],
                statusCode: HttpResponse::HTTP_NO_CONTENT
            );

        } catch (ModelNotFoundException $e) {
            return new ApiErrorResponse(
                exception: $e,
                message: 'Brand aircraft not found for deletion',
                statusCode: HttpResponse::HTTP_NOT_FOUND
            );

        } catch (AuthorizationException $e) {
            return new ApiErrorResponse(
                exception: $e,
                message: 'Unauthorized to delete this brand aircraft',
                statusCode: HttpResponse::HTTP_FORBIDDEN
            );

        } catch (Throwable $e) {
            return new ApiErrorResponse(
                exception: $e,
                message: 'Failed to delete brand aircraft',
                statusCode: HttpResponse::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }
}
