<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\SuccessController;
use App\Http\Controllers\TrackingController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\OrderController;
use App\Models\CustomerAddress;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/dummy/shop');



/*
|--------------------------------------------------------------------------
| Customer Pages (Requires Login)
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {

    Route::get('/tracking', [TrackingController::class, 'index'])->name('tracking');
    Route::match(['get', 'post'], '/track', [TrackingController::class, 'track'])->name('orders.track');

    Route::get('/cart', [CartController::class, 'index'])->name('cart');
    Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
    Route::patch('/cart/{cartItem}', [CartController::class, 'updateQuantity'])->name('cart.update');
    Route::delete('/cart/{cartItem}', [CartController::class, 'remove'])->name('cart.remove');

    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout');
    Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');
    Route::post('/checkout/address', [CheckoutController::class, 'saveAddress'])->name('checkout.address.save');

    Route::get('/payment', [PaymentController::class, 'index'])->name('payment');
    Route::post('/payment', [PaymentController::class, 'process'])->name('payment.process');

    Route::get('/success', [SuccessController::class, 'index'])->name('success');

});

/*
|--------------------------------------------------------------------------
| Customer — Authentication
|--------------------------------------------------------------------------
*/

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
Route::view('/forgot-password', 'pages.customer.auth.forgot-password')->name('forgot.password');
Route::post('/forgot-password', function () {
    return back()->with('status', 'Password reset is not yet implemented.');
});

/*
|--------------------------------------------------------------------------
| Dummy Pages — Shop & Account (Placeholder)
|--------------------------------------------------------------------------
*/

Route::get('/dummy/shop', function () {
    $products = collect([
        (object) [
            'brand' => 'NVIDIA', 'name' => 'NVIDIA GeForce RTX 4090', 'price' => 1799.99, 'sale_price' => null,
            'rating' => 4.9, 'review_count' => 48, 'badge' => 'Best Seller',
            'featured_image' => 'https://placehold.co/200x200?text=RTX+4090',
            'category' => (object) ['name' => 'Graphics Cards'],
            'slug' => 'nvidia-geforce-rtx-4090',
        ],
        (object) [
            'brand' => 'AMD', 'name' => 'AMD Ryzen 7 7800X3D', 'price' => 449.99, 'sale_price' => null,
            'rating' => 4.8, 'review_count' => 36, 'badge' => 'Best Seller',
            'featured_image' => 'https://placehold.co/200x200?text=Ryzen+7',
            'category' => (object) ['name' => 'Processors'],
            'slug' => 'amd-ryzen-7-7800x3d',
        ],
        (object) [
            'brand' => 'Intel', 'name' => 'Intel Core i9-14900K', 'price' => 589.99, 'sale_price' => 499.99,
            'rating' => 4.7, 'review_count' => 52, 'badge' => 'Sale',
            'featured_image' => 'https://placehold.co/200x200?text=i9-14900K',
            'category' => (object) ['name' => 'Processors'],
            'slug' => 'intel-core-i9-14900k',
        ],
        (object) [
            'brand' => 'Samsung', 'name' => 'Samsung 990 Pro 2TB NVMe', 'price' => 249.99, 'sale_price' => 199.99,
            'rating' => 4.6, 'review_count' => 28, 'badge' => 'Sale',
            'featured_image' => 'https://placehold.co/200x200?text=990+Pro',
            'category' => (object) ['name' => 'Storage'],
            'slug' => 'samsung-990-pro-2tb',
        ],
    ]);

    return view('pages.dummy.shop.index', compact('products'));
})->name('products.index');

Route::get('/dummy/shop/{product:slug}', function (App\Models\Product $product) {
    $product->load('category', 'images', 'specifications');
    return view('pages.dummy.shop.show', compact('product'));
})->name('products.show');

Route::view('/dummy/account', 'pages.dummy.account')->name('account');

/*
|--------------------------------------------------------------------------
| Dummy Pages — Preview Only
|--------------------------------------------------------------------------
*/

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
    if (empty($data['badge'])) {
        $data['badge'] = null;
    }

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
        'specs.*.attribute_name' => 'required|string|max:100',
        'specs.*.attribute_value' => 'nullable|string|max:100',
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

