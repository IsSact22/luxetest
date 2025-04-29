<?php

namespace App\Http\Controllers\Api\v1;

use App\Contracts\CamoRepositoryInterface;
use App\Helpers\InertiaResponse;
use App\Http\Requests\StoreCamoRequest;
use App\Http\Requests\UpdateCamoRequest;
use App\Http\Resources\CamoResource;
use App\Http\Responses\ApiErrorResponse;
use App\Http\Responses\ApiSuccessResponse;
use App\Models\Camo;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Http\Middleware\HandlePrecognitiveRequests;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Inertia\Response;
use Symfony\Component\HttpFoundation\Response as HttpResponse;
use Throwable;
use App\Http\Controllers\Controller;

class CamoController extends Controller
{
    public function __construct(protected CamoRepositoryInterface $camoRepository)
    {
        parent::__construct();
        $this->middleware(HandlePrecognitiveRequests::class)->only(['store', 'update']);
        $this->middleware('auth:api')->only(['apiIndex', 'apiStore', 'apiShow', 'apiUpdate', 'apiDestroy']);
    }

    /**
     * Determina si la solicitud es una API request
     */
    protected function isApiRequest(Request $request): bool
    {
        return $request->is('api') || $request->wantsJson();
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $this->authorize('viewAny', Camo::class);
            $camos = $this->camoRepository->getAll($request);

            if ($this->isApiRequest($request)) {
                return new ApiSuccessResponse(
                    data: CamoResource::collection($camos),
                    metaData: [
                        'total' => $camos->total(),
                        'per_page' => $camos->perPage(),
                        'current_page' => $camos->currentPage()
                    ],
                    statusCode: HttpResponse::HTTP_OK
                );
            }

            return InertiaResponse::content('Camos/Index', [
                'resource' => CamoResource::collection($camos)
            ]);

        } catch (AuthorizationException) {
            return $this->handleErrorResponse('Unauthorized', HttpResponse::HTTP_UNAUTHORIZED, $request);
        } catch (Throwable $e) {
            Log::error($e->getMessage());
            return $this->handleErrorResponse($e->getMessage(), HttpResponse::HTTP_INTERNAL_SERVER_ERROR, $request);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request): Inertia\Response|ApiSuccessResponse
    {
        try {
            $this->authorize('create', Camo::class);

            if ($this->isApiRequest($request)) {
                return new ApiSuccessResponse(
                    data: null,
                      metaData: [
                        'message' => 'Create form data would be here'
                    ],
                    statusCode: HttpResponse::HTTP_OK
                );
            }

            return InertiaResponse::content('Camos/Create');
        } catch (AuthorizationException) {
            return $this->handleErrorResponse('Unauthorized', HttpResponse::HTTP_UNAUTHORIZED, $request);
        } catch (Throwable $e) {
            Log::error($e->getMessage());
            return $this->handleErrorResponse($e->getMessage(), HttpResponse::HTTP_INTERNAL_SERVER_ERROR, $request);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCamoRequest $request)
    {
        try {
            $this->authorize('create', Camo::class);
            $payload = $request->validated();
            $camo = $this->camoRepository->newModel($payload);

            if ($this->isApiRequest($request)) {
                return new ApiSuccessResponse(
                    data: new CamoResource($camo),
                    metaData: ['action' => 'created'],
                    statusCode: HttpResponse::HTTP_CREATED
                );
            }

            return redirect()->route('camos.index')->with('success', 'CAMO creado exitosamente');
        } catch (AuthorizationException $e) {
            Log::error('Authorization error: '.$e->getMessage());
            return $this->handleErrorResponse('Unauthorized', HttpResponse::HTTP_FORBIDDEN, $request);
        } catch (Throwable $e) {
            Log::error('Error creating CAMO: '.$e->getMessage());
            return $this->handleErrorResponse($e->getMessage(), HttpResponse::HTTP_INTERNAL_SERVER_ERROR, $request);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, int $id)
    {
        try {
            $camo = $this->camoRepository->getById($id);
            $camo->load('camoActivity');
            $this->authorize('view', $camo);

            if ($this->isApiRequest($request)) {
                return new ApiSuccessResponse(
                    data: new CamoResource($camo),
                    metaData: ['activities_count' => $camo->camoActivity->count()],
                    statusCode: HttpResponse::HTTP_OK
                );
            }

            return InertiaResponse::content('Camos/Show', [
                'resource' => new CamoResource($camo)
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
    public function edit(Request $request, string $id)
    {
        try {
            $camo = $this->camoRepository->getById($id);
            $this->authorize('update', $camo);

            if ($this->isApiRequest($request)) {
                return new ApiSuccessResponse(
                    data: new CamoResource($camo),
                    message: 'Edit form data would be here',
                    statusCode: HttpResponse::HTTP_OK
                );
            }

            return InertiaResponse::content('Camos/Edit', [
                'resource' => new CamoResource($camo)
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
    public function update(UpdateCamoRequest $request, string $id)
    {
        try {
            $camo = $this->camoRepository->getById($id);
            $this->authorize('update', $camo);
            $payload = $request->validated();
            $updatedCamo = $this->camoRepository->updateModel($payload, $id);

            if ($this->isApiRequest($request)) {
                return new ApiSuccessResponse(
                    data: new CamoResource($updatedCamo),
                    metaData: [
                        'action' => 'updated',
                        'updated_at' => now()->toDateTimeString()
                    ],
                    statusCode: HttpResponse::HTTP_OK
                );
            }

            return to_route('camos.index')->with('success', 'CAMO updated successfully');
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
    public function destroy(Request $request, string $id)
    {
        try {
            $camo = $this->camoRepository->getById($id);
            $this->authorize('delete', $camo);
            $this->camoRepository->deleteModel($id);

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

            return to_route('camos.index')->with('success', 'CAMO deleted successfully');
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