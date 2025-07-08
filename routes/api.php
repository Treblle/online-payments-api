<?php

use App\Http\Controllers\HealthCheckController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\CaptureController;
use App\Http\Controllers\RefundController;
use App\Http\Controllers\VerificationController;
use App\Http\Controllers\FraudCheckController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware(['api', 'treblle'])
->prefix('v2')
->group(function () {
    
    // Health Check endpoints
    Route::get('/healthcheck/payments', [HealthCheckController::class, 'payments']);
    Route::get('/healthcheck/refunds', [HealthCheckController::class, 'refunds']);
    Route::get('/healthcheck/verifications', [HealthCheckController::class, 'verifications']);
    
    // Payment endpoints
    Route::post('/payments', [PaymentController::class, 'create']);
    Route::get('/payments', [PaymentController::class, 'index']);
    Route::get('/payments/{id}', [PaymentController::class, 'show']);
    Route::patch('/payments/{id}', [PaymentController::class, 'update']);
    
    // Capture endpoints
    Route::post('/payments/{id}/captures', [CaptureController::class, 'createForPayment']);
    Route::get('/captures', [CaptureController::class, 'index']);
    Route::get('/captures/{id}', [CaptureController::class, 'show']);
    
    // Refund endpoints
    Route::post('/refunds', [RefundController::class, 'create']);
    Route::get('/refunds', [RefundController::class, 'index']);
    Route::get('/refunds/{id}', [RefundController::class, 'show']);
    
    // Verification endpoints
    Route::post('/verifications', [VerificationController::class, 'create']);
    Route::get('/verifications', [VerificationController::class, 'index']);
    Route::get('/verifications/{id}', [VerificationController::class, 'show']);
    
    // Fraud Check endpoints
    Route::post('/fraudcheck', [FraudCheckController::class, 'create']);
    Route::get('/fraudcheck', [FraudCheckController::class, 'index']);
    Route::get('/fraudcheck/{id}', [FraudCheckController::class, 'show']);
});