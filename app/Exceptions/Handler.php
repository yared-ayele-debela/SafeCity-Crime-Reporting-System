<?php

namespace App\Exceptions;


use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Session\TokenMismatchException;
use Throwable;
use Illuminate\Http\Response;

class Handler extends ExceptionHandler
{

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
    }

    public function render($request, Throwable $exception)
    {
        // 404 Not Found
        if ($exception instanceof NotFoundHttpException) {
            return response()->view('errors.404', [], 404);
        }

        // 419 Page Expired
        if ($exception instanceof TokenMismatchException) {
            return response()->view('errors.419', [], 419);
        }

        // 500 Internal Server Error
        if ($this->isHttpException($exception)) {
            if ($exception->getStatusCode() === 500) {
                return response()->view('errors.500', [], 500);
            }
        } elseif (app()->environment('production')) {
            return response()->view('errors.500', [], 500);
        }

        return parent::render($request, $exception);
    }

}