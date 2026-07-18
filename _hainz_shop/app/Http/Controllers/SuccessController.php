<?php

namespace App\Http\Controllers;

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

        return view('pages.customer.success.success', compact('order'));
    }
}
