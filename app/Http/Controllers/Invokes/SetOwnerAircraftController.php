<?php

namespace App\Http\Controllers\Invokes;

use App\Http\Controllers\Controller;
use App\Models\Aircraft;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Throwable;

class SetOwnerAircraftController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request): JsonResponse
    {
        try {
            $aircraft = Aircraft::find($request->get('aircraft_id'));
            $owner = $request->get('owner_id');
            $aircraft->aircraftOwner()->attach($owner);

            return response()->json([
                'message' => 'Owner assigned correctly',
                'aircraft' => $aircraft,
            ], 201);
        } catch (Throwable $exception) {
            Log::error($exception->getMessage());

            return response()->json([
                'message' => 'Impossible to assign owner',
                'aircraft' => false,
            ], 500);
        }

    }
}
