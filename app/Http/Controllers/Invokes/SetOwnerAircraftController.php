<?php

namespace App\Http\Controllers\Invokes;

use App\Http\Controllers\Controller;
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
            $aircraft = \App\Models\Aircraft::query()->find($request->get('aircraft_id'));
            $owner = $request->get('owner_id');
            $aircraft->aircraftOwner()->attach($owner);

            return response()->json([
                'message' => 'Owner assigned correctly',
                'aircraft' => $aircraft,
            ], 201);
        } catch (Throwable $throwable) {
            Log::error($throwable->getMessage());

            return response()->json([
                'message' => 'Impossible to assign owner',
                'aircraft' => false,
            ], 500);
        }

    }
}