Route::get('/dummy/products', function () {
    $products = App\Models\Product::withCount(['specifications', 'compatibilities'])->latest()->get();
    return view('pages.dummy.products', compact('products'));
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
        'specs.*.attribute_name' => 'required|string|max:100',
        'specs.*.attribute_value' => 'nullable|string|max:100',
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

Route::view('/dummy/cart', 'pages.dummy.cart')->middleware('auth')->name('dummy.cart');

Route::get('/dummy/addresses', function () {
    $addresses = CustomerAddress::where('customer_id', Auth::id())->get();
    return view('pages.dummy.address-preview', compact('addresses'));
})->middleware('auth')->name('dummy.addresses');

/*
|--------------------------------------------------------------------------
| Admin Dashboard
|--------------------------------------------------------------------------
*/

Route::prefix('admin')->middleware(['auth', 'role:super_admin,admin'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
    Route::get('/dashboard/print', [DashboardController::class, 'print'])->name('admin.dashboard.print');
    Route::get('/orders', [OrderController::class, 'index'])->name('admin.orders');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('admin.orders.show');
    Route::post('/orders/{order}/payment', [OrderController::class, 'updatePayment'])->name('admin.orders.payment');
    Route::post('/orders/{order}/status', [OrderController::class, 'updateStatus'])->name('admin.orders.status');
    Route::post('/orders/{order}/tracking', [OrderController::class, 'updateTracking'])->name('admin.orders.tracking');
    Route::view('/products', 'pages.admin.products.index')->name('admin.products');
    Route::view('/inventory', 'pages.admin.inventory.index')->name('admin.inventory');
});

/*
|--------------------------------------------------------------------------
| Admin — User Management (super_admin only)
|--------------------------------------------------------------------------
*/

Route::prefix('admin')->middleware(['auth', 'role:super_admin'])->group(function () {
    Route::get('/users', [App\Http\Controllers\Admin\UserController::class, 'index'])->name('admin.users');
    Route::get('/users/create', [App\Http\Controllers\Admin\UserController::class, 'create'])->name('admin.users.create');
    Route::post('/users', [App\Http\Controllers\Admin\UserController::class, 'store'])->name('admin.users.store');
});

Route::get('/shop', function () {
    $dbProducts = App\Models\Product::with(['category', 'specifications', 'compatibilities', 'reviews.user'])
        ->where('is_active', true)
        ->get();

    $products = $dbProducts->map(function ($p) {
        $groupedSpecs = $p->specifications->groupBy('category_name');
        $groupedCompat = $p->compatibilities->groupBy('category_name');
        $reviews = $p->reviews;
        $reviewCount = $reviews->count();

        // Calculate rating from actual review ratings
        $rating = $reviewCount > 0 ? round($reviews->avg('rating'), 2) : 0;

        // Distribution from actual ratings
        $dist = [0, 0, 0, 0, 0];
        foreach ($reviews as $r) {
            if ($r->rating >= 1 && $r->rating <= 5) $dist[5 - $r->rating]++;
        }
        $reviewDistribution = $reviewCount > 0
            ? array_map(fn($c) => round(($c / $reviewCount) * 100), $dist)
            : [0, 0, 0, 0, 0];

        $detailSpecs = $groupedSpecs->map(function ($specs, $category) {
            return [
                'section' => $category,
                'items' => $specs->map(function ($s) {
                    return ['label' => $s->attribute_name, 'value' => $s->attribute_value ?? 'N/A'];
                })->values()->toArray(),
            ];
        })->values()->toArray();

        $compatGroups = $groupedCompat->map(function ($items, $category) {
            return [
                'category' => $category,
                'items' => $items->pluck('item_name')->toArray(),
            ];
        })->values()->toArray();

        return [
            'id' => (string) $p->id,
            'name' => $p->name,
            'brand' => $p->brand ?? 'Generic',
            'price' => (float) ($p->sale_price ?: $p->price),
            'sku' => $p->sku,
            'category' => $p->category->name ?? 'Uncategorized',
            'image' => $p->featured_image ?? 'https://placehold.co/200x200?text=No+Image',
            'badge' => $p->badge ?? '',
            'badgeClass' => $p->badge === 'Sale' ? 'bg-red-500' : ($p->badge === 'Best Seller' ? 'bg-amber-500' : 'bg-blue-900'),
            'stock' => (int) $p->stock,
            'inStock' => $p->stock > 0,
            'rating' => $rating,
            'reviewCount' => $reviewCount,
            'categoryMeta' => strtoupper($p->category->name ?? ''),
            'chipset' => $p->specifications->firstWhere('attribute_name', 'Chipset')->attribute_value ?? '',
            'socket' => $p->specifications->firstWhere('attribute_name', 'Socket')->attribute_value ?? '',
            'vram' => '',
            'specs' => $p->specifications->take(3)->pluck('attribute_name')->toArray(),
            'atAGlance' => $p->specifications->take(6)->map(function ($s) {
                return ['label' => $s->attribute_name, 'value' => $s->attribute_value ?? 'N/A'];
            })->toArray(),
            'detailSpecs' => $detailSpecs,
            'compatGroups' => $compatGroups,
            'userReviews' => $reviews->map(function ($r) {
                return [
                    'id' => $r->review_id,
                    'name' => ($r->user->first_name ?? '') . ' ' . ($r->user->last_name ?? ''),
                    'initials' => strtoupper(substr($r->user->first_name ?? 'A', 0, 1) . substr($r->user->last_name ?? 'U', 0, 1)),
                    'comment' => $r->comment ?? '',
                    'rating' => $r->rating ?? 0,
                    'createdAt' => $r->created_at ? $r->created_at->diffForHumans() : '',
                ];
            })->values()->toArray(),
            'reviewDistribution' => $reviewDistribution,
        ];
    })->values();

    $isLoggedIn = auth()->check();
    $authUser = $isLoggedIn ? auth()->user() : null;

    return view('pages.customer.shop.shop', compact('products', 'isLoggedIn', 'authUser'));
});

Route::post('/shop/review', function (Illuminate\Http\Request $request) {
    $data = $request->validate([
        'product_id' => 'required|exists:products,id',
        'comment' => 'required|string|max:2000',
        'rating' => 'required|integer|min:1|max:5',
    ]);

    $review = App\Models\ProductReview::create([
        'product_id' => $data['product_id'],
        'user_id' => auth()->id(),
        'comment' => $data['comment'],
        'rating' => $data['rating'],
        'created_at' => now(),
    ]);

    // Update product rating and review_count
    $product = App\Models\Product::find($data['product_id']);
    $allRatings = App\Models\ProductReview::where('product_id', $data['product_id'])->pluck('rating');
    $avgRating = round($allRatings->average(), 2);
    $product->update([
        'rating' => $avgRating,
        'review_count' => $allRatings->count(),
    ]);

    // Calculate distribution
    $dist = [0, 0, 0, 0, 0];
    foreach ($allRatings as $r) {
        if ($r >= 1 && $r <= 5) $dist[5 - $r]++;
    }
    $total = $allRatings->count();
    $reviewDistribution = array_map(fn($c) => $total > 0 ? round(($c / $total) * 100) : 0, $dist);

    return response()->json([
        'success' => true,
        'productRating' => $avgRating,
        'reviewDistribution' => $reviewDistribution,
        'review' => [
            'id' => $review->review_id,
            'name' => auth()->user()->first_name . ' ' . auth()->user()->last_name,
            'initials' => strtoupper(substr(auth()->user()->first_name ?? 'A', 0, 1) . substr(auth()->user()->last_name ?? 'U', 0, 1)),
            'comment' => $review->comment,
            'rating' => $review->rating,
            'createdAt' => $review->created_at->diffForHumans(),
        ],
    ]);
})->middleware('auth');

Route::post('/shop/decrement-stock', function (Illuminate\Http\Request $request) {
    $data = $request->validate([
        'product_id' => 'required|exists:products,id',
        'quantity' => 'required|integer|min:1',
    ]);

    $product = App\Models\Product::findOrFail($data['product_id']);
    if ($product->stock < $data['quantity']) {
        return response()->json(['success' => false, 'message' => 'Not enough stock'], 422);
    }
    $product->decrement('stock', $data['quantity']);

    return response()->json([
        'success' => true,
        'new_stock' => $product->fresh()->stock,
    ]);
})->middleware('auth');

Route::post('/shop/restore-stock', function (Illuminate\Http\Request $request) {
    $data = $request->validate([
        'product_id' => 'required|exists:products,id',
        'quantity' => 'required|integer|min:1',
    ]);

    $product = App\Models\Product::findOrFail($data['product_id']);
    $product->increment('stock', $data['quantity']);

    return response()->json([
        'success' => true,
        'new_stock' => $product->fresh()->stock,
    ]);
})->middleware('auth');

Route::delete('/dummy/products/{product}', function (App\Models\Product $product) {
    $name = $product->name;
    $product->specifications()->delete();
    $product->compatibilities()->delete();
    $product->reviews()->delete();
    App\Models\CartItem::where('product_id', $product->id)->delete();
    $product->delete();
    return redirect('/dummy/products')->with('success', "Product '{$name}' deleted!");
});
