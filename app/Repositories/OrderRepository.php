<?php

namespace App\Repositories;

use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Pagination\LengthAwarePaginator;

class OrderRepository
{
    public function create(array $data): Order
    {
        return Order::create($data);
    }

    public function addItem(Order $order, array $data): OrderItem
    {
        return $order->items()->create($data);
    }

    public function loadItems(Order $order): Order
    {
        return $order->load('items.product');
    }

    public function findWithItems(int $orderId): Order
    {
        return Order::with('items', 'tracking')->findOrFail($orderId);
    }

    public function findByOrderNumberAndCustomer(string $orderNumber, int $customerId): ?Order
    {
        return Order::with('items', 'tracking')
            ->where('order_number', $orderNumber)
            ->where('customer_id', $customerId)
            ->first();
    }

    public function findByTrackingNumberAndCustomer(string $trackingNumber, int $customerId): ?Order
    {
        return Order::with('items', 'tracking')
            ->whereHas('tracking', function ($q) use ($trackingNumber) {
                $q->where('tracking_number', $trackingNumber);
            })
            ->where('customer_id', $customerId)
            ->first();
    }

    public function find(int $orderId): ?Order
    {
        return Order::with('items', 'tracking')->find($orderId);
    }

    public function findAllPaginated(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        $query = Order::with('customer', 'items.product', 'tracking');

        if (!empty($filters['search'])) {
            $s = $filters['search'];
            $query->where(function ($q) use ($s) {
                $q->where('order_number', 'like', "%{$s}%")
                  ->orWhere('shipping_name', 'like', "%{$s}%")
                  ->orWhere('shipping_email', 'like', "%{$s}%")
                  ->orWhereHas('customer', function ($cq) use ($s) {
                      $cq->where('first_name', 'like', "%{$s}%")
                         ->orWhere('last_name', 'like', "%{$s}%");
                  });
            });
        }

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (!empty($filters['payment_status'])) {
            $query->where('payment_status', $filters['payment_status']);
        }

        if (!empty($filters['date_from'])) {
            $query->whereDate('created_at', '>=', $filters['date_from']);
        }

        if (!empty($filters['date_to'])) {
            $query->whereDate('created_at', '<=', $filters['date_to']);
        }

        $query->orderBy('created_at', 'desc');

        return $query->paginate($perPage);
    }

    public function update(int $orderId, array $data): Order
    {
        $order = Order::findOrFail($orderId);
        $order->update($data);
        return $order->fresh('items', 'tracking');
    }
}
