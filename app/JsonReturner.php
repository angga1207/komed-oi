<?php

namespace App;

trait JsonReturner
{
    public function successResponse($data, $message = null, $code = 200)
    {
        return response()->json([
            'data' => $data,
            'message' => $message,
            'status' => 'success'
        ], $code);
    }

    public function errorResponse($message, $code = 400)
    {
        return response()->json([
            'message' => $message,
            'status' => 'error'
        ], $code);
    }

    public function notFoundResponse($message = 'Not Found', $code = 404)
    {
        return response()->json([
            'message' => $message,
            'status' => 'not_found'
        ], $code);
    }

    public function unauthorizedResponse($message = 'Unauthorized', $code = 401)
    {
        return response()->json([
            'message' => $message,
            'status' => 'unauthorized'
        ], $code);
    }

    public function validationErrorResponse($message, $code = 422)
    {
        return response()->json([
            'message' => $message,
            'status' => 'validation_error'
        ], $code);
    }

    public function exceptionResponse($message, $code = 500)
    {
        return response()->json([
            'message' => $message,
            'status' => 'exception'
        ], $code);
    }
}
