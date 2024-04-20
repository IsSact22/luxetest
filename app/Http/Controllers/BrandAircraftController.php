<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBrandAircraftRequest;
use App\Http\Requests\UpdateBrandAircraftRequest;
use App\Models\BrandAircraft;

class BrandAircraftController extends Controller
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
    public function store(StoreBrandAircraftRequest $request): void
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(BrandAircraft $brandAircraft): void
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(BrandAircraft $brandAircraft): void
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBrandAircraftRequest $request, BrandAircraft $brandAircraft): void
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(BrandAircraft $brandAircraft): void
    {
        //
    }
}
