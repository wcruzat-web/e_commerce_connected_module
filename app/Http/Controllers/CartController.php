<?php
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
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $customer = Auth::user();
        $cart = $this->cartService->getOrCreateCart($customer->customer_id);
        $item = $this->cartService->addItem($cart, $request->product_id, $request->quantity);

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

    public function applyVoucher(Request $request)
    {
        $request->validate([
            'code' => 'required|string|max:50',
        ]);

        $customer = Auth::user();
        $cart = $this->cartService->getOrCreateCart($customer->customer_id);
        $result = $this->cartService->applyCoupon($cart, $request->code);

        if (!$result['success']) {
            return response()->json($result, 422);
        }

        $summary = $this->cartService->getSummary($cart);

        return response()->json([
            'success' => true,
            'message' => $result['message'],
            'summary' => $summary,
        ]);
    }

    public function removeVoucher(Request $request)
    {
        $customer = Auth::user();
        $cart = $this->cartService->getOrCreateCart($customer->customer_id);
        $this->cartService->removeCoupon($cart);

        $summary = $this->cartService->getSummary($cart);

        return response()->json([
            'success' => true,
            'message' => 'Voucher removed.',
            'summary' => $summary,
        ]);
    }
}