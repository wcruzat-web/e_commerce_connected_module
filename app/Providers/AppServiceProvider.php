<?php

namespace App\Providers;

use App\Models\Category;
use App\Models\PromoBanner;
use App\Services\Admin\DashboardService;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        if (app()->runningInConsole()) {
            return;
        }
        if (app()->bound('router') && \Illuminate\Support\Facades\Schema::hasTable('categories')) {
            View::share('headerCategories', \App\Models\Category::all());
        }

        if (app()->bound('router') && \Illuminate\Support\Facades\Schema::hasTable('promo_banners')) {
            View::share('promoBanner', PromoBanner::where('is_active', true)->orderByDesc('banner_id')->first());
        }

        // Share admin notifications with topbar and notifications panel
        View::composer(
            ['pages.admin.dashboard.components.topbar', 'pages.admin.dashboard.components.notifications-panel'],
            function ($view) {
                try {
                    $service = app(DashboardService::class);
                    $view->with('notifications', $service->getNotifications());
                } catch (\Exception $e) {
                    $view->with('notifications', []);
                }
            }
        );
    }
}
