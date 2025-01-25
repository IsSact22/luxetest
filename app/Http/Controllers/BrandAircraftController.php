<?php

namespace App\Http\Controllers;

use App\Contracts\BrandAircraftRepositoryInterface;
use App\Helpers\InertiaResponse;
use App\Http\Requests\StoreBrandAircraftRequest;
use App\Http\Requests\UpdateBrandAircraftRequest;
use App\Http\Resources\BrandAircraftResource;
use App\Models\BrandAircraft;
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

class BrandAircraftController extends Controller
{
    public function __construct(protected BrandAircraftRepositoryInterface $brandAircraftRepository)
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
            $this->authorize('viewAny', BrandAircraft::class);
            $engineType = $this->brandAircraftRepository->getAll($request);
            $resource = BrandAircraftResource::collection($engineType);

            return InertiaResponse::content('BrandAircrafts/Index', ['resource' => $resource]);
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
            $this->authorize('create', BrandAircraft::class);

            return InertiaResponse::content('BrandAircrafts/Create');
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
    public function store(StoreBrandAircraftRequest $request): Response|RedirectResponse
    {
        try {
            $this->authorize('create', BrandAircraft::class);
            $payload = precognitive(static fn ($bail) => $request->validated());
            $this->brandAircraftRepository->newBrandAircraft($payload);

            return to_route('brand-aircrafts.index')->with('success', 'Brand Aircraft has been created.');
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
            $brandAircraft = $this->brandAircraftRepository->getById($id);
            $this->authorize('view', $brandAircraft);
            $resource = new BrandAircraftResource($brandAircraft);

            return InertiaResponse::content('BrandAircrafts/Show', ['resource' => $resource]);
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
            $brandAircraft = $this->brandAircraftRepository->getById($id);
            $this->authorize('view', $brandAircraft);

            $resource = new BrandAircraftResource($brandAircraft);

            return InertiaResponse::content('BrandAircrafts/Edit', ['resource' => $resource]);
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
    public function update(UpdateBrandAircraftRequest $request, int $id): Response|RedirectResponse
    {
        try {
            $brandAircraft = $this->brandAircraftRepository->getById($id);
            $this->authorize('update', $brandAircraft);
            $payload = precognitive(static fn ($bail) => $request->validated());
            $this->brandAircraftRepository->updateBrandAircraft($payload, $id);

            return to_route('brand-aircrafts.index')->with('success', 'Brand Aircraft has been updated.');
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
            $brandAircraft = $this->brandAircraftRepository->getById($id);
            $this->authorize('delete', $brandAircraft);
            $this->brandAircraftRepository->deleteBrandAircraft($id);

            return to_route('brand-aircrafts.index')->with('success', 'Brand Aircraft has been deleted.');
        } catch (ModelNotFoundException) {
            return Inertia::render('Errors/Error', ['status' => ResponseAlias::HTTP_NOT_FOUND]);
        } catch (Throwable) {
            return Inertia::render('Errors/Error', ['status' => ResponseAlias::HTTP_INTERNAL_SERVER_ERROR]);
        }
    }
}
