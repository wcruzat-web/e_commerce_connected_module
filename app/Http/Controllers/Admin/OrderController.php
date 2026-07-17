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
        return response()->json($order->load('customer', 'items.product', 'tracking'));
    }

    public function updatePayment(Request $request, int $id): JsonResponse
    {
        $request->validate(['payment_status' => 'required|in:pending,paid']);
        $data = ['payment_status' => $request->payment_status];
        if ($request->payment_status === 'paid') {
            $data['status'] = 'processing';
        }
        $order = $this->orderRepository->update($id, $data);
        if ($order->tracking && $request->payment_status === 'paid') {
            $order->tracking->update([
                'order_status' => 'Processing',
                'last_updated' => now(),
            ]);
        }
        return response()->json(['success' => true]);
    }

    public function updateStatus(Request $request, int $id): JsonResponse
    {
        $request->validate(['status' => 'required|in:pending,processing,shipped,in_transit,out_for_delivery,delivered,cancelled']);
        $order = $this->orderRepository->update($id, ['status' => $request->status]);
        if ($order->tracking) {
            $trackingStatuses = [
                'pending' => 'Order Placed',
                'processing' => 'Processing',
                'shipped' => 'Shipped',
                'in_transit' => 'In Transit',
                'out_for_delivery' => 'Out for Delivery',
                'delivered' => 'Delivered',
                'cancelled' => 'Cancelled',
            ];
            $order->tracking->update([
                'order_status' => $trackingStatuses[$request->status] ?? ucfirst(str_replace('_', ' ', $request->status)),
                'last_updated' => now(),
            ]);
        }
        return response()->json(['success' => true]);
    }

    public function updateTracking(Request $request, int $id): JsonResponse
    {
        $request->validate(['sync_status' => 'required|in:Pending,Synced,Failed']);
        $order = $this->orderRepository->find($id);
        if ($order && $order->tracking) {
            $order->tracking->update(['sync_status' => $request->sync_status, 'last_updated' => now()]);
        }
        return response()->json(['success' => true]);
    }
}
