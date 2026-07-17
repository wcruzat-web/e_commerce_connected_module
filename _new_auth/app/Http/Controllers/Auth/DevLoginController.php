<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * One-click dev login so the UI can be clicked through without a full
 * authentication flow. Logs in the seeded demo customer
 * (demo@shopease.test / password).
 */
class DevLoginController extends Controller
{
    public function login(): RedirectResponse
    {
        // Dev-only convenience login — disabled on production so the demo
        // account can't be reached publicly.
        if (app()->environment('production')) {
            abort(404);
        }

        $customer = Customer::where('email', 'demo@shopease.test')->firstOrFail();
        Auth::login($customer);

        return redirect()->route('home');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
