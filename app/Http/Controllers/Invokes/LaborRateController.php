<?php

namespace App\Http\Controllers\Invokes;

use App\Http\Controllers\Controller;
use App\Models\AdminRate;
use App\Models\EngineType;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LaborRateController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request): JsonResponse
    {
        $adminRates = AdminRate::all()->map(function ($rate) {
            return [
                'id' => $rate->id,
                'label' => $rate->name,
                'type' => AdminRate::class,
            ];
        });

        $engineTypes = EngineType::all()->map(function ($type) {
            return [
                'id' => $type->id,
                'label' => $type->name,
                'type' => EngineType::class,
            ];
        });

        return response()->json($adminRates->concat($engineTypes));
    }
}
