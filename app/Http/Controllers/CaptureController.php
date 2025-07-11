<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CaptureController extends Controller
{
    public function createForPayment(string $paymentId, Request $request): JsonResponse
    {
        $requestId = $request->header('request-id');
        $merchantId = $request->header('merchant-id');
        
        // Mock capture creation response
        return response()->json([
            'transactionId' => 'cap_' . uniqid(),
            'requestId' => $requestId,
            'parentTransactionId' => $paymentId,
            'transactionState' => 'CAPTURED',
            'responseStatus' => 'APPROVED',
            'responseCode' => '000',
            'responseMessage' => 'Capture successful',
            'amount' => $request->input('amount'),
            'currency' => $request->input('currency'),
            'merchantId' => $merchantId,
            'createdAt' => now()->toISOString(),
            'finalCapture' => $request->input('finalCapture', false)
        ], 201);
    }

    public function index(Request $request): JsonResponse
    {
        // Mock captures list
        return response()->json([
            'captures' => [
                [
                    'transactionId' => 'cap_' . uniqid(),
                    'parentTransactionId' => 'txn_'. uniqid(),
                    'amount' => rand(100, 10000),
                    'currency' => 'USD',
                    'transactionState' => 'CAPTURED',
                    'responseStatus' => 'APPROVED',
                    'createdAt' => now()->subHours(1)->toISOString()
                ]
            ],
            'pagination' => [
                'total' => 1,
                'page' => 1,
                'pageSize' => 10
            ]
        ]);
    }

    public function show(string $id): JsonResponse
    {
        // Mock capture details
        return response()->json([
            'transactionId' => $id,
            'parentTransactionId' => 'txn_' . uniqid(),
            'requestId' => (string) Str::uuid(),
            'amount' => rand(100,10000),
            'currency' => 'USD',
            'transactionState' => 'CAPTURED',
            'responseStatus' => 'APPROVED',
            'responseCode' => '000',
            'responseMessage' => 'Capture successful',
            'createdAt' => now()->subHour()->toISOString(),
            'finalCapture' => true
        ]);
    }
}