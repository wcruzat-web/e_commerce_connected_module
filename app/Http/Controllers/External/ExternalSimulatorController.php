<?php

namespace App\Http\Controllers\External;

use App\Http\Controllers\Controller;
use App\Models\Order;
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
}
