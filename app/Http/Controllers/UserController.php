<?php

namespace App\Http\Controllers;

use App\Contracts\UserRepositoryInterface;
use App\Helpers\InertiaResponse;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Http\Middleware\HandlePrecognitiveRequests;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Inertia\Response;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;
use Throwable;

class UserController extends Controller
{
    public function __construct(protected UserRepositoryInterface $userRepository)
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
            $this->authorize('viewAny', User::class);
            $users = $this->userRepository->getAll($request);
            $resource = UserResource::collection($users);

            return InertiaResponse::content('Users/Index', ['resource' => $resource]);
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
        try {
            $this->authorize('create', User::class);

            return InertiaResponse::content('Users/Create');
        } catch (AuthorizationException) {
            return Inertia::render('Errors/Error', ['status' => ResponseAlias::HTTP_UNAUTHORIZED]);
        } catch (Throwable $e) {
            Log::error($e->getMessage());

            return Inertia::render('Errors/Error', ['status' => ResponseAlias::HTTP_INTERNAL_SERVER_ERROR]);
        }

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request): Response|RedirectResponse
    {
        try {
            $this->authorize('create', User::class);
            $payload = precognitive(static fn ($bail) => $request->validated());
            $user = $this->userRepository->newModel($payload);
            $user->assignRole($request->get('role'));

            if ($request->hasFile('avatar')) {
                $user->addMediaFromRequest('avatar')->toMediaCollection('avatars');
            }

            return to_route('users.index')->with('success', 'User created successfully');
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
    public function show(int $id): Response
    {
        try {
            $this->authorize('view', User::class);

            $user = $this->userRepository->getById($id);
            $resource = new UserResource($user);

            return InertiaResponse::content('Users/Show', ['resource' => $resource]);
        } catch (ModelNotFoundException) {
            return Inertia::render('Errors/Error', ['status' => ResponseAlias::HTTP_NOT_FOUND]);
        } catch (AuthorizationException) {
            return Inertia::render('Errors/Error', ['status' => ResponseAlias::HTTP_UNAUTHORIZED]);
        } catch (Throwable $e) {
            Log::error('showUser:'.$e->getMessage());

            return Inertia::render('Errors/Error', ['status' => ResponseAlias::HTTP_INTERNAL_SERVER_ERROR]);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(int $id): Response
    {
        try {
            $this->authorize('update', User::class);

            $user = $this->userRepository->getById($id);
            $resource = new UserResource($user);

            return InertiaResponse::content('Users/Edit', ['resource' => $resource]);
        } catch (ModelNotFoundException) {
            return Inertia::render('Errors/Error', ['status' => ResponseAlias::HTTP_NOT_FOUND]);
        } catch (Throwable $e) {
            Log::error('editUser:'.$e->getMessage());

            return Inertia::render('Errors/Error', ['status' => ResponseAlias::HTTP_INTERNAL_SERVER_ERROR]);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, int $id): Response|RedirectResponse
    {
        try {
            $this->authorize('update', User::class);

            $user = $this->userRepository->updateModel($request->all(), $id);

            if ($request->hasFile('avatar')) {
                $user->addMediaFromRequest('avatar')
                    ->toMediaCollection('avatars');
            }

            return to_route('users.index')->with([
                'message' => [
                    'type' => 'success',
                    'message' => 'User updated successfully',
                ],
            ]);

        } catch (ModelNotFoundException) {
            return Inertia::render('Errors/Error', ['status' => ResponseAlias::HTTP_NOT_FOUND]);
        } catch (Throwable $e) {
            Log::error('updateUser:'.$e->getMessage());

            return Inertia::render('Errors/Error', ['status' => ResponseAlias::HTTP_INTERNAL_SERVER_ERROR]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id): Response|RedirectResponse
    {
        try {
            $this->authorize('delete', User::class);

            $this->userRepository->deleteModel($id);

            return to_route('users.index')->with('success', 'User deleted successfully');
        } catch (ModelNotFoundException) {
            return Inertia::render('Errors/Error', ['status' => ResponseAlias::HTTP_NOT_FOUND]);
        } catch (Throwable $e) {
            Log::error('deleteUser:'.$e->getMessage());

            return Inertia::render('Errors/Error', [
                'status' => ResponseAlias::HTTP_INTERNAL_SERVER_ERROR,
                'message' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Restore the specified resource from storage.
     */
    public function restore(int $id): Response|RedirectResponse
    {
        try {
            $this->authorize('restore', User::class); // Asegúrate de tener esta autorización configurada.

            $this->userRepository->restoreModel($id); // Llama al método restoreModel de tu repositorio.

            return to_route('users.index')->with('success', 'User restored successfully');
        } catch (ModelNotFoundException) {
            return Inertia::render('Errors/Error', ['status' => ResponseAlias::HTTP_NOT_FOUND]);
        } catch (Throwable $e) {
            Log::error('restoreUser:'.$e->getMessage());

            return Inertia::render('Errors/Error', [
                'status' => ResponseAlias::HTTP_INTERNAL_SERVER_ERROR,
                'message' => $e->getMessage(),
            ]);
        }
    }
}
