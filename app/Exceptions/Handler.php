<?php

namespace Messtechnik\Exceptions;

use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

class Handler extends ExceptionHandler
{

    protected $dontReport = [
        //
    ];

    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    public function render($request, Exception $exception)
    {
        if ($this->isHttpException($exception)) {
            switch($exception->getStatusCode()) {
                case 404:
                    return response()->view('errors.' . '404', [], 404);
                    break;
                case 500:
                    return response()->view('errors.' . '500', [], 500);
                    break;
                case 403:
                    return response()->view('errors.' . '403', [], 403);
                    break;
            }
        }
        return parent::render($request, $exception);
    }
}
