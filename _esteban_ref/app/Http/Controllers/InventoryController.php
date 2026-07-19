<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Warehouse;
use App\Models\WarehouseStock;
use App\Models\RevenueOverview;
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

        $lowStockProducts = Product::whereBetween('stock', [1, 5])
            ->orderBy('stock', 'asc')
            ->get()
            ->map(function ($p) {
                $maxMap = ['GPU' => 50, 'CPU' => 100, 'Motherboard' => 30, 'Memory' => 80, 'Cooling' => 40];
                return [
                    'id' => $p->product_id,
                    'name' => strlen($p->product_name) > 24 ? substr($p->product_name, 0, 24) . '...' : $p->product_name,
                    'sku' => $p->sku,
                    'left' => $p->stock,
                    'max' => $maxMap[$p->category] ?? 50,
                ];
            });

        return response()->json([
            'totalProduct' => $totalProduct,
            'availableStock' => $availableStock,
            'lowStockProduct' => $lowStockProduct,
            'outOfStock' => $outOfStock,
            'categoryStock' => $categoryStock,
            'lowStockAlerts' => $lowStockProducts,
        ]);
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

    public function revenue(): JsonResponse
    {
        $data = RevenueOverview::orderBy('revenue_id', 'asc')->get()
            ->map(function ($r) {
                return [
                    'month' => $r->month_label,
                    'value' => (float) $r->revenue_amount,
                ];
            });

        return response()->json($data);
    }
}
