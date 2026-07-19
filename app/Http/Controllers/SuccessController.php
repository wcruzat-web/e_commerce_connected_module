<?php
// CRUZAT — success: order confirmation display after successful payment

namespace App\Http\Controllers;

use App\Models\Coupon;
use App\Repositories\OrderRepository;

class SuccessController extends Controller
{
    public function __construct(
        private OrderRepository $orderRepository,
    ) {}

    public function index()
    {
        $orderId = session('order_id');

        if (!$orderId) {
            return redirect()->route('cart')->with('error', 'No order found.');
        }

        $order = $this->orderRepository->findWithItems($orderId);
        $coupon = $order->coupon_code ? Coupon::where('code', $order->coupon_code)->first() : null;

        return view('pages.customer.success.success', compact('order', 'coupon'));
    }
}
