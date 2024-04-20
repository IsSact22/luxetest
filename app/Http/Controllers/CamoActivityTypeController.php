<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCamoActivityTypeRequest;
use App\Http\Requests\UpdateCamoActivityTypeRequest;
use App\Models\CamoActivityType;

class CamoActivityTypeController extends Controller
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
    public function store(StoreCamoActivityTypeRequest $request): void
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(CamoActivityType $camoActivityType): void
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(CamoActivityType $camoActivityType): void
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCamoActivityTypeRequest $request, CamoActivityType $camoActivityType): void
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CamoActivityType $camoActivityType): void
    {
        //
    }
}
