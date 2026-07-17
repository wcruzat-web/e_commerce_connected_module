<?php

use App\Http\Controllers\Auth\DevLoginController;
use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Customer\AddressController;
use App\Http\Controllers\Customer\ChangePasswordController;
use App\Http\Controllers\Customer\DashboardController;
use App\Http\Controllers\Customer\NotificationController;
use App\Http\Controllers\Customer\OrderController;
use App\Http\Controllers\Customer\PaymentMethodController;
use App\Http\Controllers\Customer\ProfileController;
use App\Http\Controllers\Customer\SettingController;
use App\Http\Controllers\Customer\WishlistController;
use App\Http\Controllers\StoreController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Landing: if logged in go to store home, otherwise to login.
|--------------------------------------------------------------------------
*/
Route::get('/', fn () => redirect(auth()->check() ? route('home') : route('login')));

/*
|--------------------------------------------------------------------------
| Guest-only auth pages (login, register, password reset, Google, dev-login)
|--------------------------------------------------------------------------
*/
Route::get('/login', function () {
    if (auth()->check()) {
        return redirect()->route('home');
    }

    return view('auth.login');
})->name('login');
Route::post('/login', [LoginController::class, 'store'])->name('login.store');

Route::get('/register', function () {
    if (auth()->check()) {
        return redirect()->route('home');
    }

    return view('auth.register');
})->name('register');
Route::post('/register', [RegisterController::class, 'store'])->name('register.store');

Route::view('/forgot-password', 'auth.forgot-password')->name('forgot');

// Google OAuth (real via Socialite; dummy fallback when no credentials are set).
Route::get('/auth/google', [GoogleController::class, 'redirect'])->name('google.redirect');
Route::get('/auth/google/callback', [GoogleController::class, 'callback'])->name('google.callback');

// Dev convenience: one-click login as the demo customer.
Route::get('/dev-login', [DevLoginController::class, 'login'])->name('dev-login');

Route::post('/logout', [DevLoginController::class, 'logout'])->name('logout');

/*
|--------------------------------------------------------------------------
| Authenticated area — every page below requires login.
| Guests are redirected to /login by the `auth` middleware.
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {

    // Customer portal
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Payment methods (list + full CRUD)
    Route::get('/payment-methods', [PaymentMethodController::class, 'index'])->name('payment-methods');
    Route::get('/payment-methods/add', [PaymentMethodController::class, 'create'])->name('payment-methods.create');
    Route::post('/payment-methods', [PaymentMethodController::class, 'store'])->name('payment-methods.store');
    Route::get('/payment-methods/{paymentMethod}/edit', [PaymentMethodController::class, 'edit'])->name('payment-methods.edit');
    Route::put('/payment-methods/{paymentMethod}', [PaymentMethodController::class, 'update'])->name('payment-methods.update');
    Route::delete('/payment-methods/{paymentMethod}', [PaymentMethodController::class, 'destroy'])->name('payment-methods.destroy');
    Route::post('/payment-methods/{paymentMethod}/default', [PaymentMethodController::class, 'setDefault'])->name('payment-methods.default');

    Route::get('/add-payment', [PaymentMethodController::class, 'create'])->name('add-payment');
    Route::get('/edit-payment/{paymentMethod}', [PaymentMethodController::class, 'edit'])->name('edit-payment');

    // Wishlist
    Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist');
    Route::post('/wishlist', [WishlistController::class, 'store'])->name('wishlist.store');
    Route::post('/wishlist/toggle', [WishlistController::class, 'toggle'])->name('wishlist.toggle');
    Route::post('/wishlist/{item}/move', [WishlistController::class, 'moveToCart'])->name('wishlist.moveToCart');
    Route::delete('/wishlist/{item}', [WishlistController::class, 'destroy'])->name('wishlist.destroy');

    // Notifications
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications');
    Route::post('/notifications/read-all', [NotificationController::class, 'markAllRead'])->name('notifications.read-all');
    Route::post('/notifications/{notification}/read', [NotificationController::class, 'markRead'])->name('notifications.read');

    // Settings
    Route::get('/settings', [SettingController::class, 'index'])->name('settings');
    Route::post('/settings', [SettingController::class, 'update'])->name('settings.update');

    // Profile + change password
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
    Route::post('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::get('/change-password', [ChangePasswordController::class, 'edit'])->name('change-password');
    Route::post('/change-password', [ChangePasswordController::class, 'update'])->name('change-password.update');

    // Addresses
    Route::get('/addresses', [AddressController::class, 'index'])->name('addresses');
    Route::get('/add-address', fn () => view('customer.add-address'))->name('add-address');
    Route::post('/addresses', [AddressController::class, 'store'])->name('addresses.store');
    Route::get('/addresses/{address}/edit', [AddressController::class, 'edit'])->name('addresses.edit');
    Route::put('/addresses/{address}', [AddressController::class, 'update'])->name('addresses.update');
    Route::delete('/addresses/{address}', [AddressController::class, 'destroy'])->name('addresses.destroy');

    // Help (dummy page so the sidebar link resolves)
    Route::get('/help', fn () => view('store.help'))->name('help');

    // Storefront (also requires login per requirement)
    Route::get('/home', [StoreController::class, 'home'])->name('home');
    Route::get('/products', [StoreController::class, 'products'])->name('products');
    Route::get('/products/{product}', [StoreController::class, 'show'])->name('product.show');
    Route::get('/cart', [StoreController::class, 'cart'])->name('cart');
    Route::post('/cart/add', [StoreController::class, 'addToCart'])->name('cart.add');
    Route::post('/cart/update/{product}', [StoreController::class, 'updateCart'])->name('cart.update');
    Route::post('/cart/remove/{product}', [StoreController::class, 'removeFromCart'])->name('cart.remove');

    // Co-member modules — now data-driven (empty by default).
    Route::get('/orders', [OrderController::class, 'index'])->name('orders');
    Route::get('/history', [OrderController::class, 'history'])->name('history');
    Route::post('/orders/checkout', [OrderController::class, 'checkout'])->name('orders.checkout');
    Route::post('/orders/{order}/receive', [OrderController::class, 'receive'])->name('orders.receive');
});
