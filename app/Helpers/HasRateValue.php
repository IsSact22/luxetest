<?php

namespace App\Helpers;

use App\Models\LaborRate;

class HasRateValue
{
    public static function hasRate(int $laborRateId): bool
    {
        $laborRate = LaborRate::findOrFail($laborRateId);

        return is_null($laborRate->amount) || (! $laborRate->amount) > 0;
    }

    public static function getRate(int $laborRateId): string
    {
        return LaborRate::findOrFail($laborRateId)->name;
    }
}
