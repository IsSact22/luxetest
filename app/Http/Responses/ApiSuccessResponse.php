<?php

namespace App\Http\Responses;

use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class ApiSuccessResponse implements Responsable
{
    public function __construct(
        protected mixed $data,
        protected array $metaData,
        protected int $statusCode = ResponseAlias::HTTP_CREATED,
        protected array $headers = [],
        protected int $options = 0
    ) {
    }

    public function toResponse($request): JsonResponse|ResponseAlias
    {
        return response()->json(
            [
                'data' => $this->data,
                'metaData' => $this->metaData,
            ],
            $this->statusCode,
            $this->headers,
            $this->options
        );
    }
}
