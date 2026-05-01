<?php

use App\Exceptions\APIExceptionHandler;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: [
            __DIR__.'/../routes/api.php',
        ],
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
        then: function () {
            Route::middleware('api')->name('v1.')->prefix('api/v1')
                ->group(base_path('routes/api_v1.php'));
        },
    )
    ->withMiddleware(function (Middleware $middleware): void {
        //
    })
    ->withExceptions(function (Exceptions $exceptions) {
        // This targets all exceptions thrown when the URL starts with api/
        $exceptions->render(function (Throwable $e, Request $request) {
            if ($request->is('api/*')) {
                return (new APIExceptionHandler())->render($request, $e);
            }

            // Return null to allow default Laravel web handling for non-api routes
            return null;
        });
    })->create();
