<?php

namespace App\Helpers;

use Illuminate\Support\Carbon;

if (! function_exists('CarbonParse')) {
    /**
     * Parse the given string date using Carbon and format it as 'Y-m-d H:i'.
     */
    function CarbonParse(?string $stringDate): bool|string
    {
        if ($stringDate !== null && $stringDate !== '') {
            return Carbon::parse($stringDate)->format('Y-m-d H:i');
        } elseif ($stringDate === null) {
            return false;
        } else {
            return false;
        }
    }
}
