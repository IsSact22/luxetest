<?php

namespace App\Http\Controllers\Api\v1;
use App\Http\Controllers\Controller;

use App\Contracts\RoleRepositoryInterface;
use App\Helpers\InertiaResponse;
use App\Http\Requests\StoreRoleRequest;
use App\Http\Requests\UpdateRoleRequest;
use App\Http\Resources\RoleResource;
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
use Spatie\Permission\Models\Role;
use Symfony\Component\HttpFoundation\Response as HttpResponse;
use Throwable;

class RoleController extends Controller
{
    public function __construct(protected RoleRepositoryInterface $roleRepository)
    {
        parent::__construct();
        $this->middleware(HandlePrecognitiveRequests::class)->only(['store', 'update']);
        $this->middleware('auth:api')->only(['apiIndex', 'apiStore', 'apiShow', 'apiUpdate', 'apiDestroy']);
        $this->authorizeResource(Role::class, 'role');
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
            $roles = $this->roleRepository->getAll($request);

            if ($this->isApiRequest($request)) {
                return new ApiSuccessResponse(
                    data: RoleResource::collection($roles),
                    metaData: [
                        'total' => $roles->total(),
                        'per_page' => $roles->perPage(),
                        'current_page' => $roles->currentPage()
                    ],
                    statusCode: HttpResponse::HTTP_OK
                );
            }

            return InertiaResponse::content('Roles/Index', [
                'resource' => RoleResource::collection($roles)
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
        try {
            if ($this->isApiRequest($request)) {
                return new ApiSuccessResponse(
                    data: null,
                    message: 'Create form not available via API',
                    statusCode: HttpResponse::HTTP_OK
                );
            }

            return InertiaResponse::content('Roles/Create');
        } catch (AuthorizationException $e) {
            return $this->handleErrorResponse('Unauthorized', HttpResponse::HTTP_UNAUTHORIZED, $request, $e);
        } catch (Throwable $e) {
            Log::error($e->getMessage());
            return $this->handleErrorResponse($e->getMessage(), HttpResponse::HTTP_INTERNAL_SERVER_ERROR, $request, $e);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRoleRequest $request)
    {
        try {
            $payload = precognitive(static fn ($bail) => $request->validated());
            $role = $this->roleRepository->newModel($payload);
            $role->syncPermissions($payload['permissions'] ?? []);

            if ($this->isApiRequest($request)) {
                return new ApiSuccessResponse(
                    data: new RoleResource($role),
                    metaData: ['action' => 'created'],
                    statusCode: HttpResponse::HTTP_CREATED
                );
            }

            return to_route('roles.index')->with('success', 'Role created successfully');
        } catch (AuthorizationException $e) {
            Log::error('Authorization error: '.$e->getMessage());
            return $this->handleErrorResponse('Unauthorized', HttpResponse::HTTP_FORBIDDEN, $request, $e);
        } catch (Throwable $e) {
            Log::error('Error creating Role: '.$e->getMessage());
            return $this->handleErrorResponse('Server Error', HttpResponse::HTTP_INTERNAL_SERVER_ERROR, $request, $e);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, $role)
    {
        try {
            $role = is_string($role) ? $this->roleRepository->getById($role) : $role;
            $this->authorize('view', $role);

            if ($this->isApiRequest($request)) {
                return new ApiSuccessResponse(
                    data: new RoleResource($role),
                    message: 'Role retrieved successfully',
                    statusCode: HttpResponse::HTTP_OK
                );
            }

            return InertiaResponse::content('Roles/Show', [
                'resource' => new RoleResource($role)
            ]);
        } catch (ModelNotFoundException $e) {
            return $this->handleErrorResponse('Not Found', HttpResponse::HTTP_NOT_FOUND, $request, $e);
        } catch (AuthorizationException $e) {
            return $this->handleErrorResponse('Unauthorized', HttpResponse::HTTP_FORBIDDEN, $request, $e);
        } catch (Throwable $e) {
            Log::error($e->getMessage());
            return $this->handleErrorResponse('Server Error', HttpResponse::HTTP_INTERNAL_SERVER_ERROR, $request, $e);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, $role)
    {
        try {
            $role = is_string($role) ? $this->roleRepository->getById($role) : $role;
            $this->authorize('update', $role);

            if ($this->isApiRequest($request)) {
                return new ApiSuccessResponse(
                    data: new RoleResource($role),
                    message: 'Edit form not available via API',
                    statusCode: HttpResponse::HTTP_OK
                );
            }

            return InertiaResponse::content('Roles/Edit', [
                'resource' => new RoleResource($role)
            ]);
        } catch (ModelNotFoundException $e) {
            return $this->handleErrorResponse('Not Found', HttpResponse::HTTP_NOT_FOUND, $request, $e);
        } catch (Throwable $e) {
            Log::error($e->getMessage());
            return $this->handleErrorResponse('Server Error', HttpResponse::HTTP_INTERNAL_SERVER_ERROR, $request, $e);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRoleRequest $request, $id)
    {
        try {
            $role = $this->roleRepository->getById($id);
            $this->authorize('update', $role);
            $payload = precognitive(static fn ($bail) => $request->validated());
            
            $updatedRole = $this->roleRepository->updateModel($payload, $id);
            $updatedRole->syncPermissions($payload['permissions'] ?? []);

            if ($this->isApiRequest($request)) {
                return new ApiSuccessResponse(
                    data: new RoleResource($updatedRole),
                    metaData: [
                        'action' => 'updated',
                        'updated_at' => now()->toDateTimeString()
                    ],
                    statusCode: HttpResponse::HTTP_OK
                );
            }

            return to_route('roles.index')->with('success', 'Role updated successfully');
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
    public function destroy(Request $request, $id)
    {
        try {
            $role = $this->roleRepository->getById($id);
            $this->authorize('delete', $role);
            $this->roleRepository->deleteModel($id);

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

            return to_route('roles.index')->with('success', 'Role deleted successfully');
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