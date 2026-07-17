<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'first_name' => ['required', 'string', 'max:100'],
            'last_name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'unique:customers,email'],
            'password' => [
                'required', 'string', 'min:8',
                'regex:/[A-Z]/',   // at least one capital letter
                'regex:/[0-9]/',   // at least one number
                'confirmed',
            ],
        ], [
            'password.regex' => 'Password must include at least one capital letter and one number.',
            'password.min' => 'Password must be at least 8 characters.',
        ]);

        Customer::create([
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'status' => 'Active',
            'email_verified_at' => now(),
        ]);

        // Do not auto-login: send the new user to the login page with a
        // success flag so the front-end can play a confirmation animation.
        return redirect()->route('login')
            ->with('registered', true)
            ->with('success', 'Your account was created successfully! Please log in.');
    }
}
