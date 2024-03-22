<?php

namespace App\Http\Controllers\Api\v1;

use App\Helpers\LogHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAircraftModelRequest;
use App\Http\Requests\UpdateAircraftModelRequest;
use App\Http\Resources\AircraftModelResource;
use App\Http\Responses\ApiErrorResponse;
use App\Http\Responses\ApiSuccessResponse;
use App\Models\AircraftModel;
use App\Traits\HasModelName;
use App\Traits\QueryExceptionDataTrait;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;
use Throwable;

use function App\Helpers\AfterCatchUnknown;

class AircraftModelController extends Controller
{
    use HasModelName;
    use QueryExceptionDataTrait;

    public function index(Request $request): ApiSuccessResponse|ApiErrorResponse
    {
        try {
            $aircraftModels = AircraftModel::query()
                ->when($request->get('search'), function ($query, string $search) {
                    $query->where('name', 'LIKE', $search.'%');
                })
                ->when($request->get('category'), function ($query, string $category) {
                    $query->where('category', $category);
                })
                ->when($request->get('class'), function ($query, string $class) {
                    $query->where('class', $class);
                })
                ->when($request->get('motor_type'), function ($query, string $motorType) {
                    $query->where('motor_type', $motorType);
                })
                ->when($request->get('motor_qty'), function ($query, int $motorQty) {
                    $query->where('motor_qty', $motorQty);
                })
                ->paginate()
                ->withQueryString();
            $resource = AircraftModelResource::collection($aircraftModels);

            return new ApiSuccessResponse(
                $resource,
                ['message' => 'resource '.$this->modelName],
                ResponseAlias::HTTP_ACCEPTED
            );
        } catch (Throwable $e) {
            return new ApiErrorResponse(
                $e,
                $e->getMessage(),
                ResponseAlias::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAircraftModelRequest $request): ApiSuccessResponse|ApiErrorResponse
    {
        try {
            $aircraftModel = AircraftModel::create($request->all());
            $resource = new AircraftModelResource($aircraftModel);

            return new ApiSuccessResponse(
                $resource,
                ['message' => 'new resource '.$this->modelName],
                ResponseAlias::HTTP_CREATED
            );
        } catch (ValidationException $e) {
            return new ApiErrorResponse(
                $e,
                $e->getMessage(),
                ResponseAlias::HTTP_UNPROCESSABLE_ENTITY
            );
        } catch (Throwable $e) {
            LogHelper::logError($e);

            return AfterCatchUnknown();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): ApiSuccessResponse|ApiErrorResponse
    {
        try {
            $aircraftModel = AircraftModel::findOrFail($id);
            $resource = new AircraftModelResource($aircraftModel);

            return new ApiSuccessResponse(
                $resource,
                ['message' => 'resource '.$this->modelName],
                ResponseAlias::HTTP_ACCEPTED
            );
        } catch (ModelNotFoundException $e) {
            return new ApiErrorResponse($e, $e->getMessage());
        } catch (Throwable $e) {
            LogHelper::logError($e);

            return AfterCatchUnknown();
        }
    }

    public function update(UpdateAircraftModelRequest $request, string $id): ApiSuccessResponse|ApiErrorResponse
    {
        try {
            $aircraftModel = AircraftModel::findOrFail($id);
            $aircraftModel->update($request->all());
            $resource = new AircraftModelResource($aircraftModel);

            return new ApiSuccessResponse(
                $resource,
                ['message' => 'updated resource '.$this->modelName],
                ResponseAlias::HTTP_ACCEPTED
            );
        } catch (ModelNotFoundException $e) {
            return new ApiErrorResponse($e, $e->getMessage());
        } catch (Throwable $e) {
            LogHelper::logError($e);

            return new ApiErrorResponse($e, $e->getMessage());
        }
    }

    public function destroy(string $id): ApiSuccessResponse|ApiErrorResponse
    {
        try {
            $aircraftModel = AircraftModel::findOrFail($id);
            $aircraftModel->delete();

            return new ApiSuccessResponse(
                [],
                ['message' => 'register deleted successfully'],
                ResponseAlias::HTTP_ACCEPTED
            );
        } catch (ModelNotFoundException $e) {
            return new ApiErrorResponse(
                $e,
                $e->getMessage(),
                ResponseAlias::HTTP_NOT_FOUND
            );
        } catch (Throwable $e) {
            LogHelper::logError($e);

            return AfterCatchUnknown();
        }
    }
}
