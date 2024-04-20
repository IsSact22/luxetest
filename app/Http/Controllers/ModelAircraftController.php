<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreModelAircraftRequest;
use App\Http\Requests\UpdateModelAircraftRequest;
use App\Models\ModelAircraft;

class ModelAircraftController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): void
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): void
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreModelAircraftRequest $request): void
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(ModelAircraft $modelAircraft): void
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ModelAircraft $modelAircraft): void
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateModelAircraftRequest $request, ModelAircraft $modelAircraft): void
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ModelAircraft $modelAircraft): void
    {
        //
    }
}
