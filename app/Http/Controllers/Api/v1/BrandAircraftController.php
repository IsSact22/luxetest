<?php

namespace App\Http\Controllers\Api\v1;
use App\Http\Controllers\Controller;

use App\Contracts\BrandAircraftRepositoryInterface;
use App\Helpers\InertiaResponse;
use App\Http\Requests\StoreBrandAircraftRequest;
use App\Http\Requests\UpdateBrandAircraftRequest;
use App\Http\Resources\BrandAircraftResource;
use App\Http\Responses\ApiErrorResponse;
use App\Http\Responses\ApiSuccessResponse;
use App\Models\BrandAircraft;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Http\Middleware\HandlePrecognitiveRequests as Precognitive;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Inertia\Response;
use Symfony\Component\HttpFoundation\Response as HttpResponse;
use Throwable;

class BrandAircraftController extends Controller
{
    public function __construct(protected BrandAircraftRepositoryInterface $brandAircraftRepository)
    {
        parent::__construct();
        $this->middleware(Precognitive::class)->only(['store', 'update']);
        $this->middleware('auth:api')->only(['apiIndex', 'apiStore', 'apiShow', 'apiUpdate', 'apiDestroy']);
    }

    /**
     * Determina si la solicitud es una API request
     */
    protected function isApiRequest(Request $request): bool
    {
        return $request->is('api/*') || $request->wantsJson();
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $this->authorize('viewAny', BrandAircraft::class);
            $brandAircrafts = $this->brandAircraftRepository->getAll($request);

            if ($this->isApiRequest($request)) {
                return new ApiSuccessResponse(
                    data: BrandAircraftResource::collection($brandAircrafts),
                    metaData: [
                        'total' => $brandAircrafts->total(),
                        'per_page' => $brandAircrafts->perPage(),
                        'current_page' => $brandAircrafts->currentPage()
                    ],
                    statusCode: HttpResponse::HTTP_OK
                );
            }

            return InertiaResponse::content('BrandAircrafts/Index', [
                'resource' => BrandAircraftResource::collection($brandAircrafts)
            ]);

        } catch (AuthorizationException $e) {
            return $this->handleErrorResponse('Unauthorized', HttpResponse::HTTP_UNAUTHORIZED, $request);
        } catch (Throwable $e) {
            Log::error($e->getMessage());
            return $this->handleErrorResponse($e->getMessage(), HttpResponse::HTTP_INTERNAL_SERVER_ERROR, $request);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        try {
            $this->authorize('create', BrandAircraft::class);

            if ($this->isApiRequest($request)) {
                return new ApiSuccessResponse(
                    data: null,
                    message: 'Create form data would be here',
                    statusCode: HttpResponse::HTTP_OK
                );
            }

            return InertiaResponse::content('BrandAircrafts/Create');
        } catch (AuthorizationException $e) {
            return $this->handleErrorResponse('Unauthorized', HttpResponse::HTTP_UNAUTHORIZED, $request);
        } catch (Throwable $e) {
            Log::error($e->getMessage());
            return $this->handleErrorResponse($e->getMessage(), HttpResponse::HTTP_INTERNAL_SERVER_ERROR, $request);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreBrandAircraftRequest $request)
    {
        try {
            $this->authorize('create', BrandAircraft::class);
            $payload = precognitive(static fn($bail) => $request->validated());
            $brandAircraft = $this->brandAircraftRepository->newBrandAircraft($payload);

            if ($this->isApiRequest($request)) {
                return new ApiSuccessResponse(
                    data: new BrandAircraftResource($brandAircraft),
                    metaData: ['action' => 'created'],
                    statusCode: HttpResponse::HTTP_CREATED
                );
            }

            return to_route('brand-aircrafts.index')->with('success', 'Brand Aircraft has been created.');
        } catch (AuthorizationException $e) {
            Log::error('Authorization error: '.$e->getMessage());
            return $this->handleErrorResponse('Unauthorized', HttpResponse::HTTP_FORBIDDEN, $request);
        } catch (Throwable $e) {
            Log::error('Error creating Brand Aircraft: '.$e->getMessage());
            return $this->handleErrorResponse('Server Error', HttpResponse::HTTP_INTERNAL_SERVER_ERROR, $request);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, $id)
    {
        try {
            $brandAircraft = is_int($id) ? $this->brandAircraftRepository->getById($id) : $id;
            $this->authorize('view', $brandAircraft);

            if ($this->isApiRequest($request)) {
                return new ApiSuccessResponse(
                    data: new BrandAircraftResource($brandAircraft),
                    metaData: ['fetched' => now()->toDateTimeString()],
                    statusCode: HttpResponse::HTTP_OK
                );
            }

            return InertiaResponse::content('BrandAircrafts/Show', [
                'resource' => new BrandAircraftResource($brandAircraft)
            ]);
        } catch (ModelNotFoundException $e) {
            return $this->handleErrorResponse('Not Found', HttpResponse::HTTP_NOT_FOUND, $request);
        } catch (AuthorizationException $e) {
            return $this->handleErrorResponse('Unauthorized', HttpResponse::HTTP_FORBIDDEN, $request);
        } catch (Throwable $e) {
            Log::error($e->getMessage());
            return $this->handleErrorResponse('Server Error', HttpResponse::HTTP_INTERNAL_SERVER_ERROR, $request);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, int $id)
    {
        try {
            $brandAircraft = $this->brandAircraftRepository->getById($id);
            $this->authorize('update', $brandAircraft);

            if ($this->isApiRequest($request)) {
                return new ApiSuccessResponse(
                    data: new BrandAircraftResource($brandAircraft),
                    message: 'Edit form data would be here',
                    statusCode: HttpResponse::HTTP_OK
                );
            }

            return InertiaResponse::content('BrandAircrafts/Edit', [
                'resource' => new BrandAircraftResource($brandAircraft)
            ]);
        } catch (ModelNotFoundException $e) {
            return $this->handleErrorResponse('Not Found', HttpResponse::HTTP_NOT_FOUND, $request);
        } catch (Throwable $e) {
            Log::error($e->getMessage());
            return $this->handleErrorResponse('Server Error', HttpResponse::HTTP_INTERNAL_SERVER_ERROR, $request);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBrandAircraftRequest $request, int $id)
    {
        try {
            $brandAircraft = $this->brandAircraftRepository->getById($id);
            $this->authorize('update', $brandAircraft);
            $payload = precognitive(static fn($bail) => $request->validated());
            $updatedBrandAircraft = $this->brandAircraftRepository->updateBrandAircraft($payload, $id);

            if ($this->isApiRequest($request)) {
                return new ApiSuccessResponse(
                    data: new BrandAircraftResource($updatedBrandAircraft),
                    metaData: [
                        'action' => 'updated',
                        'updated_at' => now()->toDateTimeString()
                    ],
                    statusCode: HttpResponse::HTTP_OK
                );
            }

            return to_route('brand-aircrafts.index')->with('success', 'Brand Aircraft has been updated.');
        } catch (ModelNotFoundException $e) {
            return $this->handleErrorResponse('Not Found', HttpResponse::HTTP_NOT_FOUND, $request);
        } catch (Throwable $e) {
            Log::error($e->getMessage());
            return $this->handleErrorResponse('Server Error', HttpResponse::HTTP_INTERNAL_SERVER_ERROR, $request);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, int $id)
    {
        try {
            $brandAircraft = $this->brandAircraftRepository->getById($id);
            $this->authorize('delete', $brandAircraft);
            $this->brandAircraftRepository->deleteBrandAircraft($id);

            if ($this->isApiRequest($request)) {
                return new ApiSuccessResponse(
                    data: null,
                    metaData: [
                        'action' => 'deleted',
                        'deleted_at' => now()->toDateTimeString()
                    ],
                    statusCode: HttpResponse::HTTP_NO_CONTENT
                );
            }

            return to_route('brand-aircrafts.index')->with('success', 'Brand Aircraft has been deleted.');
        } catch (ModelNotFoundException $e) {
            return $this->handleErrorResponse('Not Found', HttpResponse::HTTP_NOT_FOUND, $request);
        } catch (Throwable $e) {
            Log::error($e->getMessage());
            return $this->handleErrorResponse('Server Error', HttpResponse::HTTP_INTERNAL_SERVER_ERROR, $request);
        }
    }

    /**
     * Maneja las respuestas de error de forma unificada
     */
    protected function handleErrorResponse(
        string $message, 
        int $statusCode, 
        Request $request
    ) {
        if ($this->isApiRequest($request)) {
            return new ApiErrorResponse(
                message: $message,
                statusCode: $statusCode
            );
        }

        return Inertia::render('Errors/Error', [
            'status' => $statusCode,
            'message' => $message
        ]);
    }
}