<?php

namespace App\Services\Admin;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;

class DashboardService
{
    public function getStats(): array
    {
        $totalRevenue = Order::where('payment_status', 'paid')->sum('grand_total');

        $ordersThisMonth = Order::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        $lowStockCount = Product::where('stock', '<=', 5)->count();

        $totalOrders = Order::count();

        return [
            'total_revenue' => $totalRevenue,
            'orders_this_month' => $ordersThisMonth,
            'total_orders' => $totalOrders,
            'low_stock_count' => $lowStockCount,
        ];
    }

    public function getRecentOrders(int $limit = 2): array
    {
        $orders = Order::with('items.product')
            ->latest()
            ->take($limit)
            ->get();

        return $orders->map(function ($order) {
            $itemNames = $order->items->map(function ($item) {
                return $item->product->name ?? 'Product #' . $item->product_id;
            })->implode(', ');

            $customerName = $order->shipping_name;

            return [
                'name' => $customerName,
                'spec' => $itemNames,
                'price' => $order->grand_total,
                'status' => $order->status,
                'order_number' => $order->order_number,
            ];
        })->toArray();
    }

    public function getRevenueOverview(): array
    {
        $months = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $revenue = Order::where('payment_status', 'paid')
                ->whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->sum('grand_total');
            $months[] = [
                'month' => $date->format('F'),
                'revenue' => $revenue,
            ];
        }
        return $months;
    }

    public function getRevenueByCategory(): array
    {
        $results = OrderItem::selectRaw('categories.name as category, SUM(order_items.subtotal) as total')
            ->join('product_table', 'order_items.product_id', '=', 'product_table.product_id') // [ESTEBAN] table/column rename
            ->join('categories', 'product_table.category_id', '=', 'categories.id')
            ->groupBy('categories.name')
            ->orderByDesc('total')
            ->get();

        $total = $results->sum('total');

        return $results->map(function ($item) use ($total) {
            return [
                'label' => $item->category,
                'amount' => $item->total,
                'pct' => $total > 0 ? round(($item->total / $total) * 100) : 0,
            ];
        })->toArray();
    }

    public function getLowStockProducts(): array
    {
        return Product::where('stock', '<=', 5)
            ->orderBy('stock')
            ->take(5)
            ->get()
            ->map(function ($product) {
                return [
                    'name' => $product->product_name,
                    'sku' => $product->sku,
                    'left' => $product->stock,
                    'pct' => 0,
                    'max' => 0,
                ];
            })->toArray();
    }
}
