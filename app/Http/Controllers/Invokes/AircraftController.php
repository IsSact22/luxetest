<?php

namespace App\Http\Controllers\Invokes;

use App\Http\Controllers\Controller;
use App\Models\Aircraft;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AircraftController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request): JsonResponse
    {
        $aircraft = Aircraft::query()
            ->whereDoesntHave('aircraftOwner')
            ->orderBy('register', 'asc')
            ->get();

        return response()->json($aircraft, 200);
    }
}
