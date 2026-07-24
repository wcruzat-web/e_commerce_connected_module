<?php

use App\Http\Controllers\External\ExternalSimulatorController;
use App\Http\Controllers\External\FinanceSimulatorController;
use App\Http\Controllers\External\SalesSimulatorController;
use Illuminate\Support\Facades\Route;

Route::prefix('external')->group(function () {

    Route::get('/finance', [FinanceSimulatorController::class, 'index']);
    Route::get('/finance/orders', [FinanceSimulatorController::class, 'listPendingOrders']);

    Route::get('/sales', [SalesSimulatorController::class, 'index']);
    Route::get('/sales/orders', [SalesSimulatorController::class, 'listPaidOrders']);

    Route::get('/order/{order_number}', [ExternalSimulatorController::class, 'lookupOrder']);
});
