<?php

namespace App\Services;

class MockDataService
{
    public static function generatePaymentResponse(array $requestData, string $requestId, string $merchantId): array
    {
        return [
            'transactionId' => 'txn_' . uniqid(),
            'requestId' => $requestId,
            'transactionState' => $requestData['captureMethod'] === 'NOW' ? 'CAPTURED' : 'AUTHORIZED',
            'responseStatus' => 'APPROVED',
            'responseCode' => '000',
            'responseMessage' => 'Approved',
            'amount' => $requestData['amount'],
            'currency' => $requestData['currency'],
            'merchantId' => $merchantId,
            'createdAt' => now()->toISOString(),
            'paymentMethodType' => self::maskPaymentMethod($requestData['paymentMethodType'] ?? []),
            'captureMethod' => $requestData['captureMethod'] ?? 'LATER',
            'authorizationCode' => 'AUTH' . rand(100000, 999999),
            'processorResponse' => [
                'processorTransactionId' => 'proc_' . uniqid(),
                'processorCode' => '00',
                'processorMessage' => 'Approved'
            ]
        ];
    }

    public static function generateCaptureResponse(array $requestData, string $requestId, string $merchantId, string $paymentId): array
    {
        return [
            'transactionId' => 'cap_' . uniqid(),
            'requestId' => $requestId,
            'parentTransactionId' => $paymentId,
            'transactionState' => 'CAPTURED',
            'responseStatus' => 'APPROVED',
            'responseCode' => '000',
            'responseMessage' => 'Capture successful',
            'amount' => $requestData['amount'],
            'currency' => $requestData['currency'] ?? 'USD',
            'merchantId' => $merchantId,
            'createdAt' => now()->toISOString(),
            'finalCapture' => $requestData['finalCapture'] ?? false,
            'processorResponse' => [
                'processorTransactionId' => 'proc_cap_' . uniqid(),
                'processorCode' => '00',
                'processorMessage' => 'Capture Approved'
            ]
        ];
    }

    public static function generateRefundResponse(array $requestData, string $requestId, string $merchantId): array
    {
        return [
            'transactionId' => 'ref_' . uniqid(),
            'requestId' => $requestId,
            'parentTransactionId' => $requestData['parentTransactionId'] ?? null,
            'transactionState' => 'REFUNDED',
            'responseStatus' => 'APPROVED',
            'responseCode' => '000',
            'responseMessage' => 'Refund successful',
            'amount' => $requestData['amount'] ?? null,
            'currency' => $requestData['currency'] ?? 'USD',
            'merchantId' => $merchantId,
            'createdAt' => now()->toISOString(),
            'refundType' => $requestData['parentTransactionId'] ? 'REFERENCED' : 'STANDALONE',
            'processorResponse' => [
                'processorTransactionId' => 'proc_ref_' . uniqid(),
                'processorCode' => '00',
                'processorMessage' => 'Refund Approved'
            ]
        ];
    }

    public static function generateVerificationResponse(array $requestData, string $requestId, string $merchantId): array
    {
        return [
            'transactionId' => 'ver_' . uniqid(),
            'requestId' => $requestId,
            'transactionState' => 'VERIFIED',
            'responseStatus' => 'APPROVED',
            'responseCode' => '000',
            'responseMessage' => 'Verification successful',
            'currency' => $requestData['currency'],
            'merchantId' => $merchantId,
            'createdAt' => now()->toISOString(),
            'paymentMethodType' => self::maskPaymentMethod($requestData['paymentMethodType'] ?? []),
            'verificationMethod' => 'CARD_VERIFICATION',
            'processorResponse' => [
                'processorTransactionId' => 'proc_ver_' . uniqid(),
                'processorCode' => '00',
                'processorMessage' => 'Verification Approved'
            ]
        ];
    }

    public static function generateFraudCheckResponse(array $requestData, string $requestId, string $merchantId): array
    {
        $fraudScore = rand(1, 100);
        $riskLevel = $fraudScore <= 30 ? 'LOW' : ($fraudScore <= 70 ? 'MEDIUM' : 'HIGH');
        
        return [
            'transactionId' => 'fraud_' . uniqid(),
            'requestId' => $requestId,
            'transactionState' => 'CHECKED',
            'responseStatus' => $riskLevel === 'HIGH' ? 'DECLINED' : 'APPROVED',
            'responseCode' => $riskLevel === 'HIGH' ? '100' : '000',
            'responseMessage' => $riskLevel === 'HIGH' ? 'High fraud risk detected' : 'Fraud check completed',
            'merchantId' => $merchantId,
            'createdAt' => now()->toISOString(),
            'fraudScore' => $fraudScore,
            'riskLevel' => $riskLevel,
            'fraudRules' => self::generateFraudRules($riskLevel)
        ];
    }

    private static function maskPaymentMethod(array $paymentMethod): array
    {
        if (isset($paymentMethod['card']['accountNumber'])) {
            $accountNumber = $paymentMethod['card']['accountNumber'];
            $paymentMethod['card']['accountNumber'] = '****' . substr($accountNumber, -4);
        }
        
        return $paymentMethod;
    }

    private static function generateFraudRules(string $riskLevel): array
    {
        $rules = [
            [
                'ruleName' => 'VELOCITY_CHECK',
                'ruleResult' => $riskLevel === 'HIGH' ? 'FAIL' : 'PASS',
                'description' => 'Transaction velocity check'
            ],
            [
                'ruleName' => 'GEOLOCATION_CHECK',
                'ruleResult' => 'PASS',
                'description' => 'Geographic location verification'
            ],
            [
                'ruleName' => 'AMOUNT_CHECK',
                'ruleResult' => $riskLevel === 'HIGH' ? 'WARN' : 'PASS',
                'description' => 'Transaction amount analysis'
            ]
        ];

        if ($riskLevel === 'HIGH') {
            $rules[] = [
                'ruleName' => 'BLACKLIST_CHECK',
                'ruleResult' => 'FAIL',
                'description' => 'Card/merchant blacklist verification'
            ];
        }

        return $rules;
    }

    public static function generateMerchantInfo(string $merchantId): array
    {
        return [
            'merchantId' => $merchantId,
            'merchantCategoryCode' => '4899',
            'merchantSoftware' => [
                'companyName' => 'Payment Company',
                'productName' => 'Online Payments API Mock',
                'version' => '1.0.0'
            ]
        ];
    }

    public static function generatePaginationInfo(int $total = 10, int $page = 1, int $pageSize = 10): array
    {
        return [
            'total' => $total,
            'page' => $page,
            'pageSize' => $pageSize,
            'totalPages' => ceil($total / $pageSize)
        ];
    }
}