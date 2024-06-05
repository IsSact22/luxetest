<?php

namespace App\Http\Controllers\Invokes;

use App\Http\Controllers\Controller;
use App\Models\Camo;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CamoController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request): JsonResponse
    {
        $camo = Camo::query()
            ->when($request->get('search'), static function ($query, $search) {
                $query->where('id', $search);
            })
            ->orderBy('id', 'desc')
            ->get();

        return response()->json($camo, 200);
    }
}
