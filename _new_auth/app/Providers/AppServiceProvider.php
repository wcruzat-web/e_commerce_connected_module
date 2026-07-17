<?php

namespace App\Providers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Share live cart (session) and wishlist (DB) counts with the
        // storefront + portal layouts so the nav badges stay in sync.
        View::composer(['layouts.store', 'layouts.customer'], function ($view) {
            $cartCount = count(session('cart', []));
            $wishlistCount = Auth::check() ? Auth::user()->wishlistItems()->count() : 0;

            $view->with('cartCount', $cartCount)->with('wishlistCount', $wishlistCount);
        });
    }
}
