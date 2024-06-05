<?php

namespace App\Http\Controllers;

use App\Contracts\EngineTypeRepositoryInterface;
use App\Helpers\InertiaResponse;
use App\Http\Requests\StoreEngineTypeRequest;
use App\Http\Requests\UpdateEngineTypeRequest;
use App\Http\Resources\EngineTypeResource;
use App\Http\Resources\LaborRateResource;
use App\Models\EngineType;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Http\Middleware\HandlePrecognitiveRequests as Precognitive;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Inertia\Response;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;
use Throwable;

class EngineTypeController extends Controller
{
    public function __construct(protected EngineTypeRepositoryInterface $engineTypeRepository)
    {
        parent::__construct();
        $this->middleware(Precognitive::class)->only(['store', 'update']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): Response
    {
        try {
            $this->authorize('viewAny', EngineType::class);
            $engineType = $this->engineTypeRepository->getAll($request);
            $resource = EngineTypeResource::collection($engineType);

            return InertiaResponse::content('EngineTypes/Index', ['resource' => $resource]);
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
    public function create(Request $request): Response
    {
        try {
            $this->authorize('create', EngineType::class);

            return InertiaResponse::content('EngineTypes/Create');
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
    public function store(StoreEngineTypeRequest $request): Response|RedirectResponse
    {
        try {
            $this->authorize('create', EngineType::class);
            $payload = precognitive(static fn ($bail) => $request->validated());
            $this->engineTypeRepository->newEngineType($payload);

            return to_route('engine-types.index')->with('success', 'Camo Rate has been created.');
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
    public function show(Request $request, int $id): Response
    {
        try {
            $camoRate = $this->engineTypeRepository->getById($id);
            $this->authorize('view', $camoRate);
            $resource = new EngineTypeResource($camoRate);

            return InertiaResponse::content('EngineTypes/Show', ['resource' => $resource]);
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
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, int $id): Response
    {
        try {
            $camoRate = $this->engineTypeRepository->getById($id);
            $this->authorize('update', $camoRate);

            $resource = new LaborRateResource($camoRate);

            return InertiaResponse::content('EngineTypes/Edit', ['resource' => $resource]);
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
     * Update the specified resource in storage.
     */
    public function update(UpdateEngineTypeRequest $request, int $id): Response|RedirectResponse
    {
        try {
            $engineType = $this->engineTypeRepository->getById($id);
            $this->authorize('update', $engineType);
            $payload = precognitive(static fn ($bail) => $request->validated());
            $this->engineTypeRepository->updateEngineType($payload, $id);

            return to_route('engine-types.index')->with('success', 'Camo Rate has been updated.');
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
    public function destroy(Request $request, int $id): RedirectResponse|Response
    {
        try {
            $engineType = $this->engineTypeRepository->getById($id);
            $this->authorize('delete', $engineType);
            $this->engineTypeRepository->deleteEngineType($id);

            return to_route('engine-types.index')->with('success', 'Camo Rate has been deleted.');
        } catch (ModelNotFoundException) {
            return Inertia::render('Errors/Error', ['status' => ResponseAlias::HTTP_NOT_FOUND]);
        } catch (Throwable) {
            return Inertia::render('Errors/Error', ['status' => ResponseAlias::HTTP_INTERNAL_SERVER_ERROR]);
        }
    }
}
