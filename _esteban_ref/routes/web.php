<?php

use App\Http\Controllers\ProductController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\PromoBannerController;
use Illuminate\Support\Facades\Route;

Route::get('/dashboard', function () {
    return view('dashboard');
})->name('admin.dashboard');

Route::get('/checkout', function () {
    return view('checkout');
})->name('checkout');

Route::get('/api/products', [ProductController::class, 'index']);
Route::post('/api/products', [ProductController::class, 'store']);
Route::put('/api/products/{id}', [ProductController::class, 'update']);
Route::delete('/api/products/{id}', [ProductController::class, 'destroy']);
Route::patch('/api/products/{id}/featured', [ProductController::class, 'toggleFeatured']);

Route::get('/api/inventory/stats', [InventoryController::class, 'stats']);
Route::get('/api/inventory/warehouses', [InventoryController::class, 'warehouses']);
Route::post('/api/inventory/sync', [InventoryController::class, 'forceSync']);
Route::get('/api/revenue', [InventoryController::class, 'revenue']);

Route::get('/api/promos', [PromoBannerController::class, 'index']);
Route::post('/api/promos', [PromoBannerController::class, 'store']);
Route::delete('/api/promos/{id}', [PromoBannerController::class, 'destroy']);
