<?php

namespace App\Traits;

use App\Http\Responses\ApiErrorResponse;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

trait ModelNotFoundExceptionData
{
    protected function getModelNotFoundExceptionData(ModelNotFoundException $e): ApiErrorResponse
    {
        return new ApiErrorResponse(
            new ModelNotFoundException,
            $e->getMessage(),
            ResponseAlias::HTTP_NOT_FOUND
        );
    }
}
