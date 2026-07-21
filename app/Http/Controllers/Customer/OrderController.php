<?php
// AGNER — orders: unified view with status tabs, customer_received (ERPV0.2.3, ERPV0.2.4)

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Order;
use App\Models\ProductReview;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OrderController extends Controller
{
    public function index(Request $request): View
    {
        /** @var Customer $customer */
        $customer = $request->user();

        $status = $request->input('status', 'all');

        $orders = $customer->orders()
            ->with('items')
            ->when($status === 'processing', function ($q) {
                $q->whereIn('status', ['pending','processing','shipped','in_transit','out_for_delivery']);
            })
            ->when($status === 'delivered', function ($q) {
                $q->where('status', 'delivered');
            })
            ->orderByRaw("FIELD(status, 'pending','processing','shipped','in_transit','out_for_delivery','delivered','cancelled')")
            ->latest('order_id')
            ->paginate(7);

        return view('pages.customer.orders.orders', compact('orders', 'status'));
    }

    public function history(Request $request): View
    {
        /** @var Customer $customer */
        $customer = $request->user();

        $search = $request->input('search');

        $orders = $customer->orders()
            ->delivered()
            ->with('items')
            ->when($search, function ($q) use ($search) {
                $q->where(function ($q2) use ($search) {
                    $q2->where('order_number', 'like', "%{$search}%")
                       ->orWhere('order_id', $search)
                       ->orWhereHas('items', function ($qi) use ($search) {
                           $qi->where('product_name', 'like', "%{$search}%");
                       });
                });
            })
            ->latest('order_id')
            ->paginate(7);

        $reviewedProductIds = ProductReview::where('user_id', $customer->customer_id)->pluck('product_id')->toArray();

        return view('pages.customer.history.history', compact('orders', 'search', 'reviewedProductIds'));
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
