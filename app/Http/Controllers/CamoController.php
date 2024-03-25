<?php

namespace App\Http\Controllers;

use App\Contracts\CamoRepositoryInterface;
use App\Helpers\InertiaResponse;
use App\Http\Requests\StoreCamoRequest;
use App\Http\Requests\UpdateCamoRequest;
use App\Http\Resources\CamoResource;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;
use Throwable;

class CamoController extends Controller
{
    public function __construct(protected CamoRepositoryInterface $camo)
    {
        parent::__construct();
        $this->middleware(['role:super-admin|admin|cam']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): Response
    {
        $camos = $this->camo->getAll($request);
        $resource = CamoResource::collection($camos);

        return InertiaResponse::content('Camos/Index', ['resource' => $resource]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): Response
    {
        return InertiaResponse::content('Camos/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCamoRequest $request): RedirectResponse
    {
        $this->camo->newCamo($request->all());

        return to_route('camos.index')->with('success', 'CAMO created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): Response
    {
        try {
            $camo = $this->camo->getById($id);
            $resource = new CamoResource($camo);

            return InertiaResponse::content('Camos/Show', ['resource' => $resource]);
        } catch (ModelNotFoundException) {
            return Inertia::render('Errors/Error', ['status' => ResponseAlias::HTTP_NOT_FOUND]);
        } catch (Throwable) {
            return Inertia::render('Errors/Error', ['status' => ResponseAlias::HTTP_INTERNAL_SERVER_ERROR]);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id): Response
    {
        try {
            $camo = $this->camo->getById($id);
            $resource = new CamoResource($camo);

            return InertiaResponse::content('Camos/Edit', ['resource' => $resource]);
        } catch (ModelNotFoundException) {
            return Inertia::render('Errors/Error', ['status' => ResponseAlias::HTTP_NOT_FOUND]);
        } catch (Throwable) {
            return Inertia::render('Errors/Error', ['status' => ResponseAlias::HTTP_INTERNAL_SERVER_ERROR]);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCamoRequest $request, string $id): Response|RedirectResponse
    {
        try {
            $camo = $this->camo->updateCamo($request->all(), $id);

            return to_route('users.index')->with('success', 'CAMO created successfully');
        } catch (ModelNotFoundException) {
            return Inertia::render('Errors/Error', ['status' => ResponseAlias::HTTP_NOT_FOUND]);
        } catch (Throwable) {
            return Inertia::render('Errors/Error', ['status' => ResponseAlias::HTTP_INTERNAL_SERVER_ERROR]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): Response|RedirectResponse
    {
        try {
            $this->camo->deleteCamo($id);

            return to_route('services.index')->with('success', 'CAMO deleted successfully');
        } catch (ModelNotFoundException) {
            return Inertia::render('Errors/Error', ['status' => ResponseAlias::HTTP_NOT_FOUND]);
        } catch (Throwable) {
            return Inertia::render('Errors/Error', ['status' => ResponseAlias::HTTP_INTERNAL_SERVER_ERROR]);
        }
    }
}
