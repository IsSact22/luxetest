<?php

namespace App\Http\Controllers\Invokes;

use App\Http\Controllers\Controller;
use App\Models\ModelAircraft;
use Illuminate\Http\Request;

class ModelAircraftController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $modelAircraft = ModelAircraft::query()
            ->select('id as value', 'name as label')
            ->orderBy('name', 'asc')
            ->get();

        return response()->json($modelAircraft, 200);
    }
}
