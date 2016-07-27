<?php

namespace App\Exceptions;

use ErrorException;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Session\TokenMismatchException;
use Symfony\Component\HttpKernel\Exception\HttpException;


use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Access\AuthorizationException;


class Handler extends ExceptionHandler
{

    /**
     * A list of the exception types that should not be reported.
     */
    protected $dontReport = [
        AuthorizationException::class,
        HttpException::class,
        ModelNotFoundException::class,
        ValidationException::class,
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception $e
     * @return void
     */
    public function report(Exception $e)
    {
        if (config('app.debug') == true) {
            return parent::report($e);
        }
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Exception $e
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $e)
    {

        // reb si es debug, imprimí el por que me voy al 404 o 500, suele ser un ModelNotFoundException de un FirstOrFail

        if (config('app.debug') == true) {

            if ($request->ajax()) {
                return response()->json(['error' => 'Exceptions/Handler.php', 'message' => $e->getMessage(), 'model' => $e->getModel()]);
            }
            else{
                var_dump($e->getMessage());
                var_dump($e->getModel());
                var_dump(get_class($e));
            }

            // var_dump(get_class_methods($e));
        }
        else{

            if ($e instanceof ModelNotFoundException) {

                return response()->view('errors.404', [], 404);
            }
            if ($e instanceof TokenMismatchException) {
                return response()->view('errors.token', [], 500);
            }
            if ($e instanceof ErrorException) {
                return response()->view('errors.404', [], 404);
            }
            if ($e instanceof ClientException) {
                return response()->view('errors.400', [], 400);
            }

        }

        return parent::render($request, $e);
    }
}
