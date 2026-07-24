<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ForgotPasswordController extends Controller
{
    public function index(Request $request): RedirectResponse|\Illuminate\View\View
    {
        if ($request->isMethod('post')) {
            $request->validate([
                'email' => ['required', 'email', 'exists:customers,email'],
                'password' => ['required', 'min:8', 'confirmed'],
            ]);

            $customer = Customer::where('email', $request->email)->first();
            $customer->update(['password' => bcrypt($request->password)]);

            return redirect()->route('login')
                ->with('success', 'Password reset successfully! Please login with your new password.');
        }

        return view('auth.forgot-password');
    }
}
