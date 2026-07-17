<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OrderController extends Controller
{
    /**
     * "My Orders" — active shipments only (Processing / Shipped).
     * Delivered orders live in Order History, not here.
     * Empty by default: a customer must have something in transit.
     */
    public function index(Request $request): View
    {
        /** @var Customer $customer */
        $customer = $request->user();

        $orders = $customer->orders()
            ->active()
            ->with('items')
            ->latest('order_id')
            ->get();

        return view('customer.orders', compact('orders'));
    }

    /**
     * "Order History" — delivered orders only.
     */
    public function history(Request $request): View
    {
        /** @var Customer $customer */
        $customer = $request->user();

        $orders = $customer->orders()
            ->delivered()
            ->with('items')
            ->latest('order_id')
            ->get();

        return view('customer.history', compact('orders'));
    }

    /**
     * Place an order from the current cart. Creates one order in the
     * "Shipped" state (i.e. it is now "being shipped") and clears
     * the cart. This is what populates "My Orders".
     */
    public function checkout(Request $request): RedirectResponse
    {
        $cart = session('cart', []);

        if (empty($cart)) {
            return redirect()->route('cart')
                ->with('error', 'Your cart is empty.');
        }

        /** @var Customer $customer */
        $customer = $request->user();

        $total = 0;
        foreach ($cart as $item) {
            $total += (float) ($item['price'] ?? 0) * (int) ($item['qty'] ?? 1);
        }

        $order = $customer->orders()->create([
            'status' => 'Shipped',
            'total' => $total,
        ]);

        foreach ($cart as $item) {
            $order->items()->create([
                'customer_id' => $customer->customer_id,
                'product_id' => $item['id'] ?? null,
                'product_name' => $item['name'] ?? 'Item',
                'product_image' => $item['image'] ?? null,
                'unit_price' => (float) ($item['price'] ?? 0),
                'quantity' => (int) ($item['qty'] ?? 1),
            ]);
        }

        session()->forget('cart');

        return redirect()->route('orders')
            ->with('success', 'Order placed — your items are on the way!');
    }

    /**
     * Mark an order as received → it moves from "My Orders" to "Order History".
     */
    public function receive(Request $request, Order $order): RedirectResponse
    {
        /** @var Customer $customer */
        $customer = $request->user();
        abort_unless($order->customer_id === $customer->customer_id, 403);

        $order->update(['status' => 'Delivered']);

        return redirect()->route('orders')
            ->with('success', 'Order marked as received.');
    }
}
