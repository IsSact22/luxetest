<?php

namespace App\Http\Controllers;

use App\Contracts\CamoRateRepositoryInterface;
use App\Helpers\InertiaResponse;
use App\Http\Requests\StoreCamoRateRequest;
use App\Http\Requests\UpdateCamoRateRequest;
use App\Http\Resources\CamoRateResource;
use App\Models\CamoRate;
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

class CamoRateController extends Controller
{
    public function __construct(protected CamoRateRepositoryInterface $camoRateRepository)
    {
        parent::__construct();
        $this->middleware([HandlePrecognitiveRequests::class])->only(['store', 'update']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): Response
    {
        try {

            $this->authorize('viewAny', $request->user());
            $camoRates = $this->camoRateRepository->getAll($request);
            $resource = CamoRateResource::collection($camoRates);

            return InertiaResponse::content('CamoRates/Index', ['resource' => $resource]);
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
            $this->authorize('create', [$request->user(), CamoRate::class]);

            return InertiaResponse::content('CamoRates/Create');
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
            $this->authorize('create', [request()->user(), CamoRate::class]);
            $this->camoRateRepository->newCamoRate($request->all());

            return to_route('camo-rates.index')->with('success', 'Camo Rate has been created.');
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
            $camoRate = $this->camoRateRepository->getById($id);
            $this->authorize('view', [request()->user(), CamoRate::class]);
            $resource = new CamoRateResource($camoRate);

            return InertiaResponse::content('CamoRate/Show', ['resource' => $resource]);
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
    public function edit(int $id): Response
    {
        try {
            $camoRate = $this->camoRateRepository->getById($id);
            $this->authorize('view', [request()->user(), $camoRate]);

            $resource = new CamoRateResource($camoRate);

            return InertiaResponse::content('CamoRates/Edit', ['resource' => $resource]);
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
            $this->authorize('update', [$request->user(), CamoRate::class]);
            $this->camoRateRepository->updateCamoRate($request->all(), $id);

            return to_route('camo-rates.index')->with('success', 'Camo Rate has been updated.');
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
    public function destroy(int $id): RedirectResponse|Response
    {
        try {
            $this->authorize('delete', [request()->user(), CamoRate::class]);
            $this->camoRateRepository->deleteCamoRate($id);

            return to_route('camo-rates.index')->with('success', 'Camo Rate has been deleted.');
        } catch (ModelNotFoundException) {
            return Inertia::render('Errors/Error', ['status' => ResponseAlias::HTTP_NOT_FOUND]);
        } catch (Throwable) {
            return Inertia::render('Errors/Error', ['status' => ResponseAlias::HTTP_INTERNAL_SERVER_ERROR]);
        }
    }
}
