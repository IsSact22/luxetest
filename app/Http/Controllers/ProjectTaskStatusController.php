<?php

namespace App\Http\Controllers;

use App\Models\ProjectTask;
use Illuminate\Http\JsonResponse;

class ProjectTaskStatusController extends Controller
{
    public function change(int $id, string $status, $position): JsonResponse
    {
        try {
            ProjectTask::find($id)->update(['status' => $status]);

            return response()->json([
                'status' => 'success',
            ], 200);
        } catch (\Throwable $e) {
            return response()->json([
                'status' => 'error',
            ], 500);
        }
    }
}
