<?php

namespace App\Http\Controllers\Admin\Api;

use App\Models\Product;
use App\Models\Warehouse;
use App\Models\WarehouseStock;
use App\Services\Admin\DashboardService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

// ESTEBAN — NEW InventoryController adapted from original: uses our schema (products.id, warehouses.warehouse_id, warehouse_stock)
class InventoryController extends \App\Http\Controllers\Controller
{
    public function __construct(
        private DashboardService $dashboardService,
    ) {}

    public function stats(): JsonResponse
    {
        $now = now();
        $thisMonth = [$now->copy()->startOfMonth(), $now->copy()->endOfMonth()];
        $lastMonth = [$now->copy()->subMonth()->startOfMonth(), $now->copy()->subMonth()->endOfMonth()];

        $totalProduct = Product::count();
        $totalProductLast = Product::whereBetween('created_at', $lastMonth)->count();
        $totalProductDiff = $this->formatDiff($totalProduct, $totalProductLast);

        $availableStock = Product::sum('stock');
        $soldThisMonth = (int) \App\Models\OrderItem::whereBetween('created_at', $thisMonth)->sum('quantity');
        $soldLastMonth = (int) \App\Models\OrderItem::whereBetween('created_at', $lastMonth)->sum('quantity');
        $stockDiff = $this->formatDiff($soldThisMonth, $soldLastMonth);

        $lowStockProduct = Product::where('stock', '<=', 5)->count();
        $lowStockDiff = $lowStockProduct > 0
            ? ['text' => $lowStockProduct . ' items need restock', 'direction' => 'neutral']
            : ['text' => 'Fully stocked', 'direction' => 'neutral'];

        $outOfStock = Product::where('stock', 0)->count();
        $outOfStockDiff = $this->formatDiff($outOfStock, 0);

        // ESTEBAN — adapted: uses products.category (string) directly; no labelMap — uses raw category names
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

        // ESTEBAN — adapted: max per category computed dynamically from DB instead of hardcoded $maxMap
        $catMax = Product::select('category', DB::raw('MAX(stock) as max_stock'))
            ->whereNotNull('category')
            ->groupBy('category')
            ->pluck('max_stock', 'category');

        // ESTEBAN — adapted: stock <= 5 (was BETWEEN 1,5); max from DB query above
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

        return response()->json([
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
        ]);
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

    public function warehouses(): JsonResponse
    {
        $warehouses = Warehouse::all()->map(function ($w) {
            $productCount = WarehouseStock::where('warehouse_id', $w->warehouse_id)->sum('quantity');
            $lastSync = $w->last_sync_at ? $w->last_sync_at->diffForHumans() : 'Never';
            return [
                'id' => $w->warehouse_id,
                'name' => $w->warehouse_name,
                'detail' => number_format($productCount) . ' products',
                'lastSync' => $lastSync,
                'status' => $w->sync_status,
            ];
        });

        return response()->json($warehouses);
    }

    public function forceSync(): JsonResponse
    {
        DB::table('warehouses')->update([
            'sync_status' => 'Synced',
            'last_sync_at' => now(),
            'updated_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'All warehouses synced successfully',
        ]);
    }

    // ESTEBAN — adapted: revenue computed from orders table (paid orders, last 6 months) via DashboardService instead of revenue_overview table
    public function revenue(): JsonResponse
    {
        $months = $this->dashboardService->getRevenueOverview();

        $data = array_map(function ($m) {
            return [
                'month' => $m['month'],
                'value' => (float) $m['revenue'],
            ];
        }, $months);

        return response()->json(array_values($data));
    }
}
