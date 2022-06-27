<?php

namespace App\Exceptions;

use Throwable;
use Illuminate\Support\ViewErrorBag;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    /**
     * Render the given HttpException.
     *
     * @param  \Symfony\Component\HttpKernel\Exception\HttpExceptionInterface  $e
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function renderHttpException(HttpExceptionInterface $e)
    {
        view()->replaceNamespace(
            'errors', 
            [
                resource_path('views/errors'),
                __DIR__ . '/views',
            ]
        );

        if (view()->exists($view = $this->getHttpExceptionView($e))) {
            return response()->view($view, [
                'errors' => new ViewErrorBag,
                'exception' => $e,
            ], $e->getStatusCode(), $e->getHeaders());
        }

        return $this->convertExceptionToResponse($e);
    }
}
