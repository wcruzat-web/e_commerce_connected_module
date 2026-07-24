<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\WebhookLog;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class ExternalSimulatorController extends Controller
{
    public function index(): View
    {
        return view('pages.admin.external-simulator.index');
    }

    public function logs(): JsonResponse
    {
        $logs = WebhookLog::latest()->take(50)->get();
        return response()->json($logs);
    }

    public function lookupOrder(string $order_number): JsonResponse
    {
        $order = Order::with('items', 'customer', 'tracking')
            ->where('order_number', $order_number)
            ->firstOrFail();

        return response()->json($order);
    }

    public function listData(): JsonResponse
    {
        $orders = Order::with('customer')
            ->latest()
            ->get(['order_id', 'order_number', 'customer_id', 'grand_total', 'payment_status', 'status']);

        $paidOrders = Order::with('customer')
            ->where('payment_status', 'paid')
            ->latest()
            ->get(['order_id', 'order_number', 'customer_id', 'grand_total', 'payment_status', 'status']);

        return response()->json([
            'orders' => $orders,
            'paidOrders' => $paidOrders,
        ]);
    }
}
