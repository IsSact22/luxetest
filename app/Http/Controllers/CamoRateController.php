<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCamoRateRequest;
use App\Http\Requests\UpdateCamoRateRequest;
use App\Models\CamoRate;

class CamoRateController extends Controller
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
    public function store(StoreCamoRateRequest $request): void
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(CamoRate $camoRate): void
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(CamoRate $camoRate): void
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCamoRateRequest $request, CamoRate $camoRate): void
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CamoRate $camoRate): void
    {
        //
    }
}
