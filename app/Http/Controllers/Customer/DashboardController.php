<?php
// AGNER — customer dashboard: real cart/order counts (ERPV0.2.13)

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        /** @var Customer $customer */
        $customer = $request->user();

        $stats = [
            'wishlist' => $customer->wishlistItems()->count(),
            'cart' => $customer->carts()->first()?->items()->count() ?? 0,
            'pending' => $customer->orders()->whereNotIn('status', ['Delivered', 'Cancelled'])->count(),
            'completed' => $customer->orders()->where('status', 'Delivered')->where('customer_received', true)->count(),
        ];

        $notifications = $customer->notifications()->latest()->take(5)->get();
        $paymentMethods = $customer->paymentMethods()->latest()->get();

        return view('pages.customer.dashboard.dashboard', compact('customer', 'stats', 'notifications', 'paymentMethods'));
    }
}
