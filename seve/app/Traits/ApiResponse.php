<?php

namespace App\Traits;

trait ApiResponse
{
    protected function successResponse($data = null, $message, $code = 200)
    {
        return response()->json([
            "status" => "success",
            "message" => $message,
            "data" => $data,
        ], $code);
    }

    protected function notFoundResponse($message)
    {
        return response()->json([
            "status" => "fail",
            "message" => $message,
            "data" => null,
        ], 404);
    }

    protected function unauthorizedResponse($message)
    {
        return response()->json([
            "status" => "fail",
            "message" => $message,
            "data" => null,
        ], 401);
    }

    protected function forbiddenResponse($message)
    {
        return response()->json([
            "status" => "fail",
            "message" => $message,
            "data" => null,
        ], 403);
    }

    protected function unprocessableResponse($errors, $message)
    {
        return response()->json([
            "status" => "fail",
            "message" => $message,
            "errors" => $errors,
        ], 422);
    }

    protected function customError($message, $code = 500, $errors = [])
    {
        return response()->json([
            "status" => "error",
            "message" => $message,
            "errors" => $errors,
        ], $code);
    }
}
