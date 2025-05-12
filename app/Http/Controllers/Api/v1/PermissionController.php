<?php

namespace App\Http\Controllers\Api\v1;
use App\Http\Controllers\Controller;

use App\Contracts\PermissionRepositoryInterface;
use App\Helpers\InertiaResponse;
use App\Http\Requests\StorePermissionRequest;
use App\Http\Requests\UpdatePermissionRequest;
use App\Http\Resources\PermissionResource;
use App\Http\Responses\ApiErrorResponse;
use App\Http\Responses\ApiSuccessResponse;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Http\Middleware\HandlePrecognitiveRequests;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Inertia\Response;
use Spatie\Permission\Models\Permission;
use Symfony\Component\HttpFoundation\Response as HttpResponse;
use Throwable;

class PermissionController extends Controller
{
    public function __construct(protected PermissionRepositoryInterface $permissionRepository)
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
        return $request->is('api/*') || $request->wantsJson();
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $this->authorize('viewAny', Permission::class);
            $permissions = $this->permissionRepository->getAll($request);

            if ($this->isApiRequest($request)) {
                return new ApiSuccessResponse(
                    data: PermissionResource::collection($permissions),
                    metaData: [
                        'total' => $permissions->total(),
                        'per_page' => $permissions->perPage(),
                        'current_page' => $permissions->currentPage()
                    ],
                    statusCode: HttpResponse::HTTP_OK
                );
            }

            return InertiaResponse::content('Permissions/Index', [
                'resource' => PermissionResource::collection($permissions)
            ]);

        } catch (AuthorizationException $e) {
            return $this->handleErrorResponse('Unauthorized', HttpResponse::HTTP_UNAUTHORIZED, $request, $e);
        } catch (Throwable $e) {
            Log::error($e->getMessage());
            return $this->handleErrorResponse($e->getMessage(), HttpResponse::HTTP_INTERNAL_SERVER_ERROR, $request, $e);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        if ($this->isApiRequest($request)) {
            return new ApiSuccessResponse(
                data: null,
                message: 'Create form not available via API',
                statusCode: HttpResponse::HTTP_OK
            );
        }

        return Inertia::render('Errors/Error', ['status' => HttpResponse::HTTP_NOT_FOUND]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePermissionRequest $request)
    {
        try {
            $this->authorize('create', Permission::class);
            $payload = precognitive(static fn ($bail) => $request->validated());
            $permission = $this->permissionRepository->newModel($payload);

            if ($this->isApiRequest($request)) {
                return new ApiSuccessResponse(
                    data: new PermissionResource($permission),
                    metaData: ['action' => 'created'],
                    statusCode: HttpResponse::HTTP_CREATED
                );
            }

            return to_route('permissions.index')->with('success', 'Permission has been created.');
        } catch (AuthorizationException $e) {
            Log::error('Authorization error: '.$e->getMessage());
            return $this->handleErrorResponse('Unauthorized', HttpResponse::HTTP_FORBIDDEN, $request, $e);
        } catch (Throwable $e) {
            Log::error('Error creating Permission: '.$e->getMessage());
            return $this->handleErrorResponse('Server Error', HttpResponse::HTTP_INTERNAL_SERVER_ERROR, $request, $e);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, $permission)
    {
        if ($this->isApiRequest($request)) {
            try {
                $permission = is_int($permission) ? $this->permissionRepository->getById($permission) : $permission;
                $this->authorize('view', $permission);

                return new ApiSuccessResponse(
                    data: new PermissionResource($permission),
                    message: 'Permission retrieved successfully',
                    statusCode: HttpResponse::HTTP_OK
                );
            } catch (ModelNotFoundException $e) {
                return $this->handleErrorResponse('Not Found', HttpResponse::HTTP_NOT_FOUND, $request, $e);
            } catch (AuthorizationException $e) {
                return $this->handleErrorResponse('Unauthorized', HttpResponse::HTTP_FORBIDDEN, $request, $e);
            } catch (Throwable $e) {
                Log::error($e->getMessage());
                return $this->handleErrorResponse('Server Error', HttpResponse::HTTP_INTERNAL_SERVER_ERROR, $request, $e);
            }
        }

        return Inertia::render('Errors/Error', ['status' => HttpResponse::HTTP_NOT_FOUND]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, Permission $permission)
    {
        if ($this->isApiRequest($request)) {
            return new ApiSuccessResponse(
                data: new PermissionResource($permission),
                message: 'Edit form not available via API',
                statusCode: HttpResponse::HTTP_OK
            );
        }

        return Inertia::render('Errors/Error', ['status' => HttpResponse::HTTP_NOT_FOUND]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePermissionRequest $request, int $id)
    {
        try {
            $permission = $this->permissionRepository->getById($id);
            $this->authorize('update', $permission);
            $payload = precognitive(static fn ($bail) => $request->validated());
            $updatedPermission = $this->permissionRepository->updateModel($payload, $id);

            if ($this->isApiRequest($request)) {
                return new ApiSuccessResponse(
                    data: new PermissionResource($updatedPermission),
                    metaData: [
                        'action' => 'updated',
                        'updated_at' => now()->toDateTimeString()
                    ],
                    statusCode: HttpResponse::HTTP_OK
                );
            }

            return to_route('permissions.index')->with('success', 'Permission has been updated.');
        } catch (AuthorizationException $e) {
            return $this->handleErrorResponse('Unauthorized', HttpResponse::HTTP_FORBIDDEN, $request, $e);
        } catch (ModelNotFoundException $e) {
            return $this->handleErrorResponse('Not Found', HttpResponse::HTTP_NOT_FOUND, $request, $e);
        } catch (Throwable $e) {
            Log::error($e->getMessage());
            return $this->handleErrorResponse('Server Error', HttpResponse::HTTP_INTERNAL_SERVER_ERROR, $request, $e);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, int $id)
    {
        try {
            $permission = $this->permissionRepository->getById($id);
            $this->authorize('delete', $permission);
            $this->permissionRepository->deleteModel($id);

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

            return to_route('permissions.index')->with('success', 'Permission has been deleted.');
        } catch (ModelNotFoundException $e) {
            return $this->handleErrorResponse('Not Found', HttpResponse::HTTP_NOT_FOUND, $request, $e);
        } catch (Throwable $e) {
            Log::error($e->getMessage());
            return $this->handleErrorResponse('Server Error', HttpResponse::HTTP_INTERNAL_SERVER_ERROR, $request, $e);
        }
    }

    /**
     * Maneja las respuestas de error de forma unificada
     */
    protected function handleErrorResponse(
        string $message, 
        int $statusCode, 
        Request $request,
        ?Throwable $exception = null
    ) {
        if ($this->isApiRequest($request)) {
            return new ApiErrorResponse(
                message: $message,
                statusCode: $statusCode,
                exception: $exception
            );
        }

        return Inertia::render('Errors/Error', [
            'status' => $statusCode,
            'message' => $message
        ]);
    }
}