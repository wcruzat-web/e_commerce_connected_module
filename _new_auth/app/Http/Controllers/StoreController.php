<?php

namespace App\Http\Controllers;

use App\Services\ProductSource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Public storefront pages (Home / Products / Product / Cart).
 * These belong to the Inventory + Sales modules (co-members); kept here
 * so navigation and the cart/wishlist flow can be tested end-to-end.
 * The product catalog is a static dummy source (app/Services/ProductSource).
 */
class StoreController extends Controller
{
    /** Dummy product catalog used across the storefront pages. */
    private function catalog(): array
    {
        return ProductSource::items();
    }

    /** IDs of catalog products already saved to the customer's wishlist. */
    private function wishlistIds(): array
    {
        if (! auth()->check()) {
            return [];
        }

        /** @var \App\Models\Customer $user */
        $user = auth()->user();

        return $user->wishlistItems()->pluck('product_id')->toArray();
    }

    public function home(): View
    {
        $featured = $this->catalog();
        $wishlistIds = $this->wishlistIds();

        return view('store.home', compact('featured', 'wishlistIds'));
    }

    public function products(): View
    {
        $products = $this->catalog();
        $wishlistIds = $this->wishlistIds();

        return view('store.products', compact('products', 'wishlistIds'));
    }

    public function show(int $product): View
    {
        $catalog = $this->catalog();
        $product = $catalog[$product] ?? reset($catalog);
        $wishlistIds = $this->wishlistIds();

        return view('store.product', compact('product', 'wishlistIds'));
    }

    public function cart(): View
    {
        $items = session('cart', []);
        $total = $items ? collect($items)->sum(fn ($i) => $i['price'] * $i['qty']) : 0;

        return view('store.cart', compact('items', 'total'));
    }

    /**
     * Add a product to the cart (session-backed). Supports AJAX (returns JSON
     * with the new cart count) and normal form posts (redirects back).
     */
    public function addToCart(Request $request): RedirectResponse|JsonResponse
    {
        $validated = $request->validate([
            'product_id' => ['required', 'integer'],
            'qty' => ['nullable', 'integer', 'min:1', 'max:99'],
        ]);

        $id = $validated['product_id'];
        abort_unless(ProductSource::find($id), 404);

        $product = ProductSource::find($id);
        $qty = (int) ($validated['qty'] ?? 1);

        $cart = session('cart', []);
        if (isset($cart[$id])) {
            $cart[$id]['qty'] = min(99, $cart[$id]['qty'] + $qty);
        } else {
            $cart[$id] = [
                'id' => $id,
                'name' => $product['name'],
                'price' => $product['price'],
                'image' => $product['image'],
                'category' => $product['category'],
                'qty' => $qty,
            ];
        }
        session(['cart' => $cart]);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'ok' => true,
                'message' => 'Added to cart.',
                'cartCount' => count($cart),
                'cartQty' => $this->cartQty($cart),
            ]);
        }

        return redirect()->back()->with('success', 'Added to cart.');
    }

    /** Update the quantity of a cart line. */
    public function updateCart(Request $request, int $product): RedirectResponse|JsonResponse
    {
        $validated = $request->validate(['qty' => ['required', 'integer', 'min:1', 'max:99']]);

        $cart = session('cart', []);
        if (isset($cart[$product])) {
            $cart[$product]['qty'] = $validated['qty'];
            session(['cart' => $cart]);
        }

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'ok' => true,
                'cartCount' => count($cart),
                'cartQty' => $this->cartQty($cart),
            ]);
        }

        return redirect()->back()->with('success', 'Cart updated.');
    }

    /** Remove a product from the cart. */
    public function removeFromCart(Request $request, int $product): RedirectResponse|JsonResponse
    {
        $cart = session('cart', []);
        unset($cart[$product]);
        session(['cart' => $cart]);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'ok' => true,
                'cartCount' => count($cart),
                'cartQty' => $this->cartQty($cart),
            ]);
        }

        return redirect()->back()->with('success', 'Removed from cart.');
    }

    private function cartQty(array $cart): int
    {
        return array_sum(array_column($cart, 'qty'));
    }
}
