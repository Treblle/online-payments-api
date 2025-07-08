<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ValidateApiHeaders
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Skip validation for health check endpoints
        if ($request->is('api/v2/healthcheck/*')) {
            return $next($request);
        }

        // Validate required headers for API endpoints
        if ($request->is('api/*')) {
            $requiredHeaders = ['merchant-id', 'request-id'];
            
            foreach ($requiredHeaders as $header) {
                if (!$request->hasHeader($header)) {
                    return response()->json([
                        'responseStatus' => 'ERROR',
                        'responseCode' => '400',
                        'responseMessage' => "Missing required header: {$header}"
                    ], 400);
                }
            }

            // Validate merchant-id format
            $merchantId = $request->header('merchant-id');
            if (strlen($merchantId) < 8 || strlen($merchantId) > 12) {
                return response()->json([
                    'responseStatus' => 'ERROR',
                    'responseCode' => '400',
                    'responseMessage' => 'Invalid merchant-id format. Must be 8-12 characters.'
                ], 400);
            }

            // Validate request-id format
            $requestId = $request->header('request-id');
            if (strlen($requestId) > 40) {
                return response()->json([
                    'responseStatus' => 'ERROR',
                    'responseCode' => '400',
                    'responseMessage' => 'Invalid request-id format. Must not exceed 40 characters.'
                ], 400);
            }
        }

        return $next($request);
    }
}
