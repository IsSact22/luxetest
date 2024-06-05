<?php

namespace App\Http\Controllers\Invokes;

use App\Http\Controllers\Controller;
use App\Models\AdminRate;
use App\Models\CamoActivity;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PendingRateController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request): JsonResponse
    {
        $camoPendingRateActivities = CamoActivity::query()
            ->orderBy('camo_id', 'desc')
            ->whereHas('laborRate', function (Builder $query) {
                $query->where('rateable_type', AdminRate::class)
                    ->where('mount', 0);
            })
            ->get();

        return response()->json($camoPendingRateActivities, 200);
    }
}
