<?php

namespace App\Http\Controllers\Api\v1;

use App\Contracts\EngineTypeRepositoryInterface;
use App\Helpers\InertiaResponse;
use App\Http\Requests\StoreEngineTypeRequest;
use App\Http\Requests\UpdateEngineTypeRequest;
use App\Http\Resources\EngineTypeResource;
use App\Http\Responses\ApiErrorResponse;
use App\Http\Responses\ApiSuccessResponse;
use App\Models\EngineType;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Http\Middleware\HandlePrecognitiveRequests as Precognitive;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use App\Http\Controllers\Controller;
use Inertia\Response;
use Symfony\Component\HttpFoundation\Response as HttpResponse;
use Throwable;

class EngineTypeController extends Controller
{
    public function __construct(protected EngineTypeRepositoryInterface $engineTypeRepository)
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
            $this->authorize('viewAny', EngineType::class);
            $engineTypes = $this->engineTypeRepository->getAll($request);

            if ($this->isApiRequest($request)) {
                return new ApiSuccessResponse(
                    data: EngineTypeResource::collection($engineTypes),
                    metaData: [
                        'total' => $engineTypes->total(),
                        'per_page' => $engineTypes->perPage(),
                        'current_page' => $engineTypes->currentPage()
                    ],
                    statusCode: HttpResponse::HTTP_OK
                );
            }

            return InertiaResponse::content('EngineTypes/Index', [
                'resource' => EngineTypeResource::collection($engineTypes)
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
            $this->authorize('create', EngineType::class);

            if ($this->isApiRequest($request)) {
                return new ApiSuccessResponse(
                    data: null,
                    message: 'Create form data would be here',
                    statusCode: HttpResponse::HTTP_OK
                );
            }

            return InertiaResponse::content('EngineTypes/Create');
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
    public function store(StoreEngineTypeRequest $request)
    {
        try {
            $this->authorize('create', EngineType::class);
            $payload = precognitive(static fn($bail) => $request->validated());
            $engineType = $this->engineTypeRepository->newEngineType($payload);

            if ($this->isApiRequest($request)) {
                return new ApiSuccessResponse(
                    data: new EngineTypeResource($engineType),
                    metaData: ['action' => 'created'],
                    statusCode: HttpResponse::HTTP_CREATED
                );
            }

            return to_route('engine-types.index')->with('success', 'Engine Type has been created.');
        } catch (AuthorizationException $e) {
            Log::error('Authorization error: '.$e->getMessage());
            return $this->handleErrorResponse('Unauthorized', HttpResponse::HTTP_FORBIDDEN, $request);
        } catch (Throwable $e) {
            Log::error('Error creating Engine Type: '.$e->getMessage());
            return $this->handleErrorResponse('Server Error', HttpResponse::HTTP_INTERNAL_SERVER_ERROR, $request);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, int $id)
    {
        try {
            $engineType = $this->engineTypeRepository->getById($id);
            $this->authorize('view', $engineType);

            if ($this->isApiRequest($request)) {
                return new ApiSuccessResponse(
                    data: new EngineTypeResource($engineType),
                    metaData: ['fetched' => now()->toDateTimeString()],
                    statusCode: HttpResponse::HTTP_OK
                );
            }

            return InertiaResponse::content('EngineTypes/Show', [
                'resource' => new EngineTypeResource($engineType)
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
            $engineType = $this->engineTypeRepository->getById($id);
            $this->authorize('update', $engineType);

            if ($this->isApiRequest($request)) {
                return new ApiSuccessResponse(
                    data: new EngineTypeResource($engineType),
                    message: 'Edit form data would be here',
                    statusCode: HttpResponse::HTTP_OK
                );
            }

            return InertiaResponse::content('EngineTypes/Edit', [
                'resource' => new EngineTypeResource($engineType)
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
    public function update(UpdateEngineTypeRequest $request, int $id)
    {
        try {
            $engineType = $this->engineTypeRepository->getById($id);
            $this->authorize('update', $engineType);
            $payload = precognitive(static fn($bail) => $request->validated());
            $updatedEngineType = $this->engineTypeRepository->updateEngineType($payload, $id);

            if ($this->isApiRequest($request)) {
                return new ApiSuccessResponse(
                    data: new EngineTypeResource($updatedEngineType),
                    metaData: [
                        'action' => 'updated',
                        'updated_at' => now()->toDateTimeString()
                    ],
                    statusCode: HttpResponse::HTTP_OK
                );
            }

            return to_route('engine-types.index')->with('success', 'Engine Type has been updated.');
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
            $engineType = $this->engineTypeRepository->getById($id);
            $this->authorize('delete', $engineType);
            $this->engineTypeRepository->deleteEngineType($id);

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

            return to_route('engine-types.index')->with('success', 'Engine Type has been deleted.');
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