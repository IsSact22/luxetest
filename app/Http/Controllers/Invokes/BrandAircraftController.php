<?php

namespace App\Http\Controllers\Invokes;

use App\Http\Controllers\Controller;
use App\Models\BrandAircraft;
use Illuminate\Http\Request;

class BrandAircraftController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $brandAircraft = BrandAircraft::query()
            ->select('id as value', 'name as label')
            ->orderBy('name', 'asc')
            ->get();

        return response()->json($brandAircraft, 200);
    }
}
