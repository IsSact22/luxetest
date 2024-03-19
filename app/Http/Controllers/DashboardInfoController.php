<?php

namespace App\Http\Controllers;

use App\Http\Resources\CamoResource;
use App\Models\Camo;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class DashboardInfoController extends Controller
{
    public function __construct(protected Camo $camo)
    {
        parent::__construct();
    }

    public function dashboardCamo(): AnonymousResourceCollection
    {
        $camos = Camo::orderByDesc('id')
            ->take(10)
            ->get();

        return CamoResource::collection($camos);
    }
}
