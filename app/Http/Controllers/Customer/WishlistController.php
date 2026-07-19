<?php
// AGNER — wishlist: uses Product model (ERPV0.2, ERPV1.3)

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Product;
use App\Models\WishlistItem;
use App\Services\CartService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class WishlistController extends Controller
{
    public function __construct(
        private CartService $cartService,
    ) {}

    public function index(Request $request): View
    {
        $customer = $request->user();

        $items = $customer->wishlistItems()
            ->with('wishlist')
            ->orderByDesc('wishlist_item_id')
            ->get();

        return view('pages.customer.wishlist.wishlist', compact('items'));
    }

    public function store(Request $request): RedirectResponse|JsonResponse
    {
        $customer = $request->user();

        $validated = $request->validate(['product_id' => ['required', 'integer']]);

        $product = Product::find($validated['product_id']);
        abort_unless($product, 404);

        $wishlist = $customer->wishlists()->first();
        if (! $wishlist) {
            $wishlist = $customer->wishlists()->create(['name' => 'Default Wishlist']);
        }

        $exists = $wishlist->items()->where('product_id', $product->id)->exists();

        if (! $exists) {
            $wishlist->items()->create([
                'customer_id' => $customer->customer_id,
                'product_id' => $product->id,
                'product_name' => $product->name,
                'product_description' => $product->description,
                'product_image' => $product->image_url,
                'unit_price' => $product->price,
                'quantity' => 1,
                'in_stock' => $product->stock > 0,
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
        $customer = $request->user();
        abort_unless($item->customer_id === $customer->customer_id, 403);

        $cart = $this->cartService->getOrCreateCart($customer->customer_id);
        $product = Product::find($item->product_id);

        if (!$product) {
            return $this->errorResponse($request, 'Product no longer exists.');
        }

        if ($product->stock <= 0) {
            return $this->errorResponse($request, 'Product is out of stock.');
        }

        $this->cartService->addItem($cart, $product->id, 1);
        $item->delete();

        return $this->successResponse($request, 'Moved to cart.');
    }

    public function bulkMoveToCart(Request $request): RedirectResponse|JsonResponse
    {
        $customer = $request->user();
        $ids = $request->input('ids', []);

        if (empty($ids)) {
            return $this->errorResponse($request, 'No items selected.');
        }

        $items = WishlistItem::whereIn('wishlist_item_id', $ids)
            ->where('customer_id', $customer->customer_id)
            ->get();

        if ($items->isEmpty()) {
            return $this->errorResponse($request, 'No valid items found.');
        }

        $cart = $this->cartService->getOrCreateCart($customer->customer_id);
        $moved = 0;
        $errors = [];

        foreach ($items as $item) {
            $product = Product::find($item->product_id);

            if (!$product) {
                $errors[] = "{$item->product_name}: Product no longer exists.";
                continue;
            }

            if ($product->stock <= 0) {
                $errors[] = "{$product->name}: Out of stock.";
                continue;
            }

            $this->cartService->addItem($cart, $product->id, 1);
            $item->delete();
            $moved++;
        }

        $message = $moved ? "{$moved} item(s) moved to cart." : 'No items moved.';

        if (!empty($errors)) {
            $message .= ' ' . implode(' ', $errors);
        }

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'ok' => $moved > 0,
                'message' => $message,
                'moved' => $moved,
                'wishlistCount' => $customer->wishlistItems()->count(),
                'cartCount' => $cart->items()->count(),
            ]);
        }

        return redirect()->back()->with($moved > 0 ? 'success' : 'error', $message);
    }

    public function destroy(Request $request, WishlistItem $item): RedirectResponse|JsonResponse
    {
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

    public function destroyMultiple(Request $request): RedirectResponse|JsonResponse
    {
        $customer = $request->user();
        $ids = $request->input('ids', []);

        if (empty($ids)) {
            return $this->errorResponse($request, 'No items selected.');
        }

        $deleted = WishlistItem::whereIn('wishlist_item_id', $ids)
            ->where('customer_id', $customer->customer_id)
            ->delete();

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'ok' => true,
                'message' => "{$deleted} item(s) removed.",
                'wishlistCount' => $customer->wishlistItems()->count(),
            ]);
        }

        return redirect()->back()->with('success', "{$deleted} item(s) removed.");
    }

    private function successResponse(Request $request, string $message): RedirectResponse|JsonResponse
    {
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['ok' => true, 'message' => $message]);
        }
        return redirect()->back()->with('success', $message);
    }

    private function errorResponse(Request $request, string $message): RedirectResponse|JsonResponse
    {
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['ok' => false, 'message' => $message], 422);
        }
        return redirect()->back()->with('error', $message);
    }
}