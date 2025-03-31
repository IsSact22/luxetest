<?php

namespace App\Http\Controllers\Api\v1;

use App\Helpers\LogHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAircraftRequest;
use App\Http\Requests\UpdateAircraftRequest;
use App\Http\Resources\AircraftResource;
use App\Http\Responses\ApiErrorResponse;
use App\Http\Responses\ApiSuccessResponse;
use App\Models\Aircraft;
use App\Traits\HasModelName;
use App\Traits\QueryExceptionDataTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;
use Throwable;

use function App\Helpers\AfterCatchUnknown;

class AircraftController extends Controller
{
    use HasModelName;
    use QueryExceptionDataTrait;

    public function index(Request $request): ApiSuccessResponse|ApiErrorResponse
    {
        try {
            $aircraft = Aircraft::query()
                ->when($request->get('search'), static function ($query, string $search) {
                    $query->whereHas('owner', static function (Builder $query) use ($search) {
                        $query->where('name', 'LIKE', $search.'%');
                    })
                        ->orWhereHas('aircraftModel', static function (Builder $query) use ($search) {
                            $query->where('name', 'LIKE', $search);
                        });
                })
                ->paginate()
                ->withQueryString();
            $resource = AircraftResource::collection($aircraft);

            return new ApiSuccessResponse(
                $resource,
                ['message' => 'resource '.$this->modelName],
                ResponseAlias::HTTP_ACCEPTED
            );
        } catch (Throwable $throwable) {
            LogHelper::logError($throwable);

            return new ApiErrorResponse(
                $throwable,
                $throwable->getMessage(),
                ResponseAlias::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    public function store(StoreAircraftRequest $request): ApiSuccessResponse|ApiErrorResponse
    {
        try {
            $aircraft = \App\Models\Aircraft::query()->create($request->all());
            $resource = new AircraftResource($aircraft);

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

            return new ApiErrorResponse(
                $e,
                $e->getMessage(),
                ResponseAlias::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    public function show(string $id): ApiSuccessResponse|ApiErrorResponse
    {
        try {
            $aircraft = \App\Models\Aircraft::query()->findOrFail($id);
            $resource = new AircraftResource($aircraft);

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

    public function update(UpdateAircraftRequest $request, string $id): ApiSuccessResponse|ApiErrorResponse
    {
        try {
            $aircraft = \App\Models\Aircraft::query()->findOrFail($id);
            $aircraft->update($request->all());
            $resource = new AircraftResource($aircraft);

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
            $aircraftModel = \App\Models\Aircraft::query()->findOrFail($id);
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
