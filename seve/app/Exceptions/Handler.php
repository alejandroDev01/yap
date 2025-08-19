<?php

namespace App\Exceptions;

use App\Traits\ApiResponse;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Throwable;

class Handler extends ExceptionHandler
{
    use ApiResponse;

    public function register(): void {}

    public function render($request, Throwable $exception)
    {
        if ($exception instanceof ValidationException) {
            return $this->unprocessableResponse(
                $exception->errors(),
                "Error de validación"
            );
        }

        if ($exception instanceof AuthenticationException) {
            return $this->unauthorizedResponse("Usted no esta autenticado");
        }

        if ($exception instanceof AuthorizationException) {
            return $this->forbiddenResponse("Acceso denegado");
        }

        if ($exception instanceof ModelNotFoundException) {
            $model = class_basename($exception->getModel());
            return $this->notFoundResponse("$model no encontrado");
        }

        return $this->customError(
            $exception->getMessage() ?: "Error inesperado",
            500
        );
    }
}
