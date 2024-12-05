<?php

namespace App\Helpers;

use App\Models\CamoActivity;
use App\Models\LaborRate;
use Illuminate\Support\Facades\DB;
use RuntimeException;
use Throwable;

class RecalculateRateActivity
{
    public static function calculate($camoActivityId, LaborRate $laborRate)
    {
        try {
            return DB::transaction(static function () use ($camoActivityId, $laborRate) {
                $value = $laborRate->values()->latest()->first();
                $camoActivity = CamoActivity::findOrFail($camoActivityId);
                $camoActivity->labor_rate_value_id = $value->id;
                $estimateTime = $camoActivity->estimate_time;
                $calculatedRate = $value->amount * $estimateTime;
                $camoActivity->labor_mount = $calculatedRate;
                $camoActivity->save();

                return true;
            });
        } catch (Throwable $e) {
            throw new RuntimeException('Imposible recalcular la tarifa de la Actividad');
        }
    }
}
