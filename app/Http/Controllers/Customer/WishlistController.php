<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Product;
use App\Models\WishlistItem;
use App\Services\ProductSource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class WishlistController extends Controller
{
    public function index(Request $request): View
    {
        /** @var Customer $customer */
        $customer = $request->user();

        $items = $customer->wishlistItems()
            ->with('wishlist')
            ->orderByDesc('wishlist_item_id')
            ->get();

        return view('pages.customer.wishlist.wishlist', compact('items'));
    }

    public function store(Request $request): RedirectResponse|JsonResponse
    {
        /** @var Customer $customer */
        $customer = $request->user();

        $validated = $request->validate(['product_id' => ['required', 'integer']]);

        $product = ProductSource::find($validated['product_id']);
        abort_unless($product, 404);

        $wishlist = $customer->wishlists()->first();
        if (! $wishlist) {
            $wishlist = $customer->wishlists()->create(['name' => 'Default Wishlist']);
        }

        $exists = $wishlist->items()->where('product_id', $product['id'])->exists();

        if (! $exists) {
            $wishlist->items()->create([
                'customer_id' => $customer->customer_id,
                'product_id' => $product['id'],
                'product_name' => $product['name'],
                'product_description' => $product['description'],
                'product_image' => $product['image'],
                'unit_price' => $product['price'],
                'quantity' => 1,
                'in_stock' => true,
            ]);
        }

        $message = $exists ? 'Already in your wishlist.' : 'Added to wishlist.';

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'ok' => true,
                'message' => $message,
                'wishlistCount' => $customer->wishlistItems()->count(),
            ]);
        }

        return redirect()->back()->with('success', $message);
    }

    public function toggle(Request $request): RedirectResponse|JsonResponse
    {
        /** @var Customer $customer */
        $customer = $request->user();

        $validated = $request->validate(['product_id' => ['required', 'integer']]);
        $product = Product::find($validated['product_id']);
        abort_unless($product, 404);

        $pic = $product->featured_image ?? '';
        if ($pic && !str_starts_with($pic, 'http')) {
            $pic = asset($pic);
        }

        $wishlist = $customer->wishlists()->firstOrCreate(['name' => 'Default Wishlist']);
        $item = $wishlist->items()->where('product_id', $product->id)->first();

        if ($item) {
            $item->delete();
            $added = false;
            $message = 'Removed from wishlist.';
        } else {
            $wishlist->items()->create([
                'customer_id' => $customer->customer_id,
                'product_id' => $product->id,
                'product_name' => $product->name,
                'product_description' => $product->description,
                'product_image' => $pic,
                'unit_price' => $product->sale_price ?? $product->price,
                'quantity' => 1,
                'in_stock' => $product->stock > 0,
            ]);
            $added = true;
            $message = 'Added to wishlist.';
        }

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'ok' => true,
                'added' => $added,
                'message' => $message,
                'wishlistCount' => $customer->wishlistItems()->count(),
            ]);
        }

        return redirect()->back()->with('success', $message);
    }

    public function moveToCart(Request $request, WishlistItem $item): RedirectResponse|JsonResponse
    {
        /** @var Customer $customer */
        $customer = $request->user();
        abort_unless($item->customer_id === $customer->customer_id, 403);

        $cart = session('cart', []);
        $id = $item->product_id;
        if (isset($cart[$id])) {
            $cart[$id]['qty'] = min(99, $cart[$id]['qty'] + 1);
        } else {
            $cart[$id] = [
                'id' => $id,
                'name' => $item->product_name,
                'price' => (float) $item->unit_price,
                'image' => $item->product_image,
                'category' => null,
                'qty' => 1,
            ];
        }
        session(['cart' => $cart]);
        $item->delete();

        $cartCount = count($cart);
        $cartQty = array_sum(array_column($cart, 'qty'));

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'ok' => true,
                'message' => 'Moved to cart.',
                'wishlistCount' => $customer->wishlistItems()->count(),
                'cartCount' => $cartCount,
                'cartQty' => $cartQty,
            ]);
        }

        return redirect()->back()->with('success', 'Moved to cart.');
    }

    public function destroy(Request $request, WishlistItem $item): RedirectResponse|JsonResponse
    {
        /** @var Customer $customer */
        $customer = $request->user();
        abort_unless($item->customer_id === $customer->customer_id, 403);

        $item->delete();

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'ok' => true,
                'message' => 'Removed from wishlist.',
                'wishlistCount' => $customer->wishlistItems()->count(),
            ]);
        }

        return redirect()->back()->with('success', 'Removed from wishlist.');
    }
}
