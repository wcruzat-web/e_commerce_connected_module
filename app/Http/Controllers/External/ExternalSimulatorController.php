<?php

namespace App\Http\Controllers\External;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\WebhookLog;
use Illuminate\Http\JsonResponse;

class ExternalSimulatorController extends Controller
{
    public function lookupOrder(string $order_number): JsonResponse
    {
        $order = Order::with('items', 'customer', 'tracking')
            ->where('order_number', $order_number)
            ->firstOrFail();

        return response()->json($order);
    }

    public function logs(): JsonResponse
    {
        $logs = WebhookLog::latest()->take(50)->get();
        return response()->json($logs);
    }
}
