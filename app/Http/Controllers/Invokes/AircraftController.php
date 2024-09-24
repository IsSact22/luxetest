<?php

namespace App\Http\Controllers\Invokes;

use App\Http\Controllers\Controller;
use App\Models\Aircraft;
use Illuminate\Database\Eloquent\Builder;
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
            ->orderBy('register', 'asc')
            ->when($request->get('search'), function (Builder $query, $search) {
                $query->where('register', 'like', "%{$search}%");
            })
            ->get();

        return response()->json($aircraft, 200);
    }
}
