<?php

namespace App\Http\Controllers\Invokes;

use App\Http\Controllers\Controller;
use App\Models\EngineType;
use Illuminate\Http\Request;

class EngineTypeController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request): \Illuminate\Http\JsonResponse
    {
        $engineTypes = EngineType::query()->select('name as label', 'id as value')->get();

        return response()->json($engineTypes, 200);
    }
}
