<?php

namespace App\Http\Controllers\Invokes;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateCamoActivityRequest;
use Illuminate\Http\Request;

class HandleActivityController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(UpdateCamoActivityRequest $request, string $id): \Illuminate\Http\JsonResponse
    {
        $activity = \App\Models\CamoActivity::query()->findOrFail($id);
        $activity->update($request->all());
        if ($request->get('status') === 'in_progress' && ! $activity->date) {
            $activity->date = now();
            $activity->save();
        }

        return response()->json([
            'message' => 'Activity update successfully',
        ], 200);
    }
}
