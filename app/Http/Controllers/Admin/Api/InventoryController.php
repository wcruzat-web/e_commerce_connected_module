<?php

namespace App\Http\Controllers\Admin\Api;

use App\Models\Warehouse;
use App\Models\WarehouseStock;
use App\Services\Admin\DashboardService;
use App\Services\Admin\InventoryService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

// ESTEBAN — NEW InventoryController adapted from original: uses our schema (products.id, warehouses.warehouse_id, warehouse_stock)
class InventoryController extends \App\Http\Controllers\Controller
{
    public function __construct(
        private DashboardService $dashboardService,
        private InventoryService $inventoryService,
    ) {}

    public function stats(): JsonResponse
    {
        return response()->json($this->inventoryService->getStats());
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
