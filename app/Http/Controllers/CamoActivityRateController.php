<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCamoActivityRateRequest;
use App\Http\Requests\UpdateCamoActivityRateRequest;
use App\Models\CamoActivityRate;

class CamoActivityRateController extends Controller
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
    public function store(StoreCamoActivityRateRequest $request): void
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(CamoActivityRate $camoActivityRate): void
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(CamoActivityRate $camoActivityRate): void
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCamoActivityRateRequest $request, CamoActivityRate $camoActivityRate): void
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CamoActivityRate $camoActivityRate): void
    {
        //
    }
}
