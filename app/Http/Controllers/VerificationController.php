<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class VerificationController extends Controller
{
    public function create(Request $request): JsonResponse
    {
        $requestId = $request->header('request-id');
        $merchantId = $request->header('merchant-id');
        
        // Mock verification creation response
        return response()->json([
            'transactionId' => 'ver_' . uniqid(),
            'requestId' => $requestId,
            'transactionState' => 'VERIFIED',
            'responseStatus' => 'APPROVED',
            'responseCode' => '000',
            'responseMessage' => 'Verification successful',
            'currency' => $request->input('currency'),
            'merchantId' => $merchantId,
            'createdAt' => now()->toISOString(),
            'paymentMethodType' => $request->input('paymentMethodType'),
            'verificationMethod' => 'CARD_VERIFICATION'
        ], 201);
    }

    public function index(Request $request): JsonResponse
    {
        // Mock verifications list
        return response()->json([
            'verifications' => [
                [
                    'transactionId' => 'ver_12345',
                    'currency' => 'USD',
                    'transactionState' => 'VERIFIED',
                    'responseStatus' => 'APPROVED',
                    'createdAt' => now()->subHours(1)->toISOString(),
                    'verificationMethod' => 'CARD_VERIFICATION'
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
        // Mock verification details
        return response()->json([
            'transactionId' => $id,
            'requestId' => '10cc0270-7bed-11e9-a188-1763956dd7f6',
            'currency' => 'USD',
            'transactionState' => 'VERIFIED',
            'responseStatus' => 'APPROVED',
            'responseCode' => '000',
            'responseMessage' => 'Verification successful',
            'createdAt' => now()->subHour()->toISOString(),
            'verificationMethod' => 'CARD_VERIFICATION',
            'merchant' => [
                'merchantId' => '998482157630'
            ],
            'paymentMethodType' => [
                'card' => [
                    'accountNumber' => '****0026',
                    'expiry' => [
                        'month' => '12',
                        'year' => '2025'
                    ],
                    'brand' => 'VISA'
                ]
            ]
        ]);
    }
}