<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
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
       $this->renderable(function (NotFoundHttpException $e) {
           return response()->view('errors.404', [], 404);
            //
        });

       //QueryException
         $this->renderable(function (\Illuminate\Database\QueryException $e) {
                return response()->view('errors.404', [], 404);
          });

         //TokenMismatchException
            $this->renderable(function (\Illuminate\Session\TokenMismatchException $e) {
                    return response()->view('errors.419', [], 419);

            });
    }
}
