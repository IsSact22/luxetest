<?php

namespace App\Http\Controllers\Invokes;

use App\Http\Controllers\Controller;
use App\Models\Camo;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Throwable;

class CloseCamoController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request, Camo $camo): JsonResponse
    {
        try {
            $startDate = $camo->start_date;
            $validated = $request->validate([
                'finish_date' => [
                    'required',
                    'date',
                    'after_or_equal:' . $startDate,
                ],
            ]);

            $camo->finish_date = $validated['finish_date'];
            $camo->save();

            return response()->json([
                'success' => true,
                'message' => 'Camo Finalizado con éxito',
            ], 201);

        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Camo no encontrado',
            ], 404);
        } catch (Throwable $e) {
            Log::error('Imposible finalizar el CAMO', ['exception' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
            //throw new RuntimeException('Imposible finalizar el CAMO');
        }
    }
}
