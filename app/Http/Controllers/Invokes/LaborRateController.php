<?php

namespace App\Http\Controllers\Invokes;

use App\Http\Controllers\Controller;
use App\Models\LaborRate;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LaborRateController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request): JsonResponse
    {
        $laborRate = LaborRate::query()
            ->get();

        return response()->json($laborRate, 200);
    }
}
