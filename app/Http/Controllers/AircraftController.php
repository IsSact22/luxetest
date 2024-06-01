<?php

namespace App\Http\Controllers;

use App\Contracts\AircraftRepositoryInterface;
use App\Helpers\InertiaResponse;
use App\Http\Requests\StoreAircraftRequest;
use App\Http\Requests\UpdateAircraftRequest;
use App\Http\Resources\AircraftResource;
use App\Models\Aircraft;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Http\Middleware\HandlePrecognitiveRequests as Precongnitive;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Inertia\Response;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;
use Throwable;

class AircraftController extends Controller
{
    public function __construct(protected AircraftRepositoryInterface $aircraftRepository)
    {
        parent::__construct();
        $this->middleware(Precongnitive::class)->only(['store', 'update']);
    }

    public function index(Request $request): Response
    {
        try {
            $this->authorize('viewAny', Aircraft::class);
            $engineType = $this->aircraftRepository->getAll($request);
            $resource = AircraftResource::collection($engineType);

            return InertiaResponse::content('Aircrafts/Index', ['resource' => $resource]);
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

    public function create(Request $request): Response
    {
        try {
            $this->authorize('view', Aircraft::class);

            return InertiaResponse::content('Aircrafts/Create');
        } catch (AuthorizationException) {
            return Inertia::render('Errors/Error', ['status' => ResponseAlias::HTTP_UNAUTHORIZED]);
        } catch (Throwable $e) {
            Log::error($e->getMessage());

            return Inertia::render('Errors/Error', ['status' => ResponseAlias::HTTP_INTERNAL_SERVER_ERROR]);
        }
    }

    public function store(StoreAircraftRequest $request): Response|RedirectResponse
    {
        try {
            $this->authorize('create', Aircraft::class);
            $payload = precognitive(static fn ($bail) => $request->validated());
            $this->aircraftRepository->newModel($payload);

            return to_route('aircrafts.index')->with('success', 'Aircraft has been created.');
        } catch (AuthorizationException) {
            return Inertia::render('Errors/Error', ['status' => ResponseAlias::HTTP_UNAUTHORIZED]);
        } catch (Throwable $e) {
            Log::error($e->getMessage());

            return Inertia::render('Errors/Error', ['status' => ResponseAlias::HTTP_INTERNAL_SERVER_ERROR]);
        }
    }

    public function show(Aircraft $aircraft): Response
    {
        return Inertia::render('Errors/error', [
            'status' => ResponseAlias::HTTP_NOT_FOUND,
        ]);
    }

    public function edit(Request $request, int $id): Response
    {
        try {
            $aircraft = $this->aircraftRepository->getById($id);
            $this->authorize('update-aircraft', $aircraft);

            $resource = new AircraftResource($aircraft);

            return InertiaResponse::content('Aircrafts/Edit', ['resource' => $resource]);
        } catch (AuthorizationException) {
            return Inertia::render('Errors/Error', ['status' => ResponseAlias::HTTP_UNAUTHORIZED]);
        } catch (ModelNotFoundException) {
            return Inertia::render('Errors/Error', ['status' => ResponseAlias::HTTP_NOT_FOUND]);
        } catch (Throwable $e) {
            Log::error($e->getMessage());

            return Inertia::render('Errors/Error', ['status' => ResponseAlias::HTTP_INTERNAL_SERVER_ERROR]);
        }
    }

    public function update(UpdateAircraftRequest $request, int $id): Response|RedirectResponse
    {
        try {
            $this->authorize('update', Aircraft::class);
            $this->aircraftRepository->updateModel($request->all(), $id);

            return to_route('aircrafts.index')->with('success', 'Aircraft has been updated.');
        } catch (AuthorizationException) {
            return Inertia::render('Errors/Error', ['status' => ResponseAlias::HTTP_UNAUTHORIZED]);
        } catch (ModelNotFoundException) {
            return Inertia::render('Errors/Error', ['status' => ResponseAlias::HTTP_NOT_FOUND]);
        } catch (Throwable $e) {
            Log::error($e->getMessage());

            return Inertia::render('Errors/Error', ['status' => ResponseAlias::HTTP_INTERNAL_SERVER_ERROR]);
        }
    }

    public function destroy(Request $request, int $id): Response|RedirectResponse
    {
        try {
            $this->authorize('delete', Aircraft::class);
            $this->aircraftRepository->deleteModel($id);

            return to_route('aircrafts.index')->with('success', 'Aircraft has been deleted.');
        } catch (ModelNotFoundException) {
            return Inertia::render('Errors/Error', ['status' => ResponseAlias::HTTP_NOT_FOUND]);
        } catch (Throwable) {
            return Inertia::render('Errors/Error', ['status' => ResponseAlias::HTTP_INTERNAL_SERVER_ERROR]);
        }
    }
}
