<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class RefundController extends Controller
{
    public function create(Request $request): JsonResponse
    {
        $requestId = $request->header('request-id');
        $merchantId = $request->header('merchant-id');
        
        // Mock refund creation response
        return response()->json([
            'transactionId' => 'ref_' . uniqid(),
            'requestId' => $requestId,
            'parentTransactionId' => $request->input('parentTransactionId'),
            'transactionState' => 'REFUNDED',
            'responseStatus' => 'APPROVED',
            'responseCode' => '000',
            'responseMessage' => 'Refund successful',
            'amount' => $request->input('amount'),
            'currency' => $request->input('currency'),
            'merchantId' => $merchantId,
            'createdAt' => now()->toISOString(),
            'refundType' => $request->input('refundType', 'REFERENCED')
        ], 201);
    }

    public function index(Request $request): JsonResponse
    {
        // Mock refunds list
        return response()->json([
            'refunds' => [
                [
                    'transactionId' => 'ref_12345',
                    'parentTransactionId' => 'txn_12345',
                    'amount' => 500,
                    'currency' => 'USD',
                    'transactionState' => 'REFUNDED',
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
        // Mock refund details
        return response()->json([
            'transactionId' => $id,
            'parentTransactionId' => 'txn_12345',
            'requestId' => '10cc0270-7bed-11e9-a188-1763956dd7f6',
            'amount' => 500,
            'currency' => 'USD',
            'transactionState' => 'REFUNDED',
            'responseStatus' => 'APPROVED',
            'responseCode' => '000',
            'responseMessage' => 'Refund successful',
            'createdAt' => now()->subHour()->toISOString(),
            'refundType' => 'REFERENCED',
            'merchant' => [
                'merchantId' => '998482157630'
            ]
        ]);
    }
}