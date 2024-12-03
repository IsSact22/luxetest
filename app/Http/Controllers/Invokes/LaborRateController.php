<?php

namespace App\Http\Controllers\Invokes;

use App\Http\Controllers\Controller;
use App\Models\LaborRate;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class LaborRateController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request): JsonResponse
    {
        $laborRates = LaborRate::all()->map(function ($rate) {
            return [
                'id' => $rate->id,
                'label' => $rate->name,
                'type' => $rate->rateable_type,
                'rateable_id' => $rate->rateable_id,
                'code' => $rate->code,
                'amount' => $rate->amount,
            ];
        });

        return response()->json($laborRates, Response::HTTP_OK);
    }
}
