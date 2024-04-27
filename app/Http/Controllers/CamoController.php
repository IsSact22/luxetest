<?php

namespace App\Http\Controllers;

use App\Contracts\CamoRepositoryInterface;
use App\Helpers\InertiaResponse;
use App\Http\Requests\StoreCamoRequest;
use App\Http\Requests\UpdateCamoRequest;
use App\Http\Resources\CamoResource;
use App\Models\Camo;
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

class CamoController extends Controller
{
    public function __construct(protected CamoRepositoryInterface $camo)
    {
        parent::__construct();
        $this->middleware(HandlePrecognitiveRequests::class)->only(['store', 'update']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): Response
    {
        $this->authorize('read-camo', Camo::class);
        $camos = $this->camo->getAll($request);
        $resource = CamoResource::collection($camos);

        return InertiaResponse::content('Camos/Index', ['resource' => $resource]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): Response
    {
        try {
            $this->authorize('create-camo', Camo::class);

            return InertiaResponse::content('Camos/Create');
        } catch (AuthorizationException $e) {
            return Inertia::render('Errors/Error', ['status' => ResponseAlias::HTTP_UNAUTHORIZED]);
        } catch (Throwable $e) {
            Log::error($e->getMessage());

            return Inertia::render('Errors/Error', ['status' => ResponseAlias::HTTP_INTERNAL_SERVER_ERROR]);
        }

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCamoRequest $request): RedirectResponse
    {
        $this->authorize('create-camo', Camo::class);
        $this->camo->newCamo($request->all());

        return to_route('camos.index')->with('success', 'CAMO created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id): Response
    {
        try {
            $this->authorize('read-camo', Camo::class);
            $camo = $this->camo->getById($id);
            $resource = new CamoResource($camo);

            return InertiaResponse::content('Camos/Show', ['resource' => $resource]);
        } catch (ModelNotFoundException) {
            return Inertia::render('Errors/Error', ['status' => ResponseAlias::HTTP_NOT_FOUND]);
        } catch (Throwable $e) {
            return Inertia::render('Errors/Error', ['status' => ResponseAlias::HTTP_INTERNAL_SERVER_ERROR]);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id): Response
    {
        try {
            $this->authorize('update-camo', Camo::class);
            $camo = $this->camo->getById($id);
            $resource = new CamoResource($camo);

            return InertiaResponse::content('Camos/Edit', ['resource' => $resource]);
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
    public function update(UpdateCamoRequest $request, string $id): Response|RedirectResponse
    {
        try {
            $this->authorize('update-camo', Camo::class);
            $this->camo->updateCamo($request->all(), $id);

            return to_route('users.index')->with('success', 'CAMO created successfully');
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
    public function destroy(string $id): Response|RedirectResponse
    {
        try {
            $this->authorize('delete-camo', Camo::class);
            $this->camo->deleteCamo($id);

            return to_route('services.index')->with('success', 'CAMO deleted successfully');
        } catch (ModelNotFoundException) {
            return Inertia::render('Errors/Error', ['status' => ResponseAlias::HTTP_NOT_FOUND]);
        } catch (Throwable $e) {
            Log::error($e->getMessage());

            return Inertia::render('Errors/Error', ['status' => ResponseAlias::HTTP_INTERNAL_SERVER_ERROR]);
        }
    }
}
