<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;
use Throwable;

class RepositoryException extends Exception
{
    protected int $statusCode;

    public function __construct(
        string $message = 'An error occurred in Repository',
        int $statusCode = ResponseAlias::HTTP_INTERNAL_SERVER_ERROR,
        mixed $code = 0,
        ?Throwable $previous = null
    ) {
        $this->statusCode = $statusCode;
        parent::__construct($message, $code, $previous);
    }

    public function render(Request $request): Response
    {
        return Inertia::render('Errors/Error', [
            'status' => $this->statusCode,
            'description' => $this->getMessage(),
        ]);
    }
}
