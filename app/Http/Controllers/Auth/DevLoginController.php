<?php

// [CRUZAT] — dev auto-login + logout handler
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DevLoginController extends Controller
{
    public function login(): RedirectResponse
    {
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
