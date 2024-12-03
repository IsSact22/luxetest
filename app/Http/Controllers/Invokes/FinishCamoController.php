<?php

namespace App\Http\Controllers\Invokes;

use App\Http\Controllers\Controller;
use App\Models\Camo;
use Illuminate\Http\JsonResponse;

class FinishCamoController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Camo $camo): JsonResponse
    {
        // Cargar las actividades relacionadas con el camo
        $camo->load('camoActivity');

        // Inicializamos las banderas
        $hasCanceled = false; // Bandera para verificar si hay una actividad 'canceled'
        $allApprovedAndCompleted = true; // Asumimos que todas las actividades están aprobadas y completadas

        foreach ($camo->camoActivity as $activity) {
            // Verificar si la actividad ha sido cancelada
            if ($activity->approval_status === 'canceled') {
                $hasCanceled = true;
            }

            // Si alguna actividad no cumple con las condiciones, ajustamos la bandera
            if ($activity->approval_status !== 'approved' || $activity->status !== 'completed') {
                $allApprovedAndCompleted = false; // Al menos una actividad no cumple las condiciones requeridas
            }
        }

        // Podemos finalizar el camo si:
        // 1. Hay al menos una actividad cancelada o
        // 2. Todas las actividades están aprobadas y completadas
        return response()->json([
            'result' => $hasCanceled || $allApprovedAndCompleted,
        ], 200);
    }
}
