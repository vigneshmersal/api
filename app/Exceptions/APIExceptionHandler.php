<?php

namespace App\Exceptions;

use App\Traits\ApiResponses;
use Exception;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;
use Throwable;

class APIExceptionHandler extends Exception
{
    use ApiResponses;

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

    protected $handlers = [
        ValidationException::class => 'handleValidation',
        ModelNotFoundException::class => 'handleModelNotFound',
        AuthenticationException::class => 'handleAuthentication',
    ];
    // public static array $handlers = [
    //     AuthenticationException::class => 'handleAuthenticationException',
    //     ValidationException::class => 'handleValidationException',
    //     ModelNotFoundException::class => 'handleNotFoundException',
    //     NotFoundHttpException::class => 'handleNotFoundException',
    //     AuthorizationException::class => 'handleAuthorizationException',
    //     MethodNotAllowedHttpException::class => 'handleMethodNotAllowedHttpException',
    //     HttpException::class => 'handleHttpException',
    //     QueryException::class => 'handleQueryException'
    // ];

    private function handleValidation(ValidationException $exception)
    {
        $errors = [];
        foreach ($exception->errors() as $key => $value) {
            foreach ($value as $message) {
                $errors[] = [
                    'status' => 422,
                    'message' => $message,
                    'source' => $key,
                ];
            }
        }

        return $errors;
    }

    private function handleModelNotFound(ModelNotFoundException $exception)
    {
        // $className = basename(\App\Models\User::class);
        return [
            [
                'status' => 404,
                'message' => 'The resource cannot be found.',
                'source' => $exception->getModel(),
            ],
        ];
    }

    private function handleAuthentication(AuthenticationException $exception)
    {
        return [
            [
                'status' => 401,
                'message' => 'Unauthenticated',
                'source' => '',
            ],
        ];
    }

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        // $this->reportable(function (Throwable $e) {
        //     //
        // });
    }

    public function render($request, Throwable $exception)
    {
        $className = get_class($exception);

        if (array_key_exists($className, $this->handlers)) {
            $method = $this->handlers[$className];

            return $this->error($this->$method($exception));
        }

        $index = strrpos($className, '\\');

        return $this->error([
            [
                // 'type' => basename(get_class($e)),
                // 'status' => intval($e->getCode()), // returns 0 if no code
                'type' => substr($className, $index + 1),
                'status' => 0,
                'message' => $exception->getMessage(),
                'source' => 'Line: '.$exception->getLine().': '.$exception->getFile(),
            ],
        ]);
    }
}
