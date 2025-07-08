<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreatePaymentRequest;
use App\Services\MockDataService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function create(CreatePaymentRequest $request): JsonResponse
    {
        $requestId = $request->header('request-id');
        $merchantId = $request->header('merchant-id');
        
        $response = MockDataService::generatePaymentResponse(
            $request->all(),
            $requestId,
            $merchantId
        );
        
        return response()->json($response, 201);
    }

    public function index(Request $request): JsonResponse
    {
        // Mock payments list
        return response()->json([
            'payments' => [
                [
                    'transactionId' => 'txn_12345',
                    'amount' => 1000,
                    'currency' => 'USD',
                    'transactionState' => 'AUTHORIZED',
                    'responseStatus' => 'APPROVED',
                    'createdAt' => now()->subHours(2)->toISOString()
                ],
                [
                    'transactionId' => 'txn_12346',
                    'amount' => 2500,
                    'currency' => 'USD',
                    'transactionState' => 'CAPTURED',
                    'responseStatus' => 'APPROVED',
                    'createdAt' => now()->subHours(1)->toISOString()
                ]
            ],
            'pagination' => [
                'total' => 2,
                'page' => 1,
                'pageSize' => 10
            ]
        ]);
    }

    public function show(string $id): JsonResponse
    {
        // Mock payment details
        return response()->json([
            'transactionId' => $id,
            'requestId' => '10cc0270-7bed-11e9-a188-1763956dd7f6',
            'amount' => 1234,
            'currency' => 'USD',
            'transactionState' => 'AUTHORIZED',
            'responseStatus' => 'APPROVED',
            'responseCode' => '000',
            'responseMessage' => 'Approved',
            'createdAt' => now()->subHour()->toISOString(),
            'merchant' => [
                'merchantId' => '998482157630',
                'merchantCategoryCode' => '4899'
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

    public function update(string $id, Request $request): JsonResponse
    {
        // Mock payment update
        return response()->json([
            'transactionId' => $id,
            'requestId' => $request->header('request-id'),
            'transactionState' => 'UPDATED',
            'responseStatus' => 'APPROVED',
            'responseCode' => '000',
            'responseMessage' => 'Payment updated successfully',
            'updatedAt' => now()->toISOString()
        ]);
    }
}