<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\OrderRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OrderController extends Controller
{
    public function __construct(
        private OrderRepository $orderRepository,
    ) {}

    public function index(Request $request): View
    {
        $filters = $request->only(['search', 'status', 'payment_status', 'date_from', 'date_to']);
        $orders = $this->orderRepository->findAllPaginated($filters);

        if ($request->has('partial')) {
            return view('pages.admin.orders.components.orders-table-wrapper', compact('orders', 'filters'));
        }

        return view('pages.admin.orders.orders', compact('orders', 'filters'));
    }

    public function show(int $id): JsonResponse
    {
        $order = $this->orderRepository->findWithItems($id);
        return response()->json($order->load('customer', 'items.product'));
    }

    public function updateTracking(Request $request, int $id): JsonResponse
    {
        $request->validate(['sync_status' => 'required|in:Pending,Synced,Failed']);
        $order = $this->orderRepository->update($id, []);
        if ($order->tracking) {
            $order->tracking->update(['sync_status' => $request->sync_status, 'last_updated' => now()]);
        }
        return response()->json(['success' => true]);
    }
}
