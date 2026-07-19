<?php

// [AGNER] — auth login, role-based redirect (admin → dashboard, customer → cart)
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;

class LoginController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            // CHANGES HERE: update last_login
            Auth::user()->update(['last_login' => now()]);
            $request->session()->regenerate();
            $request->session()->forget('auth_via');

            if ($request->boolean('remember')) {
                Cookie::queue('remember_login_email', $request->email, 43200);
                Cookie::queue('remember_login_password', $request->password, 43200);
            } else {
                Cookie::queue(Cookie::forget('remember_login_email'));
                Cookie::queue(Cookie::forget('remember_login_password'));
            }

            // CHANGES HERE: role-based redirect
            $role = Auth::user()->role;
            if (in_array($role, ['super_admin', 'admin'])) {
                return redirect()->route('admin.dashboard');
            }

            return redirect()->intended(route('home'));
        }

        return back()
            ->withErrors(['email' => 'Invalid email or password.'])
            ->withInput($request->only('email'));
    }
}
