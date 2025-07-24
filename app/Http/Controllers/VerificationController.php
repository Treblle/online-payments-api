<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class VerificationController extends Controller
{
    public function create(Request $request): JsonResponse
    {
        $requestId = $request->header('request-id');
        $merchantId = $request->header('merchant-id');

        if($request->input('currency') == 'HRK') {
            return response()->json([
                'error' => 'Currency HRK is not supported',
                'code' => 'CURRENCY_NOT_SUPPORTED'
            ], 500);
        }
        
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
                    'transactionId' => 'ver_' . uniqid(),
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
            'requestId' => (string) Str::uuid(),
            'currency' => 'USD',
            'transactionState' => 'VERIFIED',
            'responseStatus' => 'APPROVED',
            'responseCode' => '000',
            'responseMessage' => 'Verification successful',
            'createdAt' => now()->subHour()->toISOString(),
            'verificationMethod' => 'CARD_VERIFICATION',
            'merchant' => [
                'merchantId' => rand(pow(10, 12 - 1), pow(10, 12) - 1)
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