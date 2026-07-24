<?php

namespace App\Http\Controllers\Api\External;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SalesController extends Controller
{
    public function getOrder(string $orderNumber): JsonResponse
    {
        $order = Order::with('items', 'customer', 'tracking')
            ->where('order_number', $orderNumber)
            ->firstOrFail();

        return response()->json($order);
    }

    public function listOrders(): JsonResponse
    {
        $orders = Order::where('payment_status', 'paid')
            ->with('customer')
            ->get(['order_id', 'order_number', 'grand_total', 'status', 'created_at', 'shipping_name']);

        return response()->json([
            'success' => true,
            'orders' => $orders->map(fn($o) => [
                'order_number' => $o->order_number,
                'customer' => $o->shipping_name,
                'total' => $o->grand_total,
                'status' => $o->status,
                'created_at' => $o->created_at,
            ]),
        ]);
    }

    public function updateStatus(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'order_number' => 'required|string|exists:orders,order_number',
            'status' => 'required|in:processing,shipped,in_transit,out_for_delivery,delivered,cancelled',
        ]);

        $order = Order::where('order_number', $validated['order_number'])->firstOrFail();

        if ($order->payment_status !== 'paid') {
            return response()->json([
                'success' => false,
                'message' => 'Order must be paid before Sales can update status',
            ], 422);
        }

        if ($order->customer_received) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot update status — customer has already received the order',
            ], 422);
        }

        $order->update(['status' => $validated['status']]);

        if ($order->tracking) {
            $trackingStatuses = [
                'processing' => 'Processing',
                'shipped' => 'Shipped',
                'in_transit' => 'In Transit',
                'out_for_delivery' => 'Out for Delivery',
                'delivered' => 'Delivered',
                'cancelled' => 'Cancelled',
            ];
            $order->tracking->update([
                'order_status' => $trackingStatuses[$validated['status']],
                'last_updated' => now(),
            ]);
        }

        $order->load('items', 'customer', 'tracking');

        return response()->json([
            'success' => true,
            'message' => 'Order status updated by Sales module',
            'order' => $order,
        ]);
    }
}
