<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAircraftRequest;
use App\Http\Requests\UpdateAircraftRequest;
use App\Models\Aircraft;

class AircraftController extends Controller
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
    public function store(StoreAircraftRequest $request): void
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Aircraft $aircraft): void
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Aircraft $aircraft): void
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAircraftRequest $request, Aircraft $aircraft): void
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Aircraft $aircraft): void
    {
        //
    }
}
