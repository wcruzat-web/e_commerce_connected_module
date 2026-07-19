<?php

// [AGNER] — customer registration, creates Customer record
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        // CHANGES HERE: added phone field, last_login
        $data = $request->validate([
            'first_name' => ['required', 'string', 'max:100'],
            'last_name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'unique:customers,email'],
            'phone' => ['nullable', 'string', 'max:20'],
            'password' => [
                'required', 'string', 'min:8',
                'regex:/[A-Z]/',
                'regex:/[0-9]/',
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
            'phone_number' => $data['phone'] ?? '',
            'password' => Hash::make($data['password']),
            'status' => 'Active',
            'email_verified_at' => now(),
            'last_login' => now(),
        ]);

        return redirect()->route('login')
            ->with('registered', true)
            ->with('success', 'Your account was created successfully! Please log in.');
    }
}
