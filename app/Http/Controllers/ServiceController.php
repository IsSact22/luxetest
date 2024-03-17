<?php

namespace App\Http\Controllers;

use App\Contracts\ServiceRepositoryInterface;
use App\Helpers\InertiaResponse;
use App\Http\Requests\StoreServiceRequest;
use App\Http\Requests\UpdateServiceRequest;
use App\Http\Resources\ServiceResource;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class ServiceController extends Controller
{
    public function __construct(protected ServiceRepositoryInterface $service)
    {
        parent::__construct();
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): Response
    {
        $services = $this->service->getAll($request);
        $resource = ServiceResource::collection($services);

        return InertiaResponse::content('Services/Index', ['resource' => $resource]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): Response
    {
        return InertiaResponse::content('Services/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreServiceRequest $request): RedirectResponse
    {
        $this->service->save($request->all());

        return to_route('services.index')->with('success', 'Service created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id): Response
    {
        $service = $this->service->getById($id);
        $resource = new ServiceResource($service);

        return InertiaResponse::content('Services/Show', ['resource' => $resource]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(int $id): Response
    {
        try {
            $service = $this->service->getById($id);
            $resource = new ServiceResource($service);

            return InertiaResponse::content('Services/Show', ['resource' => $resource]);
        } catch (ModelNotFoundException) {
            return Inertia::render('Errors/Error', ['status' => ResponseAlias::HTTP_NOT_FOUND]);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateServiceRequest $request, int $id): RedirectResponse
    {
        $data = $request->all();
        $this->service->update($data, $id);

        return to_route('services.index')->with('success', 'Service updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id): Response|RedirectResponse
    {
        try {
            $this->service->delete($id);

            return to_route('services.index')->with('success', 'Service deleted successfully');
        } catch (ModelNotFoundException) {
            return Inertia::render('Errors/Error', ['status' => ResponseAlias::HTTP_NOT_FOUND]);
        }
    }
}
