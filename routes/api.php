<?php

use App\Http\Controllers\Api\External\FinanceController;
use App\Http\Controllers\Api\External\SalesController;
use Illuminate\Support\Facades\Route;

Route::prefix('external')->group(function () {

    Route::get('/ping', function () {
        return response()->json(['status' => 'ok', 'timestamp' => now()->toIso8601String()]);
    });

    // Public GET — browser-accessible JSON
    Route::get('/finance/orders', [FinanceController::class, 'index']);
    Route::get('/finance/orders/{orderNumber}', [FinanceController::class, 'show']);
    Route::get('/sales/orders', [SalesController::class, 'index']);
    Route::get('/sales/orders/{orderNumber}', [SalesController::class, 'show']);

    // Protected mutations (require Bearer token)
    Route::middleware('external-auth:finance')->post('/finance/orders/{orderNumber}/payments', [FinanceController::class, 'store']);
    Route::middleware('external-auth:sales')->patch('/sales/orders/{orderNumber}', [SalesController::class, 'update']);
});
