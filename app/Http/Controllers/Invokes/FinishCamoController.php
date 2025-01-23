<?php

namespace App\Http\Controllers\Invokes;

use App\ActivityStatus;
use App\ApprovalStatus;
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
        $hasCanceled = false; // Bandera para actividades 'canceled'
        $allApprovedCompleted = true; // Bandera para verificar si todas las actividades aprobadas están completadas
        $onlyCanceled = true; // Bandera para verificar si todas las actividades son 'canceled'

        foreach ($camo->camoActivity as $activity) {
            // Verificar si la actividad ha sido cancelada
            if ($activity->approval_status === ApprovalStatus::canceled) {
                $hasCanceled = true; // Hay al menos una actividad cancelada
            } else {
                $onlyCanceled = false; // Si encontramos una actividad que no está cancelada, esta bandera se pone en false

                // Si la actividad está aprobada, verificar si está completada
                if ($activity->approval_status === ApprovalStatus::approved &&
                    $activity->status !== ActivityStatus::completed) {
                    $allApprovedCompleted = false; // Al menos una actividad aprobada no está completada
                }
            }
        }

        // Verificación de las condiciones
        $canFinishCamo = false;

        // 1. Si todas las actividades están con approval_status 'canceled'
        if ($onlyCanceled) {
            $canFinishCamo = true;
        }

        // 2. Si hay actividades 'canceled' y hay actividades aprobadas y completadas
        if ($hasCanceled && $allApprovedCompleted) {
            $canFinishCamo = true;
        }

        // 3. Si hay actividades 'canceled' y también hay actividades aprobadas y no todas completadas, no se puede finalizar
        // Esto ya se maneja implícitamente.

        // 4. Todas las actividades aprobadas deben estar completadas para poder finalizar el Camo
        // Y el camo no tiene un finish_date
        if ($allApprovedCompleted && !$camo->finish_date) {
            $canFinishCamo = true; // Se puede finalizar
        }

        return response()->json([
            'result' => $canFinishCamo,
        ], 200);
    }
}
