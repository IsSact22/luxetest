<?php

namespace App\Http\Controllers;

use App\Contracts\PermissionRepositoryInterface;
use App\Helpers\InertiaResponse;
use App\Http\Requests\StorePermissionRequest;
use App\Http\Requests\UpdatePermissionRequest;
use App\Http\Resources\PermissionResource;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Http\Middleware\HandlePrecognitiveRequests;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Inertia\Response;
use Spatie\Permission\Models\Permission;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;
use Throwable;

class PermissionController extends Controller
{
    public function __construct(protected PermissionRepositoryInterface $permissionRepository)
    {
        parent::__construct();
        $this->middleware(HandlePrecognitiveRequests::class)->only(['store', 'update']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): Response
    {
        try {
            $this->authorize('viewAny', Permission::class);
            $permissions = $this->permissionRepository->getAll($request);
            $resource = PermissionResource::collection($permissions);

            return InertiaResponse::content('Permissions/Index', ['resource' => $resource]);
        } catch (AuthorizationException) {
            return Inertia::render('Errors/Error', ['status' => ResponseAlias::HTTP_UNAUTHORIZED]);
        } catch (Throwable $e) {
            Log::error($e->getMessage());

            return Inertia::render('Errors/Error', [
                'status' => ResponseAlias::HTTP_INTERNAL_SERVER_ERROR,
                'description' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): Response
    {
        return Inertia::render('Errors/Error', ['status' => ResponseAlias::HTTP_NOT_FOUND]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePermissionRequest $request): Response|RedirectResponse
    {
        try {
            $this->authorize('create', Permission::class);
            $payload = precognitive(static fn ($bail) => $request->validated());
            $this->permissionRepository->newModel($payload);

            return to_route('permissions.index')->with('success', 'Permission has been created.');
        } catch (AuthorizationException) {
            return Inertia::render('Errors/Error', ['status' => ResponseAlias::HTTP_UNAUTHORIZED]);
        } catch (Throwable $e) {
            Log::error($e->getMessage());

            return Inertia::render('Errors/Error', ['status' => ResponseAlias::HTTP_INTERNAL_SERVER_ERROR]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Permission $permission): Response
    {
        return Inertia::render('Errors/Error', ['status' => ResponseAlias::HTTP_NOT_FOUND]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Permission $permission): Response
    {
        return Inertia::render('Errors/Error', ['status' => ResponseAlias::HTTP_NOT_FOUND]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(
        UpdatePermissionRequest $request,
        int $id
    ): Response|RedirectResponse {
        try {
            $this->authorize('update', Permission::class);
            $payload = precognitive(static fn ($bail) => $request->validated());
            $this->permissionRepository->updateModel($payload, $id);

            return to_route('permissions.index')->with('success', 'Permission has been updated.');
        } catch (AuthorizationException) {
            return Inertia::render('Errors/Error', ['status' => ResponseAlias::HTTP_UNAUTHORIZED]);
        } catch (ModelNotFoundException) {
            return Inertia::render('Errors/Error', ['status' => ResponseAlias::HTTP_NOT_FOUND]);
        } catch (Throwable $e) {
            Log::error($e->getMessage());

            return Inertia::render('Errors/Error', ['status' => ResponseAlias::HTTP_INTERNAL_SERVER_ERROR]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, int $id): Response|RedirectResponse
    {
        try {
            $this->authorize('delete', Permission::class);
            $this->permissionRepository->deleteModel($id);

            return to_route('permissions.index')->with('success', 'Permission has been deleted.');
        } catch (ModelNotFoundException) {
            return Inertia::render('Errors/Error', ['status' => ResponseAlias::HTTP_NOT_FOUND]);
        } catch (Throwable) {
            return Inertia::render('Errors/Error', ['status' => ResponseAlias::HTTP_INTERNAL_SERVER_ERROR]);
        }
    }
}
