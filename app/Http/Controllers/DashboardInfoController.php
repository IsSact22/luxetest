<?php

namespace App\Http\Controllers;

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
        $camos = Camo::orderByDesc('id')
            ->when($user && $user->isCam, function ($query) use ($user) {
                $query->where(function ($query) use ($user) {
                    $query->where('cam_id', $user->id);
                });
            })

            ->when($user && ($user->isOwner || $user->isCrew), function ($query) use ($user) {
                $query->where(function ($query) use ($user) {
                    $query->where('owner_id', $user->owner_id)
                        ->orWhereHas('owner', function ($query) use ($user) {
                            $query->where('owner_id', $user->owner_id);
                        });
                });
            })
            ->take(10)
            ->get();

        return CamoResource::collection($camos);
    }
}
