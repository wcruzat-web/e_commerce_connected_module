<?php
// AGNER — orders: unified view with status tabs, customer_received (ERPV0.2.3, ERPV0.2.4)

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OrderController extends Controller
{
    public function index(Request $request): View
    {
        /** @var Customer $customer */
        $customer = $request->user();

        $orders = $customer->orders()
            ->with('items')
            ->orderByRaw("FIELD(status, 'pending','processing','shipped','in_transit','out_for_delivery','delivered','cancelled')")
            ->latest('order_id')
            ->get();

        return view('pages.customer.orders.orders', compact('orders'));
    }

    public function history(Request $request): View
    {
        /** @var Customer $customer */
        $customer = $request->user();

        $orders = $customer->orders()
            ->delivered()
            ->with('items')
            ->latest('order_id')
            ->get();

        return view('pages.customer.history.history', compact('orders'));
    }

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
            'grand_total' => $total,
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

    public function receive(Request $request, Order $order): RedirectResponse
    {
        /** @var Customer $customer */
        $customer = $request->user();
        abort_unless($order->customer_id === $customer->customer_id, 403);

        $order->update(['customer_received' => true]);

        return redirect()->route('orders')
            ->with('success', 'Order marked as received.');
    }
}
