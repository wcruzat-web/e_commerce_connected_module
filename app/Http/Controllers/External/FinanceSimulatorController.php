<?php

namespace App\Http\Controllers\External;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class FinanceSimulatorController extends Controller
{
    public function index(): View
    {
        return view('pages.external.finance.index');
    }

    public function listPendingOrders(): JsonResponse
    {
        $orders = Order::with('customer')
            ->latest()
            ->get(['order_id', 'order_number', 'customer_id', 'grand_total', 'payment_status', 'status']);

        return response()->json([
            'success' => true,
            'orders' => $orders,
        ]);
    }
}
