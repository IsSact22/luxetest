<?php

namespace App\Http\Controllers\Invokes;

use App\Http\Controllers\Controller;
use App\Models\AdminRate;
use App\Models\EngineType;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminRateController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request): JsonResponse
    {
        $adminRates = AdminRate::all()->map(function ($item) {
            return [
                'label' => $item->name,
                'rateable_type' => AdminRate::class,
                'rateable_id' => $item->id,
            ];
        });

        $engineRates = EngineType::all()->map(function ($item) {
            return [
                'label' => $item->name,
                'rateable_type' => EngineType::class,
                'rateable_id' => $item->id,
            ];
        });

        // Usar el método merge para combinar ambas colecciones
        $combinedRates = $adminRates->merge($engineRates);

        return response()->json($combinedRates, Response::HTTP_OK);
    }
}
