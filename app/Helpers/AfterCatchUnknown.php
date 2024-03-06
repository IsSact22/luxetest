<?php

namespace App\Helpers;

use App\Http\Responses\ApiErrorResponse;
use Exception;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

if (! function_exists('AfterCatchUnknown')) {
    /**
     * Esta función devuelve Error server Default
     */
    function AfterCatchUnknown(): ApiErrorResponse
    {
        return new ApiErrorResponse(
            new Exception('Unknown error occurred.'),
            'Server Error',
            ResponseAlias::HTTP_INTERNAL_SERVER_ERROR
        );
    }
}
