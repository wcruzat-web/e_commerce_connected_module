<?php

namespace App\Services\Admin;

use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class InventoryService
{
    public function getStats(): array
    {
        $now = now();
        $thisMonth = [$now->copy()->startOfMonth(), $now->copy()->endOfMonth()];
        $lastMonth = [$now->copy()->subMonth()->startOfMonth(), $now->copy()->subMonth()->endOfMonth()];

        $totalProduct = Product::count();
        $totalProductLast = Product::whereBetween('created_at', $lastMonth)->count();
        $totalProductDiff = $this->formatDiff($totalProduct, $totalProductLast);

        $availableStock = Product::sum('stock');
        $soldThisMonth = (int) OrderItem::whereBetween('created_at', $thisMonth)->sum('quantity');
        $soldLastMonth = (int) OrderItem::whereBetween('created_at', $lastMonth)->sum('quantity');
        $stockDiff = $this->formatDiff($soldThisMonth, $soldLastMonth);

        $lowStockProduct = Product::where('stock', '<=', 5)->count();
        $lowStockDiff = $lowStockProduct > 0
            ? ['text' => $lowStockProduct . ' items need restock', 'direction' => 'neutral']
            : ['text' => 'Fully stocked', 'direction' => 'neutral'];

        $outOfStock = Product::where('stock', 0)->count();
        $outOfStockDiff = $this->formatDiff($outOfStock, 0);

        $categoryStock = Product::select('category', DB::raw('SUM(stock) as total'))
            ->whereNotNull('category')
            ->groupBy('category')
            ->get()
            ->map(function ($item) {
                return [
                    'label' => $item->category,
                    'value' => (int) $item->total,
                ];
            });

        $catMax = Product::select('category', DB::raw('MAX(stock) as max_stock'))
            ->whereNotNull('category')
            ->groupBy('category')
            ->pluck('max_stock', 'category');

        $lowStockProducts = Product::where('stock', '<=', 5)
            ->orderBy('stock', 'asc')
            ->get()
            ->map(function ($p) use ($catMax) {
                return [
                    'id' => $p->id,
                    'name' => strlen($p->name) > 24 ? substr($p->name, 0, 24) . '...' : $p->name,
                    'sku' => $p->sku,
                    'left' => $p->stock,
                    'max' => $catMax[$p->category] ?? 50,
                ];
            });

        return [
            'totalProduct' => $totalProduct,
            'totalProductDiff' => $totalProductDiff,
            'availableStock' => $availableStock,
            'stockDiff' => $stockDiff,
            'lowStockProduct' => $lowStockProduct,
            'lowStockDiff' => $lowStockDiff,
            'outOfStock' => $outOfStock,
            'outOfStockDiff' => $outOfStockDiff,
            'categoryStock' => $categoryStock,
            'lowStockAlerts' => $lowStockProducts,
        ];
    }

    private function formatDiff(int $current, int $previous, string $suffix = ' vs. last month'): array
    {
        if ($current === 0 && $previous === 0) {
            return ['text' => 'No change', 'direction' => 'neutral'];
        }
        $diff = $current - $previous;
        if ($diff > 0) {
            return ['text' => '+' . $diff . $suffix, 'direction' => 'up'];
        }
        if ($diff < 0) {
            return ['text' => $diff . $suffix, 'direction' => 'down'];
        }
        return ['text' => 'No change', 'direction' => 'neutral'];
    }
}
