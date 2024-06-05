<?php

namespace App\Http\Controllers;

use App\Contracts\LaborRateRepositoryInterface;
use App\Helpers\InertiaResponse;
use App\Http\Requests\StoreCamoRateRequest;
use App\Http\Requests\UpdateCamoRateRequest;
use App\Http\Resources\LaborRateResource;
use App\Models\LaborRate;
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

class LaborRateController extends Controller
{
    public function __construct(protected LaborRateRepositoryInterface $laborRateRepository)
    {
        parent::__construct();
        $this->middleware([Precognitive::class])->only(['store', 'update']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): Response
    {
        try {

            $this->authorize('viewAny', LaborRate::class);
            $laborRates = $this->laborRateRepository->getAll($request);
            $resource = LaborRateResource::collection($laborRates);

            return InertiaResponse::content('LaborRates/Index', ['resource' => $resource]);
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
            $this->authorize('create', LaborRate::class);

            return InertiaResponse::content('LaborRates/Create');
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
    public function store(StoreCamoRateRequest $request): Response|RedirectResponse
    {
        try {
            $this->authorize('create', LaborRate::class);
            $payload = precognitive(static fn ($bail) => $request->validated());
            $this->laborRateRepository->newModel($payload);

            return to_route('labor-rates.index')->with('success', 'Camo Rate has been created.');
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
            $laborRate = $this->laborRateRepository->getById($id);
            $this->authorize('view', $laborRate);
            $resource = new LaborRateResource($laborRate);

            return InertiaResponse::content('LaborRates/Show', ['resource' => $resource]);
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
            $laborRate = $this->laborRateRepository->getById($id);
            $this->authorize('view', $laborRate);

            $resource = new LaborRateResource($laborRate);

            return InertiaResponse::content('LaborRates/Edit', ['resource' => $resource]);
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
    public function update(UpdateCamoRateRequest $request, int $id): Response|RedirectResponse
    {
        try {
            $laborRate = $this->laborRateRepository->getById($id);
            $this->authorize('update', $laborRate);
            $payload = precognitive(static fn ($bail) => $request->validated());
            $this->laborRateRepository->updateModel($payload, $id);

            return to_route('labor-rates.index')->with('success', 'Camo Rate has been updated.');
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
            $laborRate = $this->laborRateRepository->getById($id);
            $this->authorize('delete', $laborRate);
            $this->laborRateRepository->deleteModel($id);

            return to_route('labor-rates.index')->with('success', 'Camo Rate has been deleted.');
        } catch (ModelNotFoundException) {
            return Inertia::render('Errors/Error', ['status' => ResponseAlias::HTTP_NOT_FOUND]);
        } catch (Throwable) {
            return Inertia::render('Errors/Error', ['status' => ResponseAlias::HTTP_INTERNAL_SERVER_ERROR]);
        }
    }
}
