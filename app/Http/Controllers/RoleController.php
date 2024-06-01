<?php

namespace App\Http\Controllers;

use App\Contracts\RoleRepositoryInterface;
use App\Helpers\InertiaResponse;
use App\Http\Requests\StoreRoleRequest;
use App\Http\Resources\RoleResource;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Http\Middleware\HandlePrecognitiveRequests;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Inertia\Response;
use Spatie\Permission\Models\Role;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;
use Throwable;

class RoleController extends Controller
{
    public function __construct(protected RoleRepositoryInterface $role)
    {
        parent::__construct();
        $this->middleware(HandlePrecognitiveRequests::class)->only(['store', 'update']);
        $this->authorizeResource(Role::class, 'role');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): Response
    {
        $roles = $this->role->getAll($request);
        $resource = RoleResource::collection($roles);

        return InertiaResponse::content('Roles/Index', ['resource' => $resource]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): Response
    {
        return InertiaResponse::content('Roles/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRoleRequest $request): Response|RedirectResponse
    {
        try {
            $this->authorize('create', Role::class);
            $payload = precognitive(static fn ($bail) => $request->validated());

            $role = $this->role->newModel($payload);
            $role->syncPermissions($payload['permissions']);

            return to_route('roles.index')->with('success', 'Role created successfully');
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
    public function show(string $id): Response
    {
        try {
            $user = $this->role->getById($id);
            $resource = new RoleResource($user);

            return InertiaResponse::content('Roles/Show', ['resource' => $resource]);
        } catch (ModelNotFoundException) {
            return Inertia::render('Errors/Error', ['status' => ResponseAlias::HTTP_NOT_FOUND]);
        } catch (Throwable) {
            return Inertia::render('Errors/Error', ['status' => ResponseAlias::HTTP_INTERNAL_SERVER_ERROR]);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id): Response
    {
        try {
            $user = $this->role->getById($id);
            $resource = new RoleResource($user);

            return InertiaResponse::content('Roles/Edit', ['resource' => $resource]);
        } catch (ModelNotFoundException) {
            return Inertia::render('Errors/Error', ['status' => ResponseAlias::HTTP_NOT_FOUND]);
        } catch (Throwable) {
            return Inertia::render('Errors/Error', ['status' => ResponseAlias::HTTP_INTERNAL_SERVER_ERROR]);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id): Response|RedirectResponse
    {
        try {
            $this->role->updateModel($request->all(), $id);

            return to_route('roles.index')->with('success', 'Role updated successfully');
        } catch (ModelNotFoundException) {
            return Inertia::render('Errors/Error', ['status' => ResponseAlias::HTTP_NOT_FOUND]);
        } catch (Throwable) {
            return Inertia::render('Errors/Error', ['status' => ResponseAlias::HTTP_INTERNAL_SERVER_ERROR]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): Response|RedirectResponse
    {
        try {
            $this->role->deleteModel($id);

            return to_route('services.index')->with('success', 'Role deleted successfully');
        } catch (ModelNotFoundException) {
            return Inertia::render('Errors/Error', ['status' => ResponseAlias::HTTP_NOT_FOUND]);
        } catch (Throwable) {
            return Inertia::render('Errors/Error', ['status' => ResponseAlias::HTTP_INTERNAL_SERVER_ERROR]);
        }
    }
}
