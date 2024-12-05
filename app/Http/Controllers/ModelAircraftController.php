<?php

namespace App\Http\Controllers;

use App\Contracts\ModelAircraftRepositoryInterface;
use App\Helpers\InertiaResponse;
use App\Http\Requests\StoreModelAircraftRequest;
use App\Http\Requests\UpdateModelAircraftRequest;
use App\Http\Resources\ModelAircraftResource;
use App\Models\ModelAircraft;
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

class ModelAircraftController extends Controller
{
    public function __construct(protected ModelAircraftRepositoryInterface $modelAircraftRepository)
    {
        parent::__construct();
        $this->middleware(Precognitive::class)->only(['store', 'update']);
    }

    public function index(Request $request): Response
    {
        try {
            $this->authorize('viewAny', ModelAircraft::class);
            $modelAircraft = $this->modelAircraftRepository->getAll($request);
            $resource = ModelAircraftResource::collection($modelAircraft);

            return InertiaResponse::content('ModelAircrafts/Index', ['resource' => $resource]);
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
            $this->authorize('create', ModelAircraft::class);

            return InertiaResponse::content('ModelAircrafts/Create');
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
    public function store(StoreModelAircraftRequest $request): Response|RedirectResponse
    {
        try {
            $this->authorize('create', ModelAircraft::class);
            $payload = precognitive(static fn($bail) => $request->validated());
            $this->modelAircraftRepository->newModelAircraft($payload);

            return to_route('model-aircrafts.index')->with('success', 'Model Aircraft has been created.');
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
            $modelAircraft = $this->modelAircraftRepository->getById($id);
            $this->authorize('view', $modelAircraft);
            $resource = new ModelAircraftResource($modelAircraft);

            return InertiaResponse::content('ModelAircrafts/Show', ['resource' => $resource]);
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
            $modelAircraft = $this->modelAircraftRepository->getById($id);
            $this->authorize('update', $modelAircraft);

            $resource = new ModelAircraftResource($modelAircraft);

            return InertiaResponse::content('ModelAircrafts/Edit', ['resource' => $resource]);
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
    public function update(UpdateModelAircraftRequest $request, int $id): Response|RedirectResponse
    {
        try {
            $modelAircraft = $this->modelAircraftRepository->getById($id);
            $this->authorize('update', $modelAircraft);
            $payload = precognitive(static fn($bail) => $request->validated());
            $this->modelAircraftRepository->updateModelAircraft($payload, $id);

            return to_route('model-aircrafts.index')->with('success', 'Model Aircraft has been updated.');
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
    public function destroy(int $id): Response|RedirectResponse
    {
        try {
            $modelAircraft = $this->modelAircraftRepository->getById($id);
            $this->authorize('delete', $modelAircraft);
            $this->modelAircraftRepository->deleteModelAircraft($id);

            return to_route('model-aircrafts.index')->with('success', 'Model Aircraft has been deleted.');
        } catch (ModelNotFoundException) {
            return Inertia::render('Errors/Error', ['status' => ResponseAlias::HTTP_NOT_FOUND]);
        } catch (Throwable) {
            return Inertia::render('Errors/Error', ['status' => ResponseAlias::HTTP_INTERNAL_SERVER_ERROR]);
        }
    }
}
