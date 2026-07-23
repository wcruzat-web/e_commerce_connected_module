<?php

// ============================================================================
// ROUTES — Developer attribution
// [CRUZAT] = original routes (admin, cart, checkout, payment, success, tracking)
// [AGNER]  = new auth, customer portal, storefront (from _new_auth integration)
// [HAINZ]  = real product shop, reviews, stock management
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
use App\Http\Controllers\ShopController;
use App\Http\Controllers\StoreController;
use App\Http\Controllers\SuccessController;
use App\Http\Controllers\TrackingController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

/*
|--------------------------------------------------------------------------
| Landing
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    if (auth()->check()) {
        $role = auth()->user()->role;
        if (in_array($role, ['super_admin', 'admin'])) {
            return redirect()->route('admin.dashboard');
        }
        return redirect()->route('shop');
    }
    return view('auth.login');
});

/*
|--------------------------------------------------------------------------
| Guest-only auth pages  [AGNER — replaced original CRUZAT auth]
|--------------------------------------------------------------------------
*/
Route::get('/login', function () {
    if (auth()->check()) {
        $role = auth()->user()->role;
        if (in_array($role, ['super_admin', 'admin'])) {
            return redirect()->route('admin.dashboard');
        }
        return redirect()->route('shop');
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
    Route::post('/wishlist/bulk-move', [WishlistController::class, 'bulkMoveToCart'])->name('wishlist.bulkMove');
    Route::post('/wishlist/bulk-destroy', [WishlistController::class, 'destroyMultiple'])->name('wishlist.bulkDestroy');
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
    Route::get('/add-address', fn () => view('pages.customer.addresses.add-address'))->name('add-address');
    Route::post('/addresses', [AddressController::class, 'store'])->name('addresses.store');
    Route::get('/addresses/{address}/edit', [AddressController::class, 'edit'])->name('addresses.edit');
    Route::put('/addresses/{address}', [AddressController::class, 'update'])->name('addresses.update');
    Route::delete('/addresses/{address}', [AddressController::class, 'destroy'])->name('addresses.destroy');

    // Help  [AGNER]
    Route::get('/help', fn () => view('store.help'))->name('help');

    // Cart  [CRUZAT — restored original CartController]
    Route::get('/cart', [CartController::class, 'index'])->name('cart');
    Route::get('/cart/summary', [CartController::class, 'summaryJson'])->name('cart.summary');
    Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
    Route::post('/cart/voucher', [CartController::class, 'applyVoucher'])->name('cart.voucher.apply');
    Route::post('/cart/voucher/remove', [CartController::class, 'removeVoucher'])->name('cart.voucher.remove');
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
    Route::get('/tracking/{order}/poll', [TrackingController::class, 'poll'])->name('tracking.poll');  // [AGNER]
    Route::get('/tracking/{order}', [TrackingController::class, 'show'])->name('tracking.show');

    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout');
    Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');
    Route::post('/checkout/address', [CheckoutController::class, 'saveAddress'])->name('checkout.address.save');

    Route::get('/payment', [PaymentController::class, 'index'])->name('payment');
    Route::post('/payment', [PaymentController::class, 'process'])->name('payment.process');

    Route::get('/success', [SuccessController::class, 'index'])->name('success');

    // Shop — customer-only (auth + customer role)  [HAINZ]
    Route::middleware('customer')->group(function () {
        Route::get('/shop', [ShopController::class, 'index'])->name('shop');
        Route::get('/products', [ShopController::class, 'index'])->name('products.index');
        Route::get('/products/{product}', [ShopController::class, 'show'])->name('products.show');
        Route::get('/home', fn () => redirect()->route('shop'))->name('home');
        Route::post('/shop/review', [ShopController::class, 'review'])->name('shop.review');
        Route::post('/shop/decrement-stock', [ShopController::class, 'decrementStock'])->name('shop.decrement-stock');
        Route::post('/shop/restore-stock', [ShopController::class, 'restoreStock'])->name('shop.restore-stock');
    });

    // Product CRUD  [HAINZ — dummy product management]
    Route::get('/dummy/products', function () {
        $products = App\Models\Product::withCount(['specifications', 'compatibilities'])->latest()->get();
        return view('pages.dummy.products', compact('products'));
    });
    Route::get('/dummy/add-product', function () {
        $categories = App\Models\Category::all();
        return view('pages.dummy.add-product', compact('categories'));
    });
    Route::post('/dummy/add-product', function (Illuminate\Http\Request $request) {
        $data = $request->validate([
            'name' => 'required|string|max:150',
            'sku' => 'required|string|max:100|unique:products,sku',
            'brand' => 'nullable|string|max:100',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric|min:0',
            'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'stock' => 'nullable|integer|min:0',
            'badge' => 'nullable|string|max:50',
        ]);
        $data['slug'] = Str::slug($request->name . '-' . uniqid());
        $data['is_active'] = true;
        if (empty($data['badge'])) $data['badge'] = null;
        if ($request->hasFile('featured_image')) {
            $data['featured_image'] = 'storage/' . $request->file('featured_image')->store('products', 'public');
        }
        $product = App\Models\Product::create($data);
        return redirect("/dummy/add-specs/{$product->id}")->with('success', "Product '{$product->name}' added! Now add its specifications and compatibility.");
    });
    Route::get('/dummy/add-specs/{product}', function (App\Models\Product $product) {
        $specCategories = ['Core Architectures', 'Performance', 'Memory', 'Connectivity', 'Thermal & Power', 'Physical'];
        $compatCategories = ['Recommended PSU', 'Compatible Cases', 'Platform Support', 'Supported RAM', 'Motherboard Compatibility'];
        return view('pages.dummy.add-specs', compact('product', 'specCategories', 'compatCategories'));
    });
    Route::post('/dummy/add-specs/{product}', function (Illuminate\Http\Request $request, App\Models\Product $product) {
        $data = $request->validate([
            'specs' => 'nullable|array',
            'specs.*.category_name' => 'required|string|max:100',
            'specs.*.label' => 'required|string|max:100',
            'specs.*.value' => 'nullable|string|max:100',
            'compatibilities' => 'nullable|array',
            'compatibilities.*.category_name' => 'required|string|max:100',
            'compatibilities.*.item_name' => 'required|string|max:150',
        ]);
        if ($request->has('specs')) {
            foreach ($data['specs'] as $spec) {
                $product->specifications()->create($spec);
            }
        }
        if ($request->has('compatibilities')) {
            foreach ($data['compatibilities'] as $compat) {
                \App\Models\ProductCompatibility::create([
                    'product_id' => $product->id,
                    'category_name' => $compat['category_name'],
                    'item_name' => $compat['item_name'],
                ]);
            }
        }
        return redirect('/shop')->with('success', 'Product added successfully!');
    });
    Route::get('/dummy/edit-product/{product}', function (App\Models\Product $product) {
        $categories = App\Models\Category::all();
        return view('pages.dummy.edit-product', compact('product', 'categories'));
    });
    Route::post('/dummy/edit-product/{product}', function (Illuminate\Http\Request $request, App\Models\Product $product) {
        $data = $request->validate([
            'name' => 'required|string|max:150',
            'sku' => 'required|string|max:100|unique:products,sku,' . $product->id,
            'brand' => 'nullable|string|max:100',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric|min:0',
            'stock' => 'nullable|integer|min:0',
        ]);
        $product->update($data);
        return redirect("/dummy/products")->with('success', "Product '{$product->name}' updated!");
    });
    Route::get('/dummy/edit-specs/{product}', function (App\Models\Product $product) {
        $product->load('specifications', 'compatibilities');
        $specCategories = ['Core Architectures', 'Performance', 'Memory', 'Connectivity', 'Thermal & Power', 'Physical'];
        $compatCategories = ['Recommended PSU', 'Compatible Cases', 'Platform Support', 'Supported RAM', 'Motherboard Compatibility'];
        return view('pages.dummy.edit-specs', compact('product', 'specCategories', 'compatCategories'));
    });
    Route::post('/dummy/edit-specs/{product}', function (Illuminate\Http\Request $request, App\Models\Product $product) {
        $data = $request->validate([
            'specs' => 'nullable|array',
            'specs.*.category_name' => 'required|string|max:100',
            'specs.*.label' => 'required|string|max:100',
            'specs.*.value' => 'nullable|string|max:100',
            'compatibilities' => 'nullable|array',
            'compatibilities.*.category_name' => 'required|string|max:100',
            'compatibilities.*.item_name' => 'required|string|max:150',
        ]);
        $product->specifications()->delete();
        if ($request->has('specs')) {
            foreach ($data['specs'] as $spec) {
                $product->specifications()->create($spec);
            }
        }
        $product->compatibilities()->delete();
        if ($request->has('compatibilities')) {
            foreach ($data['compatibilities'] as $compat) {
                \App\Models\ProductCompatibility::create([
                    'product_id' => $product->id,
                    'category_name' => $compat['category_name'],
                    'item_name' => $compat['item_name'],
                ]);
            }
        }
        return redirect("/dummy/edit-specs/{$product->id}")->with('success', "Specifications and compatibility for '{$product->name}' updated!");
    });
    Route::delete('/dummy/products/{product}', function (App\Models\Product $product) {
        $product->specifications()->delete();
        $product->compatibilities()->delete();
        $product->reviews()->delete();
        \App\Models\CartItem::where('product_id', $product->id)->delete();
        $product->delete();
        return redirect('/dummy/products')->with('success', "Product '{$product->name}' deleted!");
    });
});

/*
|--------------------------------------------------------------------------
| Admin Dashboard  [CRUZAT — untouched]
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->middleware(['auth', 'role:super_admin,admin'])->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
    Route::get('/dashboard/notifications', [AdminDashboardController::class, 'notifications'])->name('admin.dashboard.notifications');
    Route::get('/dashboard/print', [AdminDashboardController::class, 'print'])->name('admin.dashboard.print');
    Route::get('/orders', [AdminOrderController::class, 'index'])->name('admin.orders');
    Route::get('/orders/{order}', [AdminOrderController::class, 'show'])->name('admin.orders.show');
    Route::post('/orders/{order}/payment', [AdminOrderController::class, 'updatePayment'])->name('admin.orders.payment');
    Route::post('/orders/{order}/status', [AdminOrderController::class, 'updateStatus'])->name('admin.orders.status');
    Route::post('/orders/{order}/tracking', [AdminOrderController::class, 'updateTracking'])->name('admin.orders.tracking');
    Route::view('/products', 'pages.admin.products.index')->name('admin.products');
    Route::view('/inventory', 'pages.admin.inventory.index')->name('admin.inventory');
    Route::resource('coupons', \App\Http\Controllers\Admin\CouponController::class)->names('admin.coupons');
});

/*
|--------------------------------------------------------------------------
| Admin — Esteban Product & Promo API  [ESTEBAN]
|--------------------------------------------------------------------------
*/
// ESTEBAN — API group: all product/promo/inventory routes under /api/admin (was /api/)
Route::prefix('api/admin')->middleware(['auth', 'role:super_admin,admin'])->group(function () {
    Route::get('/products', [\App\Http\Controllers\Admin\Api\ProductController::class, 'index']);
    Route::post('/products', [\App\Http\Controllers\Admin\Api\ProductController::class, 'store']);
    Route::put('/products/{id}', [\App\Http\Controllers\Admin\Api\ProductController::class, 'update']);
    Route::delete('/products/{id}', [\App\Http\Controllers\Admin\Api\ProductController::class, 'destroy']);
    Route::patch('/products/{id}/featured', [\App\Http\Controllers\Admin\Api\ProductController::class, 'toggleFeatured']); // ESTEBAN — added: featured toggle route
    Route::get('/promos', [\App\Http\Controllers\Admin\Api\PromoBannerController::class, 'index']);
    Route::post('/promos', [\App\Http\Controllers\Admin\Api\PromoBannerController::class, 'store']);
    Route::delete('/promos/{id}', [\App\Http\Controllers\Admin\Api\PromoBannerController::class, 'destroy']);
    // ESTEBAN — changed: returns distinct products.category instead of querying categories table
    Route::get('/categories', function () {
        return \App\Models\Product::whereNotNull('category')->distinct()->orderBy('category')->pluck('category');
    });
    // ESTEBAN — added: inventory API routes for inventory monitoring page (V2.9)
    Route::get('/inventory/stats', [\App\Http\Controllers\Admin\Api\InventoryController::class, 'stats']);
    Route::get('/inventory/warehouses', [\App\Http\Controllers\Admin\Api\InventoryController::class, 'warehouses']);
    Route::post('/inventory/sync', [\App\Http\Controllers\Admin\Api\InventoryController::class, 'forceSync']);
    // ESTEBAN — revenue computed from orders table via DashboardService (was revenue_overview table)
    Route::get('/revenue', [\App\Http\Controllers\Admin\Api\InventoryController::class, 'revenue']);
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
