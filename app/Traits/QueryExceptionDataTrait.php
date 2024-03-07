<?php

namespace App\Traits;

use App\Http\Responses\ApiErrorResponse;
use Illuminate\Database\QueryException;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

trait QueryExceptionDataTrait
{
    protected function getQueryExceptionData(QueryException $e): ApiErrorResponse
    {
        $connectionName = $e->getConnectionName();
        $sql = $e->getSql();
        $bindings = $e->getBindings();
        $previous = $e->getPrevious();

        return new ApiErrorResponse(
            new QueryException($connectionName, $sql, $bindings, $previous),
            $e->getMessage(),
            ResponseAlias::HTTP_CONFLICT
        );
    }
}
