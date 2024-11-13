<?php

namespace App\Http\Controllers;

use App\Contracts\LaborRateRepositoryInterface;
use App\Helpers\InertiaResponse;
use App\Http\Requests\StoreLaborRateRequest;
use App\Http\Requests\UpdateLaborRateRequest;
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
        } catch (AuthorizationException $e) {
            Log::error($e->getMessage());

            return Inertia::render('Errors/Error', [
                'status' => ResponseAlias::HTTP_UNAUTHORIZED,
                'message' => $e->getMessage(),
            ]);
        } catch (Throwable $e) {
            Log::error($e->getMessage());

            return Inertia::render('Errors/Error', [
                'status' => ResponseAlias::HTTP_INTERNAL_SERVER_ERROR,
                'message' => $e->getMessage(),
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
        } catch (AuthorizationException $e) {
            Log::error($e->getMessage());

            return Inertia::render('Errors/Error', [
                'status' => ResponseAlias::HTTP_UNAUTHORIZED,
                'message' => $e->getMessage(),
            ]);
        } catch (Throwable $e) {
            Log::error($e->getMessage());

            return Inertia::render('Errors/Error', [
                'status' => ResponseAlias::HTTP_INTERNAL_SERVER_ERROR,
                'message' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreLaborRateRequest $request): Response|RedirectResponse
    {
        try {
            $this->authorize('create', LaborRate::class);
            $payload = precognitive(static fn ($bail) => $request->validated());
            //dd($payload);
            $this->laborRateRepository->newModel($payload);

            return to_route('labor-rates.index')->with('success', 'Camo Rate has been created.');
        } catch (AuthorizationException $e) {
            Log::error($e->getMessage());

            return Inertia::render('Errors/Error', [
                'status' => ResponseAlias::HTTP_UNAUTHORIZED,
                'message' => $e->getMessage(),
            ]);
        } catch (Throwable $e) {
            Log::error($e->getMessage());

            return Inertia::render('Errors/Error', [
                'status' => ResponseAlias::HTTP_INTERNAL_SERVER_ERROR,
                'message' => $e->getMessage(),
            ]);
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
        } catch (AuthorizationException $e) {
            Log::error($e->getMessage());

            return Inertia::render('Errors/Error', [
                'status' => ResponseAlias::HTTP_UNAUTHORIZED,
                'message' => $e->getMessage(),
            ]);
        } catch (ModelNotFoundException $e) {
            Log::error($e->getMessage());

            return Inertia::render('Errors/Error', [
                'status' => ResponseAlias::HTTP_NOT_FOUND,
                'message' => $e->getMessage(),
            ]);
        } catch (Throwable $e) {
            Log::error($e->getMessage());

            return Inertia::render('Errors/Error', [
                'status' => ResponseAlias::HTTP_INTERNAL_SERVER_ERROR,
                'message' => $e->getMessage(),
            ]);
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
        } catch (AuthorizationException $e) {
            Log::error($e->getMessage());

            return Inertia::render('Errors/Error', [
                'status' => ResponseAlias::HTTP_UNAUTHORIZED,
                'message' => $e->getMessage(),
            ]);
        } catch (ModelNotFoundException $e) {
            Log::error($e->getMessage());

            return Inertia::render('Errors/Error', [
                'status' => ResponseAlias::HTTP_NOT_FOUND,
                'message' => $e->getMessage(),
            ]);
        } catch (Throwable $e) {
            Log::error($e->getMessage());

            return Inertia::render('Errors/Error', [
                'status' => ResponseAlias::HTTP_INTERNAL_SERVER_ERROR,
                'message' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateLaborRateRequest $request, int $id): Response|RedirectResponse
    {
        try {
            $laborRate = $this->laborRateRepository->getById($id);
            $this->authorize('update', $laborRate);
            $payload = precognitive(static fn ($bail) => $request->validated());
            $this->laborRateRepository->updateModel($payload, $id);

            return to_route('labor-rates.index')->with('success', 'Camo Rate has been updated.');
        } catch (AuthorizationException $e) {
            Log::error($e->getMessage());

            return Inertia::render('Errors/Error', [
                'status' => ResponseAlias::HTTP_UNAUTHORIZED,
                'message' => $e->getMessage(),
            ]);
        } catch (ModelNotFoundException $e) {
            Log::error($e->getMessage());

            return Inertia::render('Errors/Error', [
                'status' => ResponseAlias::HTTP_NOT_FOUND,
                'message' => $e->getMessage(),
            ]);
        } catch (Throwable $e) {
            Log::error($e->getMessage());

            return Inertia::render('Errors/Error', [
                'status' => ResponseAlias::HTTP_INTERNAL_SERVER_ERROR,
                'message' => $e->getMessage(),
            ]);
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
        } catch (ModelNotFoundException $e) {
            return Inertia::render('Errors/Error', [
                'status' => ResponseAlias::HTTP_NOT_FOUND,
                'message' => $e->getMessage(),
            ]);
        } catch (Throwable $e) {
            return Inertia::render('Errors/Error', [
                'status' => ResponseAlias::HTTP_INTERNAL_SERVER_ERROR,
                'message' => $e->getMessage(),
            ]);
        }
    }
}
