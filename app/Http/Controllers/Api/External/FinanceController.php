<?php

namespace App\Http\Controllers\Api\External;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\External\WebhookService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class FinanceController extends Controller
{
    public function __construct(
        private WebhookService $webhookService,
    ) {}

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

        $this->webhookService->paymentConfirmed($order);

        return response()->json([
            'success' => true,
            'message' => 'Payment confirmed by Finance module',
            'transaction_id' => $validated['finance_transaction_id'],
        ]);
    }

    public function index(): JsonResponse
    {
        $orders = Order::where('payment_status', 'pending')
            ->with('customer')
            ->get(['order_id', 'order_number', 'grand_total', 'created_at', 'shipping_name']);

        return response()->json([
            'success' => true,
            'orders' => $orders->map(fn($o) => [
                'order_number' => $o->order_number,
                'customer' => $o->shipping_name,
                'total' => $o->grand_total,
                'created_at' => $o->created_at,
            ]),
        ]);
    }
}
