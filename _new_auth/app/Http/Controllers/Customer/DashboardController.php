<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        /** @var Customer $customer */
        $customer = $request->user();

        // Real counts. Cart comes from the session cart; Orders are not part
        // of this module yet, so pending/completed start at 0.
        $stats = [
            'wishlist' => $customer->wishlistItems()->count(),
            'cart' => count(session('cart', [])),
            'pending' => 0,
            'completed' => 0,
        ];

        $notifications = $customer->notifications()->latest()->take(5)->get();
        $paymentMethods = $customer->paymentMethods()->latest()->get();

        return view('customer.dashboard', compact('customer', 'stats', 'notifications', 'paymentMethods'));
    }
}
