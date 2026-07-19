<?php
// CRUZAT — admin dashboard: stats, revenue chart, recent orders, low stocks

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\Admin\DashboardService;

class DashboardController extends Controller
{
    public function __construct(
        private DashboardService $dashboardService,
    ) {}

    public function index()
    {
        $stats = $this->dashboardService->getStats();
        $recentOrders = $this->dashboardService->getRecentOrders();
        $revenueMonths = $this->dashboardService->getRevenueOverview();
        $revenueByCategory = $this->dashboardService->getRevenueByCategory();
        $lowStockProducts = $this->dashboardService->getLowStockProducts();

        return view('pages.admin.dashboard.dashboard', compact(
            'stats', 'recentOrders', 'revenueMonths', 'revenueByCategory', 'lowStockProducts'
        ));
    }

    public function print()
    {
        $stats = $this->dashboardService->getStats();
        $recentOrders = $this->dashboardService->getRecentOrders();
        $revenueMonths = $this->dashboardService->getRevenueOverview();
        $revenueByCategory = $this->dashboardService->getRevenueByCategory();
        $lowStockProducts = $this->dashboardService->getLowStockProducts();

        return view('pages.admin.dashboard.print', compact(
            'stats', 'recentOrders', 'revenueMonths', 'revenueByCategory', 'lowStockProducts'
        ));
    }
}
