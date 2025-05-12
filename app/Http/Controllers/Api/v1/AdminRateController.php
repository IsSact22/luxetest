<?php

namespace App\Http\Controllers\Api\v1;
use App\Http\Controllers\Controller;
use App\Contracts\AdminRateRepositoryInterface;
use App\Helpers\InertiaResponse;
use App\Http\Requests\StoreAdminRateRequest;
use App\Http\Requests\UpdateAdminRateRequest;
use App\Http\Resources\AdminRateResource;
use App\Http\Responses\ApiErrorResponse;
use App\Http\Responses\ApiSuccessResponse;
use App\Models\AdminRate;
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

class AdminRateController extends Controller
{
    public function __construct(protected AdminRateRepositoryInterface $adminRateRepository)
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
            $this->authorize('viewAny', AdminRate::class);
            $adminRates = $this->adminRateRepository->getAll($request);

            if ($this->isApiRequest($request)) {
                return new ApiSuccessResponse(
                    data: AdminRateResource::collection($adminRates),
                    metaData: [
                        'total' => $adminRates->total(),
                        'per_page' => $adminRates->perPage(),
                        'current_page' => $adminRates->currentPage()
                    ],
                    statusCode: HttpResponse::HTTP_OK
                );
            }

            return InertiaResponse::content('AdminRates/Index', [
                'resource' => AdminRateResource::collection($adminRates)
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
            $this->authorize('create', AdminRate::class);

            if ($this->isApiRequest($request)) {
                return new ApiSuccessResponse(
                    data: null,
                    message: 'Create form data would be here',
                    statusCode: HttpResponse::HTTP_OK
                );
            }

            return InertiaResponse::content('AdminRates/Create');
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
    public function store(StoreAdminRateRequest $request)
    {
        try {
            $this->authorize('create', AdminRate::class);
            $payload = precognitive(static fn($bail) => $request->validated());
            $adminRate = $this->adminRateRepository->newModel($payload);

            if ($this->isApiRequest($request)) {
                return new ApiSuccessResponse(
                    data: new AdminRateResource($adminRate),
                    metaData: ['action' => 'created'],
                    statusCode: HttpResponse::HTTP_CREATED
                );
            }

            return to_route('admin-rates.index')->with('success', 'Rate has been created.');
        } catch (AuthorizationException $e) {
            Log::error('Authorization error: '.$e->getMessage());
            return $this->handleErrorResponse('Unauthorized', HttpResponse::HTTP_FORBIDDEN, $request);
        } catch (Throwable $e) {
            Log::error('Error creating Admin Rate: '.$e->getMessage());
            return $this->handleErrorResponse('Server Error', HttpResponse::HTTP_INTERNAL_SERVER_ERROR, $request);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, $id)
    {
        try {
            if ($id instanceof AdminRate) {
                // Handle route model binding for web
                $adminRate = $id;
                $this->authorize('view', $adminRate);

                return Inertia::render('Errors/Error', [
                    'status' => HttpResponse::HTTP_NOT_FOUND,
                ]);
            }

            // API request
            $adminRate = $this->adminRateRepository->getById($id);
            $this->authorize('view', $adminRate);

            return new ApiSuccessResponse(
                data: new AdminRateResource($adminRate),
                metaData: ['fetched' => now()->toDateTimeString()],
                statusCode: HttpResponse::HTTP_OK
            );

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
            $adminRate = $this->adminRateRepository->getById($id);
            $this->authorize('update', $adminRate);

            if ($this->isApiRequest($request)) {
                return new ApiSuccessResponse(
                    data: new AdminRateResource($adminRate),
                    message: 'Edit form data would be here',
                    statusCode: HttpResponse::HTTP_OK
                );
            }

            return InertiaResponse::content('AdminRates/Edit', [
                'resource' => new AdminRateResource($adminRate)
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
    public function update(UpdateAdminRateRequest $request, int $id)
    {
        try {
            $adminRate = $this->adminRateRepository->getById($id);
            $this->authorize('update', $adminRate);
            $payload = precognitive(static fn($bail) => $request->validated());
            $updatedAdminRate = $this->adminRateRepository->updateModel($payload, $id);

            if ($this->isApiRequest($request)) {
                return new ApiSuccessResponse(
                    data: new AdminRateResource($updatedAdminRate),
                    metaData: [
                        'action' => 'updated',
                        'updated_at' => now()->toDateTimeString()
                    ],
                    statusCode: HttpResponse::HTTP_OK
                );
            }

            return to_route('admin-rates.index')->with('success', 'Rate has been updated.');
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
            $adminRate = $this->adminRateRepository->getById($id);
            $this->authorize('delete', $adminRate);
            $this->adminRateRepository->deleteModel($id);

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

            return to_route('admin-rates.index')->with('success', 'Rate has been deleted.');
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