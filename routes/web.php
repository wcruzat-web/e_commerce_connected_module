<?php

// ============================================================================
// ROUTES — Developer attribution
// [CRUZAT] = original routes (admin, cart, checkout, payment, success, tracking)
// [AGNER]  = new auth, customer portal, storefront (from _new_auth integration)
// ============================================================================

use App\Http\Controllers\Auth\DevLoginController;
use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\Customer\AddressController;
use App\Http\Controllers\Customer\ChangePasswordController;
use App\Http\Controllers\Customer\DashboardController;
use App\Http\Controllers\Customer\NotificationController;
use App\Http\Controllers\Customer\OrderController as CustomerOrderController;
use App\Http\Controllers\Customer\PaymentMethodController;
use App\Http\Controllers\Customer\ProfileController;
use App\Http\Controllers\Customer\SettingController;
use App\Http\Controllers\Customer\WishlistController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\StoreController;
use App\Http\Controllers\SuccessController;
use App\Http\Controllers\TrackingController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Landing
|--------------------------------------------------------------------------
*/
Route::get('/', fn () => view('auth.login'));

/*
|--------------------------------------------------------------------------
| Guest-only auth pages  [AGNER — replaced original CRUZAT auth]
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

Route::get('/auth/google', [GoogleController::class, 'redirect'])->name('google.redirect');
Route::get('/auth/google/callback', [GoogleController::class, 'callback'])->name('google.callback');

Route::get('/dev-login', [DevLoginController::class, 'login'])->name('dev-login');
Route::post('/logout', [DevLoginController::class, 'logout'])->name('logout');

/*
|--------------------------------------------------------------------------
| Authenticated area  [AGNER — customer portal + storefront]
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {

    // Customer portal  [AGNER]
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Payment methods  [AGNER]
    Route::get('/payment-methods', [PaymentMethodController::class, 'index'])->name('payment-methods');
    Route::get('/payment-methods/add', [PaymentMethodController::class, 'create'])->name('payment-methods.create');
    Route::post('/payment-methods', [PaymentMethodController::class, 'store'])->name('payment-methods.store');
    Route::get('/payment-methods/{paymentMethod}/edit', [PaymentMethodController::class, 'edit'])->name('payment-methods.edit');
    Route::put('/payment-methods/{paymentMethod}', [PaymentMethodController::class, 'update'])->name('payment-methods.update');
    Route::delete('/payment-methods/{paymentMethod}', [PaymentMethodController::class, 'destroy'])->name('payment-methods.destroy');
    Route::post('/payment-methods/{paymentMethod}/default', [PaymentMethodController::class, 'setDefault'])->name('payment-methods.default');
    Route::get('/add-payment', [PaymentMethodController::class, 'create'])->name('add-payment');
    Route::get('/edit-payment/{paymentMethod}', [PaymentMethodController::class, 'edit'])->name('edit-payment');

    // Wishlist  [AGNER]
    Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist');
    Route::post('/wishlist', [WishlistController::class, 'store'])->name('wishlist.store');
    Route::post('/wishlist/toggle', [WishlistController::class, 'toggle'])->name('wishlist.toggle');
    Route::post('/wishlist/{item}/move', [WishlistController::class, 'moveToCart'])->name('wishlist.moveToCart');
    Route::delete('/wishlist/{item}', [WishlistController::class, 'destroy'])->name('wishlist.destroy');

    // Notifications  [AGNER]
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications');
    Route::post('/notifications/read-all', [NotificationController::class, 'markAllRead'])->name('notifications.read-all');
    Route::post('/notifications/{notification}/read', [NotificationController::class, 'markRead'])->name('notifications.read');

    // Settings  [AGNER]
    Route::get('/settings', [SettingController::class, 'index'])->name('settings');
    Route::post('/settings', [SettingController::class, 'update'])->name('settings.update');

    // Profile + change password  [AGNER]
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
    Route::post('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::get('/change-password', [ChangePasswordController::class, 'edit'])->name('change-password');
    Route::post('/change-password', [ChangePasswordController::class, 'update'])->name('change-password.update');

    // Addresses  [AGNER]
    Route::get('/addresses', [AddressController::class, 'index'])->name('addresses');
    Route::get('/add-address', fn () => view('customer.add-address'))->name('add-address');
    Route::post('/addresses', [AddressController::class, 'store'])->name('addresses.store');
    Route::get('/addresses/{address}/edit', [AddressController::class, 'edit'])->name('addresses.edit');
    Route::put('/addresses/{address}', [AddressController::class, 'update'])->name('addresses.update');
    Route::delete('/addresses/{address}', [AddressController::class, 'destroy'])->name('addresses.destroy');

    // Help  [AGNER]
    Route::get('/help', fn () => view('store.help'))->name('help');

    // Storefront  [AGNER → CRUZAT shop restored]
    Route::get('/home', [StoreController::class, 'home'])->name('home');
    Route::get('/products', fn () => redirect()->route('home'))->name('products');
    Route::get('/products/{product}', fn () => redirect()->route('home'))->name('product.show');
    Route::get('/shop', fn () => redirect()->route('home'))->name('products.index');

    // Cart  [CRUZAT — restored original CartController]
    Route::get('/cart', [CartController::class, 'index'])->name('cart');
    Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
    Route::patch('/cart/{cartItem}', [CartController::class, 'updateQuantity'])->name('cart.update');
    Route::delete('/cart/{cartItem}', [CartController::class, 'remove'])->name('cart.remove');

    // Orders / History / Checkout / Receive  [AGNER — customer order views]
    Route::get('/orders', [CustomerOrderController::class, 'index'])->name('orders');
    Route::get('/history', [CustomerOrderController::class, 'history'])->name('history');
    Route::post('/orders/checkout', [CustomerOrderController::class, 'checkout'])->name('orders.checkout');
    Route::post('/orders/{order}/receive', [CustomerOrderController::class, 'receive'])->name('orders.receive');

    // Checkout / Payment / Success / Tracking  [CRUZAT — original untouched]
    Route::get('/tracking', [TrackingController::class, 'index'])->name('tracking');
    Route::match(['get', 'post'], '/track', [TrackingController::class, 'track'])->name('orders.track');

    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout');
    Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');
    Route::post('/checkout/address', [CheckoutController::class, 'saveAddress'])->name('checkout.address.save');

    Route::get('/payment', [PaymentController::class, 'index'])->name('payment');
    Route::post('/payment', [PaymentController::class, 'process'])->name('payment.process');

    Route::get('/success', [SuccessController::class, 'index'])->name('success');

});

/*
|--------------------------------------------------------------------------
| Admin Dashboard  [CRUZAT — untouched]
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->middleware(['auth', 'role:super_admin,admin'])->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
    Route::get('/dashboard/print', [AdminDashboardController::class, 'print'])->name('admin.dashboard.print');
    Route::get('/orders', [AdminOrderController::class, 'index'])->name('admin.orders');
    Route::get('/orders/{order}', [AdminOrderController::class, 'show'])->name('admin.orders.show');
    Route::post('/orders/{order}/payment', [AdminOrderController::class, 'updatePayment'])->name('admin.orders.payment');
    Route::post('/orders/{order}/status', [AdminOrderController::class, 'updateStatus'])->name('admin.orders.status');
    Route::post('/orders/{order}/tracking', [AdminOrderController::class, 'updateTracking'])->name('admin.orders.tracking');
    Route::view('/products', 'pages.admin.products.index')->name('admin.products');
    Route::view('/inventory', 'pages.admin.inventory.index')->name('admin.inventory');
});

/*
|--------------------------------------------------------------------------
| Admin — User Management (super_admin only)  [CRUZAT — untouched]
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->middleware(['auth', 'role:super_admin'])->group(function () {
    Route::get('/users', [UserController::class, 'index'])->name('admin.users');
    Route::get('/users/create', [UserController::class, 'create'])->name('admin.users.create');
    Route::post('/users', [UserController::class, 'store'])->name('admin.users.store');
});
