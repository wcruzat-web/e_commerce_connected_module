<?php

use App\Http\Controllers\Api\External\FinanceController;
use App\Http\Controllers\Api\External\SalesController;
use Illuminate\Support\Facades\Route;

Route::prefix('external')->group(function () {

    Route::get('/ping', function () {
        return response()->json(['status' => 'ok', 'timestamp' => now()->toIso8601String()]);
    });

    Route::middleware('external-auth:finance')->prefix('finance')->group(function () {
        Route::post('/orders/{order}/payments', [FinanceController::class, 'store']);
        Route::get('/orders/{order}', [FinanceController::class, 'show']);
        Route::get('/orders', [FinanceController::class, 'index']);
    });

    Route::middleware('external-auth:sales')->prefix('sales')->group(function () {
        Route::get('/orders/{order}', [SalesController::class, 'show']);
        Route::get('/orders', [SalesController::class, 'index']);
        Route::patch('/orders/{order}', [SalesController::class, 'update']);
    });
});
