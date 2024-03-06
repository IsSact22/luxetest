<?php

namespace App\Helpers;

use Exception;
use Illuminate\Support\Facades\Log;

class LogHelper
{
    public static function logError(Exception $e): void
    {
        $message = 'Error occurred: '.$e->getMessage().' | Trace: '.$e->getTraceAsString();
        Log::error($message);
    }
}
