<?php

namespace App\Http\Responses;

use Exception;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class ApiErrorResponse implements Responsable
{
    public function __construct(
        protected ?Exception $exception,
        protected string $message,
        protected int $statusCode = Response::HTTP_BAD_REQUEST,
        protected array $headers = [],
        protected int $options = 0
    ) {
    }

    public function toResponse($request): JsonResponse|Response
    {
        $response = ['message' => $this->message];

        if ($this->exception instanceof Exception && config('app.debug')) {
            $response['debug'] = [
                'message' => $this->exception->getMessage(),
                'file' => $this->exception->getFile(),
                'line' => $this->exception->getLine(),
                'trace' => $this->exception->getTrace(),
            ];
        }

        return response()->json(
            $response,
            $this->statusCode,
            $this->headers,
            $this->options
        );
    }
}
