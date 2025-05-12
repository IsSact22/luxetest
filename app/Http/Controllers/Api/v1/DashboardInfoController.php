<?php

namespace App\Http\Controllers\Api\v1;
use App\Http\Controllers\Controller;
use App\Http\Resources\CamoResource;
use App\Models\Camo;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class DashboardInfoController extends Controller
{
    public function __construct(protected Camo $camo)
    {
        parent::__construct();
    }

    public function dashboardCamo(): AnonymousResourceCollection
    {
        $user = auth()->user();

        $camos = \App\Models\Camo::query()->orderByDesc('id')
            ->when($user && $user->isCam, static function ($query) use ($user) {
                $query->where(static function ($query) use ($user) {
                    $query->where('cam_id', $user->id);
                });
            })

            ->when($user && ($user->isOwner || $user->isCrew), static function ($query) use ($user) {
                $query->where(static function ($query) use ($user) {
                    $query->where('owner_id', $user->id)
                        ->orWhereHas('owner', static function ($query) use ($user) {
                            $query->where('owner_id', $user->id);
                        });
                });
            })
            ->take(10)
            ->get();

        return CamoResource::collection($camos);
    }
}
