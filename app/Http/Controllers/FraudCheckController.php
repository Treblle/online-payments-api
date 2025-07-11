<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class FraudCheckController extends Controller
{
    public function create(Request $request): JsonResponse
    {
        $requestId = $request->header('request-id');
        $merchantId = $request->header('merchant-id');
        
        // Mock fraud check creation response
        return response()->json([
            'transactionId' => 'fraud_' . uniqid(),
            'requestId' => $requestId,
            'transactionState' => 'CHECKED',
            'responseStatus' => 'APPROVED',
            'responseCode' => '000',
            'responseMessage' => 'Fraud check completed',
            'merchantId' => $merchantId,
            'createdAt' => now()->toISOString(),
            'fraudScore' => rand(1, 100),
            'riskLevel' => 'LOW',
            'fraudRules' => [
                [
                    'ruleName' => 'VELOCITY_CHECK',
                    'ruleResult' => 'PASS'
                ],
                [
                    'ruleName' => 'GEOLOCATION_CHECK',
                    'ruleResult' => 'PASS'
                ]
            ]
        ], 201);
    }

    public function index(Request $request): JsonResponse
    {
        // Mock fraud checks list
        return response()->json([
            'fraudChecks' => [
                [
                    'transactionId' => 'fraud_' . uniqid(),
                    'transactionState' => 'CHECKED',
                    'responseStatus' => 'APPROVED',
                    'fraudScore' => 25,
                    'riskLevel' => 'LOW',
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
        // Mock fraud check details
        return response()->json([
            'transactionId' => $id,
            'requestId' => (string) Str::uuid(),
            'transactionState' => 'CHECKED',
            'responseStatus' => 'APPROVED',
            'responseCode' => '000',
            'responseMessage' => 'Fraud check completed',
            'createdAt' => now()->subHour()->toISOString(),
            'fraudScore' => 25,
            'riskLevel' => 'LOW',
            'fraudRules' => [
                [
                    'ruleName' => 'VELOCITY_CHECK',
                    'ruleResult' => 'PASS',
                    'description' => 'Transaction velocity within acceptable limits'
                ],
                [
                    'ruleName' => 'GEOLOCATION_CHECK',
                    'ruleResult' => 'PASS',
                    'description' => 'Transaction location matches cardholder profile'
                ],
                [
                    'ruleName' => 'AMOUNT_CHECK',
                    'ruleResult' => 'PASS',
                    'description' => 'Transaction amount within normal range'
                ]
            ],
            'merchant' => [
                'merchantId' => rand(pow(10, 12 - 1), pow(10, 12) - 1)
            ]
        ]);
    }
}