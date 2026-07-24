<?php

use App\Http\Controllers\Api\External\FinanceController;
use App\Http\Controllers\Api\External\SalesController;
use Illuminate\Support\Facades\Route;

Route::prefix('external')->group(function () {

    Route::get('/ping', function () {
        return response()->json(['status' => 'ok', 'timestamp' => now()->toIso8601String()]);
    });

    Route::middleware('external-auth:finance')->prefix('finance')->group(function () {
        Route::post('/payment-confirmed', [FinanceController::class, 'paymentConfirmed']);
        Route::get('/orders', [FinanceController::class, 'listOrders']);
    });

    Route::middleware('external-auth:sales')->prefix('sales')->group(function () {
        Route::get('/order/{order_number}', [SalesController::class, 'getOrder']);
        Route::get('/orders', [SalesController::class, 'listOrders']);
        Route::post('/update-status', [SalesController::class, 'updateStatus']);
    });
});
