<?php

namespace App\Providers;

use App\Models\Category;
use App\Models\PromoBanner;
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
    }
}
