<?php

namespace App\Http\Controllers\Api\External;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class FinanceController extends Controller
{

    public function store(string $orderNumber, Request $request): JsonResponse
    {
        $validated = $request->validate([
            'finance_transaction_id' => 'required|string',
            'paid_at' => 'nullable|date',
        ]);

        $order = Order::where('order_number', $orderNumber)->firstOrFail();

        $order->update([
            'payment_status' => 'paid',
            'status' => 'processing',
            'finance_transaction_id' => $validated['finance_transaction_id'],
            'paid_at' => $validated['paid_at'] ?? now(),
        ]);

        if ($order->tracking) {
            $order->tracking->update([
                'order_status' => 'Processing',
                'last_updated' => now(),
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Payment confirmed by Finance module',
            'transaction_id' => $validated['finance_transaction_id'],
        ]);
    }

    public function index(): JsonResponse
    {
        $orders = Order::with('customer')
            ->latest()
            ->get([
                'order_id', 'order_number', 'customer_id', 'grand_total',
                'payment_status', 'status', 'created_at', 'shipping_name',
            ]);

        return response()->json([
            'success' => true,
            'orders' => $orders,
        ]);
    }

    public function show(string $orderNumber): JsonResponse
    {
        $order = Order::with('items', 'customer', 'tracking')
            ->where('order_number', $orderNumber)
            ->firstOrFail();

        return response()->json($order);
    }
}
