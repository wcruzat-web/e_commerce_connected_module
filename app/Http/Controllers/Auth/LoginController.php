<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;

// AGNER — LoginController: last_login on login, role-based redirect (ERPV0.2)
class LoginController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials, true)) {
            // CHANGES HERE: update last_login
            Auth::user()->update(['last_login' => now()]);
            $request->session()->regenerate();
            $request->session()->forget('auth_via');

            Cookie::queue('remember_login_email', $request->email, 43200);
            Cookie::queue('remember_login_password', $request->password, 43200);

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
