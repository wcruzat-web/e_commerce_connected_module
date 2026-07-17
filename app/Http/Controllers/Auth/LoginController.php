<?php

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
            $request->session()->regenerate();
            $request->session()->forget('auth_via');

            if ($request->boolean('remember')) {
                Cookie::queue('remember_login_email', $request->email, 43200);
                Cookie::queue('remember_login_password', $request->password, 43200);
            } else {
                Cookie::queue(Cookie::forget('remember_login_email'));
                Cookie::queue(Cookie::forget('remember_login_password'));
            }

            return redirect()->intended(route('home'));
        }

        return back()
            ->withErrors(['email' => 'Invalid email or password.'])
            ->withInput($request->only('email'));
    }
}
