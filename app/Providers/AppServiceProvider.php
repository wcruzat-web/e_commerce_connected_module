<?php
// [CRUZAT] Base provider (was empty)
// [AGNER]  Added View composers for cart/wishlist badge counts

namespace App\Providers;

use App\Models\Wishlist;
use Illuminate\Support\Facades\Auth;
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
        // [AGNER] View composer for store + customer layouts
        View::composer(['layouts.store', 'layouts.customer'], function ($view) {
            if (Auth::check()) {
                $cart = session()->get('cart', []);
                $cartCount = array_sum(array_column($cart, 'qty'));
                $wishlistCount = Wishlist::where('customer_id', Auth::id())->count();
            } else {
                $cartCount = 0;
                $wishlistCount = 0;
            }
            $view->with(compact('cartCount', 'wishlistCount'));
        });
    }
}
