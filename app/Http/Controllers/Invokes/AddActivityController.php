<?php

namespace App\Http\Controllers\Invokes;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCamoActivityRequest;
use App\Repositories\CamoActivityRepository;
use Illuminate\Http\Request;

class AddActivityController extends Controller
{
    public function __construct(protected CamoActivityRepository $activity)
    {
        parent::__construct();
    }

    /**
     * Handle the incoming request.
     */
    public function __invoke(StoreCamoActivityRequest $request): \Illuminate\Http\JsonResponse
    {
        $this->activity->newActivity($request->all());

        return response()->json([
            'message' => 'CAMO Activity created successfully',
        ], 200);
    }
}
