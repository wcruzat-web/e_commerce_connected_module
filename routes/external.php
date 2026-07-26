<?php

use Illuminate\Support\Facades\Route;

Route::prefix('external')->group(function () {
    Route::view('/finance', 'pages.external.finance.index');
    Route::view('/sales', 'pages.external.sales.index');
});
