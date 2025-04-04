<?php

namespace App\Http\Controllers\Api\v1;

use App\Contracts\UserRepositoryInterface;
use App\Helpers\InertiaResponse;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Http\Responses\ApiErrorResponse;
use App\Http\Responses\ApiSuccessResponse;
use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Http\Middleware\HandlePrecognitiveRequests;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Inertia\Response;
use App\Http\Controllers\Controller;
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
    public function index(Request $request)
    {
        try {
            $this->authorize('viewAny', User::class);
            $users = $this->userRepository->getAll($request);
            $resource = UserResource::collection($users);

            // Para peticiones Inertia
            if ($request->hasHeader('X-Inertia')) {
                return InertiaResponse::content('Users/Index', ['resource' => $resource]);
            }

            // Para peticiones API
            if ($request->expectsJson()) {
                return new ApiSuccessResponse($resource, []);
            }

            // Redirección para acceso directo desde navegador
            return redirect()->route('users.index');
        } catch (AuthorizationException) {
            return $this->handleErrorResponse($request, 'Unauthorized', ResponseAlias::HTTP_UNAUTHORIZED);
        } catch (Throwable $e) {
            Log::error($e->getMessage());
            return $this->handleErrorResponse($request, $e->getMessage(), ResponseAlias::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): Response
    {
        try {
            $this->authorize('create', User::class);

            // Solo para web/Inertia
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
    public function store(StoreUserRequest $request)
    {
        try {
            $this->authorize('create', User::class);
            $payload = precognitive(static fn ($bail) => $request->validated());
            $user = $this->userRepository->newModel($payload);
            $user->assignRole($request->get('role'));

            if ($request->hasFile('avatar')) {
                $user->addMediaFromRequest('avatar')->toMediaCollection('avatars');
            }

            $resource = new UserResource($user);

            // Para peticiones Inertia
            if ($request->hasHeader('X-Inertia')) {
                return to_route('users.index')->with('success', 'User created successfully');
            }

            // Para peticiones API
            return new ApiSuccessResponse($resource, [], ResponseAlias::HTTP_CREATED);
        } catch (AuthorizationException) {
            return $this->handleErrorResponse($request, 'Unauthorized', ResponseAlias::HTTP_UNAUTHORIZED);
        } catch (Throwable $e) {
            Log::error($e->getMessage());
            return $this->handleErrorResponse($request, $e->getMessage(), ResponseAlias::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, int $id)
    {
        try {
            $user = $this->userRepository->getById($id);
            $this->authorize('view', $user);
            $resource = new UserResource($user);

            // Para peticiones Inertia
            if ($request->hasHeader('X-Inertia')) {
                return InertiaResponse::content('Users/Show', ['resource' => $resource]);
            }

            // Para peticiones API
            return new ApiSuccessResponse($resource, []);
        } catch (ModelNotFoundException) {
            return $this->handleErrorResponse($request, 'User Not Found', ResponseAlias::HTTP_NOT_FOUND);
        } catch (AuthorizationException) {
            return $this->handleErrorResponse($request, 'Unauthorized', ResponseAlias::HTTP_UNAUTHORIZED);
        } catch (Throwable $e) {
            Log::error('showUser:'.$e->getMessage());
            return $this->handleErrorResponse($request, $e->getMessage(), ResponseAlias::HTTP_INTERNAL_SERVER_ERROR);
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

            // Solo para web/Inertia
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
    public function update(UpdateUserRequest $request, int $id)
    {
        try {
            $user = $this->userRepository->getById($id);
            $this->authorize('update', $user);

            $this->userRepository->updateModel($request->all(), $id);

            if ($request->hasFile('avatar')) {
                $user->addMediaFromRequest('avatar')
                    ->toMediaCollection('avatars');
            }

            $updatedUser = $this->userRepository->getById($id);
            $resource = new UserResource($updatedUser);

            // Para peticiones Inertia
            if ($request->hasHeader('X-Inertia')) {
                return to_route('users.index')->with([
                    'message' => [
                        'type' => 'success',
                        'message' => 'User updated successfully',
                    ],
                ]);
            }

            // Para peticiones API
            return new ApiSuccessResponse($resource, [], ResponseAlias::HTTP_OK);
        } catch (ModelNotFoundException) {
            return $this->handleErrorResponse($request, 'User Not Found', ResponseAlias::HTTP_NOT_FOUND);
        } catch (Throwable $e) {
            Log::error('updateUser:'.$e->getMessage());
            return $this->handleErrorResponse($request, $e->getMessage(), ResponseAlias::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, int $id)
    {
        try {
            $user = $this->userRepository->getById($id);
            $this->authorize('delete', $user);

            $this->userRepository->deleteModel($id);

            // Para peticiones Inertia
            if ($request->hasHeader('X-Inertia')) {
                return to_route('users.index')->with('success', 'User deleted successfully');
            }

            // Para peticiones API
            return new ApiSuccessResponse(['message' => 'User deleted successfully'], []);
        } catch (ModelNotFoundException) {
            return $this->handleErrorResponse($request, 'User Not Found', ResponseAlias::HTTP_NOT_FOUND);
        } catch (Throwable $e) {
            Log::error('deleteUser:'.$e->getMessage());
            return $this->handleErrorResponse($request, $e->getMessage(), ResponseAlias::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Restore the specified resource from storage.
     */
    public function restore(Request $request, int $id)
    {
        try {
            $this->authorize('restore', User::class);
            $this->userRepository->restoreModel($id);

            // Para peticiones Inertia
            if ($request->hasHeader('X-Inertia')) {
                return to_route('users.index')->with('success', 'User restored successfully');
            }

            // Para peticiones API
            return new ApiSuccessResponse(['message' => 'User restored successfully'], []);
        } catch (ModelNotFoundException) {
            return $this->handleErrorResponse($request, 'User Not Found', ResponseAlias::HTTP_NOT_FOUND);
        } catch (Throwable $e) {
            Log::error('restoreUser:'.$e->getMessage());
            return $this->handleErrorResponse($request, $e->getMessage(), ResponseAlias::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Handle error responses consistently.
     */
    private function handleErrorResponse(Request $request, string $message, int $statusCode)
    {
        // Para peticiones Inertia
        if ($request->hasHeader('X-Inertia')) {
            return Inertia::render('Errors/Error', [
                'status' => $statusCode,
                'description' => $message,
            ]);
        }

        // Para peticiones API
        return new ApiErrorResponse(null, $message, $statusCode);
    }
}