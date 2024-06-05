<?php

namespace App\Http\Controllers\Invokes;

use App\Http\Controllers\Controller;
use App\Models\EngineType;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class EngineTypeController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request): JsonResponse
    {
        $engineTypes = EngineType::query()
            ->select('id as value', 'name as label')
            ->orderBy('name', 'asc')
            ->get();

        return response()->json($engineTypes, 200);
    }
}
