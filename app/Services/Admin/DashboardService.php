<?php

namespace App\Services\Admin;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Support\Facades\Cache;

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
                return $item->product?->name ?? 'Product #' . $item->product_id;
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
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->join('categories', 'products.category_id', '=', 'categories.id')
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
                    'name' => $product->name,
                    'sku' => $product->sku,
                    'left' => $product->stock,
                    'pct' => 0,
                    'max' => 0,
                ];
            })->toArray();
    }

    public function getNotifications(): array
    {
        $notifications = [];
        $now = now();

        // Low stock alerts
        $lowStock = Product::where('stock', '<=', 5)->where('stock', '>', 0)->orderBy('stock')->take(5)->get();
        foreach ($lowStock as $p) {
            $notifications[] = [
                'icon' => 'alert-triangle',
                'icon_color' => 'text-red-500',
                'title' => "Low stock: {$p->name} ({$p->stock} units left)",
                'time' => $p->updated_at->diffForHumans(),
                'unread' => true,
                'created_at' => $p->updated_at,
            ];
        }

        // Out of stock alerts
        $outOfStock = Product::where('stock', 0)->take(5)->get();
        foreach ($outOfStock as $p) {
            $notifications[] = [
                'icon' => 'alert-triangle',
                'icon_color' => 'text-red-500',
                'title' => "Out of stock: {$p->name}",
                'time' => $p->updated_at->diffForHumans(),
                'unread' => true,
                'created_at' => $p->updated_at,
            ];
        }

        // New pending orders
        $pendingOrders = Order::whereIn('status', ['pending', 'processing'])->latest()->take(5)->get();
        foreach ($pendingOrders as $o) {
            $notifications[] = [
                'icon' => 'shopping-cart',
                'icon_color' => 'text-amber-500',
                'title' => "New order: {$o->shipping_name} — ₱" . number_format($o->grand_total, 2) . " (" . ucfirst($o->status) . ")",
                'time' => $o->created_at->diffForHumans(),
                'unread' => $o->created_at->gt($now->subDay()),
                'created_at' => $o->created_at,
            ];
        }

        // Recently shipped orders
        $shippedOrders = Order::where('status', 'shipped')->latest()->take(5)->get();
        foreach ($shippedOrders as $o) {
            $notifications[] = [
                'icon' => 'package',
                'icon_color' => 'text-blue-500',
                'title' => "Order shipped: {$o->shipping_name} — Shipped",
                'time' => $o->updated_at->diffForHumans(),
                'unread' => $o->updated_at->gt($now->subDay()),
                'created_at' => $o->updated_at,
            ];
        }

        // Recently delivered orders
        $deliveredOrders = Order::where('status', 'delivered')->latest()->take(5)->get();
        foreach ($deliveredOrders as $o) {
            $notifications[] = [
                'icon' => 'check-circle',
                'icon_color' => 'text-green-500',
                'title' => "Order delivered: {$o->shipping_name} — Delivered",
                'time' => $o->updated_at->diffForHumans(),
                'unread' => $o->updated_at->gt($now->subDay()),
                'created_at' => $o->updated_at,
            ];
        }

        // Sort by created_at descending, take newest 20
        usort($notifications, fn ($a, $b) => $b['created_at']->timestamp <=> $a['created_at']->timestamp);

        return array_slice($notifications, 0, 20);
    }
}
