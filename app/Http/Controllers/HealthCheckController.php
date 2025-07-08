<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;

class HealthCheckController extends Controller
{
    public function payments(): JsonResponse
    {
        return response()->json([
            'status' => 'PASS'
        ]);
    }

    public function refunds(): JsonResponse
    {
        return response()->json([
            'status' => 'PASS'
        ]);
    }

    public function verifications(): JsonResponse
    {
        return response()->json([
            'status' => 'PASS'
        ]);
    }
}