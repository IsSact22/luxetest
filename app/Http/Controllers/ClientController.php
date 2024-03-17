<?php

namespace App\Http\Controllers;

use App\Contracts\ClientRepositoryInterface;
use App\Helpers\InertiaResponse;
use App\Http\Requests\StoreClientRequest;
use App\Http\Requests\UpdateClientRequest;
use App\Http\Resources\ClientResource;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class ClientController extends Controller
{
    public function __construct(protected ClientRepositoryInterface $client)
    {
        parent::__construct();
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): \Inertia\Response
    {
        $clients = $this->client->getAll($request);
        $resource = ClientResource::collection($clients);

        return InertiaResponse::content('Clients/Index', ['resource' => $resource]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): \Inertia\Response
    {
        return InertiaResponse::content('Clients/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreClientRequest $request): RedirectResponse
    {
        $this->client->save($request->all());

        return to_route('clients.index')->with('success', 'Client created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): Response
    {
        try {
            $client = $this->client->getById($id);
            $resource = new ClientResource($client);

            return InertiaResponse::content('Clients/Show', ['resource' => $resource]);
        } catch (ModelNotFoundException $e) {
            return Inertia::render('Errors/Error', ['status' => ResponseAlias::HTTP_NOT_FOUND]);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id): Response
    {
        try {
            $client = $this->client->getById($id);
            $resource = new ClientResource($client);

            return InertiaResponse::content('Client/Edit', ['resource' => $resource]);
        } catch (ModelNotFoundException $e) {
            return Inertia::render('Errors/Error', ['status' => ResponseAlias::HTTP_NOT_FOUND]);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateClientRequest $request, string $id): RedirectResponse
    {
        $this->client->update($request->all(), $id);

        return to_route('clients.index')->with('success', 'Client updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): Response|RedirectResponse
    {
        try {
            $this->client->delete($id);

            return to_route('clients.index')->with('success', 'Client deleted successfully');
        } catch (ModelNotFoundException) {
            return Inertia::render('Errors/Error', ['status' => ResponseAlias::HTTP_NOT_FOUND]);
        }
    }
}
