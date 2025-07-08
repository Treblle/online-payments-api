<?php

namespace App\Http\Controllers;

use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;

class HomeController extends Controller
{
    use ApiResponse;

    public function index(): JsonResponse
    {
        return $this->successResponse([
            'message' => 'Welcome to the JPM Online Payments API',
            'status' => 'active',
            'version' => '2.7.0'
        ]);
    }
}