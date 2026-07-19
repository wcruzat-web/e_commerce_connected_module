<?php
// CRUZAT — cart: AJAX add-to-cart support (ERPV0.2)

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Services\CartService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function __construct(
        protected CartService $cartService,
    ) {}

    public function index()
    {
        $customer = Auth::user();
        $cart = $this->cartService->getOrCreateCart($customer->customer_id);
        $summary = $this->cartService->getSummary($cart);

        return view('pages.customer.cart.cart', compact('cart', 'summary'));
    }

    public function add(Request $request)
    {
        // CHANGES HERE: added AJAX JSON response below (lines 38-44) for shop add-to-cart
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $customer = Auth::user();
        $cart = $this->cartService->getOrCreateCart($customer->customer_id);
        $item = $this->cartService->addItem($cart, $request->product_id, $request->quantity);

        // CHANGES HERE: return JSON for AJAX requests (shop add-to-cart without page reload)
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'ok' => true,
                'message' => 'Item added to cart.',
                'cartCount' => $cart->items()->count(),
            ]);
        }

        return redirect()->back()->with('success', 'Item added to cart.');
    }

    public function updateQuantity(Request $request, CartItem $cartItem)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $this->cartService->updateQuantity($cartItem, $request->quantity);

        return redirect()->route('cart')->with('success', 'Cart updated.');
    }

    public function remove(CartItem $cartItem)
    {
        $this->cartService->removeItem($cartItem);

        return redirect()->route('cart')->with('success', 'Item removed from cart.');
    }
}
