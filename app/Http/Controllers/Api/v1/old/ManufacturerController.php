<?php

namespace App\Http\Controllers\Api\v1;

use App\Helpers\LogHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreManufacturerRequest;
use App\Http\Requests\UpdateManufacturerRequest;
use App\Http\Resources\ManufacturerResource;
use App\Http\Responses\ApiErrorResponse;
use App\Http\Responses\ApiSuccessResponse;
use App\Models\Manufacturer;
use App\Traits\HasModelName;
use App\Traits\QueryExceptionDataTrait;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;
use Throwable;

use function App\Helpers\AfterCatchUnknown;

class ManufacturerController extends Controller
{
    use HasModelName;
    use QueryExceptionDataTrait;

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): ApiSuccessResponse|ApiErrorResponse
    {
        try {
            $manufacturers = Manufacturer::query()
                ->when($request->get('search'), static function ($query, string $search) {
                    $query->where('name', 'LIKE', $search.'%')
                        ->orWhere('acronym', $search);
                })
                ->paginate()
                ->withQueryString();

            $resource = ManufacturerResource::collection($manufacturers);

            return new ApiSuccessResponse(
                $resource,
                ['message' => 'return resource '.$this->modelName],
                ResponseAlias::HTTP_ACCEPTED
            );
        } catch (Throwable $throwable) {
            LogHelper::logError($throwable);

            return new ApiErrorResponse(
                $throwable,
                'Error occurred while fetching manufacturers.',
                ResponseAlias::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreManufacturerRequest $request): ApiSuccessResponse|ApiErrorResponse
    {
        try {
            $manufacturer = Manufacturer::create($request->all());

            return new ApiSuccessResponse(
                $manufacturer,
                ['message' => $this->modelName.'register successfully'],
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
            $manufacturer = Manufacturer::findOrFail($id);
            $resource = new ManufacturerResource($manufacturer);

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

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateManufacturerRequest $request, string $id): ApiSuccessResponse|ApiErrorResponse
    {
        try {
            $manufacturer = Manufacturer::findOrFail($id);
            $manufacturer->update($request->all());
            $resource = new ManufacturerResource($manufacturer);

            return new ApiSuccessResponse(
                $resource,
                ['message' => 'resource '.$this->modelName],
                ResponseAlias::HTTP_ACCEPTED
            );
        } catch (ModelNotFoundException $e) {
            return new ApiErrorResponse($e, $e->getMessage());
        } catch (Throwable $e) {
            LogHelper::logError($e);

            return new ApiErrorResponse($e, $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): ApiSuccessResponse|ApiErrorResponse
    {
        try {
            $manufacturer = Manufacturer::findOrFail($id);
            $manufacturer->delete();

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
