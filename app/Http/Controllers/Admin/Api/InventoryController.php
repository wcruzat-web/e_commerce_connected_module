<?php

// [ESTEBAN] — REST API, logic unchanged from original (revenue method modified to query orders table)
namespace App\Http\Controllers\Admin\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\Warehouse;
use App\Models\WarehouseStock;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class InventoryController extends Controller
{
    public function stats(): JsonResponse
    {
        $totalProduct = Product::count();
        $availableStock = Product::sum('stock');
        $lowStockProduct = Product::whereBetween('stock', [1, 5])->count();
        $outOfStock = Product::where('stock', 0)->count();

        $categoryStock = Product::select('category', DB::raw('SUM(stock) as total'))
            ->groupBy('category')
            ->get()
            ->map(function ($item) {
                $labelMap = [
                    'GPU' => 'GPU',
                    'CPU' => 'CPU',
                    'Motherboard' => 'MB',
                    'Memory' => 'RAM',
                    'Cooling' => 'Cooling',
                ];
                return [
                    'label' => $labelMap[$item->category] ?? $item->category,
                    'value' => (int) $item->total,
                ];
            });

        $lowStockAlerts = Product::whereBetween('stock', [1, 5])
            ->orderBy('stock')
            ->get()
            ->map(function ($p) {
                return [
                    'id' => $p->product_id,
                    'name' => strlen($p->product_name) > 24 ? substr($p->product_name, 0, 24) . '...' : $p->product_name,
                    'sku' => $p->sku,
                    'left' => $p->stock,
                    'max' => 50,
                ];
            });

        return response()->json([
            'totalProduct' => $totalProduct,
            'availableStock' => $availableStock,
            'lowStockProduct' => $lowStockProduct,
            'outOfStock' => $outOfStock,
            'categoryStock' => $categoryStock,
            'lowStockAlerts' => $lowStockAlerts,
        ]);
    }

    public function warehouses(): JsonResponse
    {
        $warehouses = Warehouse::all()->map(function ($w) {
            $productCount = WarehouseStock::where('warehouse_id', $w->warehouse_id)->sum('quantity');
            return [
                'id' => $w->warehouse_id,
                'name' => $w->warehouse_name,
                'detail' => number_format($productCount) . ' products',
                'lastSync' => $w->last_sync_at ? $w->last_sync_at->diffForHumans() : 'Never',
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

    // [NEW] — custom method, not in Esteban's original. Returns full product stock listing for SPA.
    public function products(): JsonResponse
    {
        $products = Product::orderBy('product_name')
            ->get()
            ->map(function ($p) {
                $stock = (int) $p->stock;
                return [
                    'product_id' => $p->product_id,
                    'product_name' => $p->product_name,
                    'category' => $p->category ?? 'Uncategorized',
                    'stock' => $stock,
                    'status' => $stock > 5 ? 'available' : ($stock > 0 ? 'low' : 'out'),
                ];
            });

        return response()->json($products);
    }

    public function revenue(): JsonResponse
    {
        $data = Order::whereNotNull('paid_at')
            ->selectRaw('DATE_FORMAT(paid_at, "%b") as month_label')
            ->selectRaw('YEAR(paid_at) as overview_year')
            ->selectRaw('SUM(grand_total) as revenue_amount')
            ->groupBy('overview_year', 'month_label')
            ->orderBy('overview_year')
            ->orderByRaw('MIN(paid_at)')
            ->get()
            ->map(fn($r) => [
                'month' => $r->month_label,
                'value' => (float) $r->revenue_amount,
            ]);

        return response()->json($data);
    }
}
