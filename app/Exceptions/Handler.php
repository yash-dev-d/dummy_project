<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class Handler extends ExceptionHandler
{
    // ...

    public function render($request, Throwable $e)
    {
        if ($e instanceof ValidationException) {
            return $this->convertValidationExceptionToResponse($e, $request);
        }

        return $this->prepareJsonResponse($request, $e);
    }

    protected function convertValidationExceptionToResponse(ValidationException $e, $request)
    {
        $errors = $e->validator->errors()->getMessages();

        return response()->json(['errors' => $errors], 422);
    }

    protected function prepareJsonResponse($request, Throwable $e)
    {
        return response()->json([
            'error' => 'Server Error',
            'message' => $e->getMessage()
        ], $this->isHttpException($e) ? $e->getStatusCode() : 500);
    }

    // ...
}
