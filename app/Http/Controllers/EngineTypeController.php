<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreEngineTypeRequest;
use App\Http\Requests\UpdateEngineTypeRequest;
use App\Models\EngineType;

class EngineTypeController extends Controller
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
    public function store(StoreEngineTypeRequest $request): void
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(EngineType $engineType): void
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(EngineType $engineType): void
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateEngineTypeRequest $request, EngineType $engineType): void
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(EngineType $engineType): void
    {
        //
    }
}
