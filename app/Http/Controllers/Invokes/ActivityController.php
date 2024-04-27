<?php

namespace App\Http\Controllers\Invokes;

use App\Http\Controllers\Controller;
use App\Http\Resources\CamoActivityResource;
use App\Models\CamoActivity;
use Illuminate\Http\Request;

class ActivityController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request): \Illuminate\Http\JsonResponse
    {
        $perPage = $request->has('per_page') ? $request->get('per_page') : 10;
        $activities = CamoActivity::query()
            ->when($request->get('camo_id'), static function ($query, int $camoId) {
                $query->where('camo_id', $camoId);
            })
            ->when($request->get('filter'), static function ($query, string $filter) {
                $parts = explode('.', $filter);
                $column = $parts[0];
                $needle = $parts[1];
                $query->where($column, $needle);
            })
            ->when($request->get('search'), static function ($query, string $search) {
                $query->where('name', 'like', $search.'%')
                    ->orWhere('description', 'like', $search.'%')
                    ->orWhere('status', 'like', $search.'%')
                    ->orWhere('comments', 'like', $search.'%')
                    ->orWhere('awr', 'like', $search.'%')
                    ->orWhere('approval_status', 'like', $search.'%');
            })
            ->paginate($perPage)
            ->withQueryString();

        return response()->json([
            'resource' => CamoActivityResource::collection($activities->items()),
            'current_page' => $activities->currentPage(),
            'per_page' => $activities->perPage(),
            'total' => $activities->total(),
            'last_page' => $activities->lastPage(),
            'first_page_url' => $activities->url(1),
            'last_page_url' => $activities->url($activities->lastPage()),
            'next_page_url' => $activities->nextPageUrl(),
            'prev_page_url' => $activities->previousPageUrl(),
        ], 200);
    }
}
