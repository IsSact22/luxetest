<?php

namespace App\Http\Controllers\Api\v1;

use App\Contracts\AdminRateRepositoryInterface;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAdminRateRequest;
use App\Http\Requests\UpdateAdminRateRequest;
use App\Http\Resources\AdminRateResource;
use App\Http\Responses\ApiErrorResponse;
use App\Http\Responses\ApiSuccessResponse;
use App\Models\AdminRate;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response as HttpResponse;
use Throwable;

class AdminRateController extends Controller
{
    public function __construct(protected AdminRateRepositoryInterface $adminRateRepository)
    {
        $this->middleware('auth:api');
    }

    /**
     * @OA\Get(
     *     path="/api/v1/admin-rates",
     *     tags={"Admin Rates"},
     *     summary="List all admin rates",
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/AdminRateResource")),
     *             @OA\Property(property="metaData", type="object")
     *         )
     *     )
     * )
     */
    public function index(Request $request)
    {
        try {
            $this->authorize('viewAny', AdminRate::class);
            $adminRates = $this->adminRateRepository->getAll($request);

            return new ApiSuccessResponse(
                data: AdminRateResource::collection($adminRates),
                metaData: [
                    'total' => $adminRates->total(),
                    'per_page' => $adminRates->perPage(),
                    'current_page' => $adminRates->currentPage()
                ],
                statusCode: HttpResponse::HTTP_OK
            );

        } catch (AuthorizationException $e) {
            return new ApiErrorResponse(
                exception: $e,
                message: 'Unauthorized to view admin rates',
                statusCode: HttpResponse::HTTP_FORBIDDEN
            );

        } catch (Throwable $e) {
            return new ApiErrorResponse(
                exception: $e,
                message: 'Failed to retrieve admin rates',
                statusCode: HttpResponse::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    /**
     * @OA\Post(
     *     path="/api/v1/admin-rates",
     *     tags={"Admin Rates"},
     *     summary="Create a new admin rate",
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/StoreAdminRateRequest")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Admin rate created successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="data", ref="#/components/schemas/AdminRateResource"),
     *             @OA\Property(property="metaData", type="object")
     *         )
     *     )
     * )
     */
    public function store(StoreAdminRateRequest $request)
    {
        try {
            $this->authorize('create', AdminRate::class);
            $adminRate = $this->adminRateRepository->newModel($request->validated());

            return new ApiSuccessResponse(
                data: new AdminRateResource($adminRate),
                metaData: ['action' => 'created'],
                statusCode: HttpResponse::HTTP_CREATED
            );

        } catch (AuthorizationException $e) {
            return new ApiErrorResponse(
                exception: $e,
                message: 'Unauthorized to create admin rate',
                statusCode: HttpResponse::HTTP_FORBIDDEN
            );

        } catch (Throwable $e) {
            return new ApiErrorResponse(
                exception: $e,
                message: 'Failed to create admin rate',
                statusCode: HttpResponse::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    /**
     * @OA\Get(
     *     path="/api/v1/admin-rates/{id}",
     *     tags={"Admin Rates"},
     *     summary="Get admin rate details",
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
     *             @OA\Property(property="data", ref="#/components/schemas/AdminRateResource"),
     *             @OA\Property(property="metaData", type="object")
     *         )
     *     )
     * )
     */
    public function show(int $id)
    {
        try {
            $adminRate = $this->adminRateRepository->getById($id);
            $this->authorize('view', $adminRate);

            return new ApiSuccessResponse(
                data: new AdminRateResource($adminRate),
                metaData: ['fetched' => now()->toDateTimeString()],
                statusCode: HttpResponse::HTTP_OK
            );

        } catch (ModelNotFoundException $e) {
            return new ApiErrorResponse(
                exception: $e,
                message: 'Admin rate not found',
                statusCode: HttpResponse::HTTP_NOT_FOUND
            );

        } catch (AuthorizationException $e) {
            return new ApiErrorResponse(
                exception: $e,
                message: 'Unauthorized to view this admin rate',
                statusCode: HttpResponse::HTTP_FORBIDDEN
            );

        } catch (Throwable $e) {
            return new ApiErrorResponse(
                exception: $e,
                message: 'Failed to retrieve admin rate',
                statusCode: HttpResponse::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    /**
     * @OA\Put(
     *     path="/api/v1/admin-rates/{id}",
     *     tags={"Admin Rates"},
     *     summary="Update admin rate",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/UpdateAdminRateRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Admin rate updated successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="data", ref="#/components/schemas/AdminRateResource"),
     *             @OA\Property(property="metaData", type="object")
     *         )
     *     )
     * )
     */
    public function update(UpdateAdminRateRequest $request, int $id)
    {
        try {
            $adminRate = $this->adminRateRepository->getById($id);
            $this->authorize('update', $adminRate);

            $updatedAdminRate = $this->adminRateRepository->updateModel($request->validated(), $id);

            return new ApiSuccessResponse(
                data: new AdminRateResource($updatedAdminRate),
                metaData: ['action' => 'updated', 'at' => now()->toDateTimeString()],
                statusCode: HttpResponse::HTTP_OK
            );

        } catch (ModelNotFoundException $e) {
            return new ApiErrorResponse(
                exception: $e,
                message: 'Admin rate not found for update',
                statusCode: HttpResponse::HTTP_NOT_FOUND
            );

        } catch (AuthorizationException $e) {
            return new ApiErrorResponse(
                exception: $e,
                message: 'Unauthorized to update this admin rate',
                statusCode: HttpResponse::HTTP_FORBIDDEN
            );

        } catch (Throwable $e) {
            return new ApiErrorResponse(
                exception: $e,
                message: 'Failed to update admin rate',
                statusCode: HttpResponse::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    /**
     * @OA\Delete(
     *     path="/api/v1/admin-rates/{id}",
     *     tags={"Admin Rates"},
     *     summary="Delete admin rate",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Admin rate deleted successfully",
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
            $adminRate = $this->adminRateRepository->getById($id);
            $this->authorize('delete', $adminRate);

            $this->adminRateRepository->deleteModel($id);

            return new ApiSuccessResponse(
                data: null,
                metaData: ['action' => 'deleted', 'at' => now()->toDateTimeString()],
                statusCode: HttpResponse::HTTP_NO_CONTENT
            );

        } catch (ModelNotFoundException $e) {
            return new ApiErrorResponse(
                exception: $e,
                message: 'Admin rate not found for deletion',
                statusCode: HttpResponse::HTTP_NOT_FOUND
            );

        } catch (AuthorizationException $e) {
            return new ApiErrorResponse(
                exception: $e,
                message: 'Unauthorized to delete this admin rate',
                statusCode: HttpResponse::HTTP_FORBIDDEN
            );

        } catch (Throwable $e) {
            return new ApiErrorResponse(
                exception: $e,
                message: 'Failed to delete admin rate',
                statusCode: HttpResponse::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }
}
