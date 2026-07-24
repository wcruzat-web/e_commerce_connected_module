<?php

use App\Http\Controllers\Admin\ExternalSimulatorController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin/external')->middleware(['auth', 'role:super_admin'])->group(function () {

    Route::get('/simulator', [ExternalSimulatorController::class, 'index'])->name('admin.external.simulator');
    Route::get('/simulator/logs', [ExternalSimulatorController::class, 'logs'])->name('admin.external.simulator.logs');
    Route::get('/simulator/order/{order_number}', [ExternalSimulatorController::class, 'lookupOrder'])->name('admin.external.simulator.lookup');
    Route::get('/simulator/list', [ExternalSimulatorController::class, 'listData'])->name('admin.external.simulator.list');
});
