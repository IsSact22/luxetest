<?php

namespace App\Exceptions;

use App\Http\Responses\ApiErrorResponse;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Nette\Schema\ValidationException;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });

        $this->renderable(function (ValidationException $e, $request) {
            $message = $e->getMessage();
            $statusCode = $e->status ?: ResponseAlias::HTTP_UNPROCESSABLE_ENTITY;

            return new ApiErrorResponse($e, $message, $statusCode);
        });
    }
}
