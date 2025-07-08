<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

trait ApiResponse
{
    protected function successResponse(array $data, int $statusCode = Response::HTTP_OK): JsonResponse
    {
        return response()->json($data, $statusCode);
    }

    protected function errorResponse(string $message, int $statusCode = Response::HTTP_BAD_REQUEST, array $errors = []): JsonResponse
    {
        $response = [
            'responseStatus' => 'ERROR',
            'responseCode' => (string) $statusCode,
            'responseMessage' => $message,
        ];

        if (!empty($errors)) {
            $response['errors'] = $errors;
        }

        return response()->json($response, $statusCode);
    }

    protected function validationErrorResponse(array $errors): JsonResponse
    {
        return response()->json([
            'responseStatus' => 'ERROR',
            'responseCode' => '400',
            'responseMessage' => 'Validation failed',
            'errors' => $errors
        ], Response::HTTP_BAD_REQUEST);
    }

    protected function notFoundResponse(string $message = 'Resource not found'): JsonResponse
    {
        return response()->json([
            'responseStatus' => 'ERROR',
            'responseCode' => '404',
            'responseMessage' => $message
        ], Response::HTTP_NOT_FOUND);
    }

    protected function unauthorizedResponse(string $message = 'Unauthorized'): JsonResponse
    {
        return response()->json([
            'responseStatus' => 'ERROR',
            'responseCode' => '401',
            'responseMessage' => $message
        ], Response::HTTP_UNAUTHORIZED);
    }

    protected function tooManyRequestsResponse(string $message = 'Too many requests'): JsonResponse
    {
        return response()->json([
            'responseStatus' => 'ERROR',
            'responseCode' => '429',
            'responseMessage' => $message
        ], Response::HTTP_TOO_MANY_REQUESTS);
    }

    protected function serviceUnavailableResponse(string $message = 'Service temporarily unavailable'): JsonResponse
    {
        return response()->json([
            'responseStatus' => 'ERROR',
            'responseCode' => '503',
            'responseMessage' => $message
        ], Response::HTTP_SERVICE_UNAVAILABLE);
    }
}