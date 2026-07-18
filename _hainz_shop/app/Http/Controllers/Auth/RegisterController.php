<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\CustomerService;
use Illuminate\Http\Request;

class RegisterController extends Controller
{
    public function __construct(
        private CustomerService $customerService,
    ) {}

    public function showRegistrationForm()
    {
        return view('pages.customer.auth.register');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:50',
            'last_name' => 'required|string|max:50',
            'email' => 'required|email|max:100|unique:customers,email',
            'password' => 'required|string|min:8|confirmed',
            'phone_number' => 'nullable|string|max:20',
        ]);

        $this->customerService->register($validated);

        return redirect()->to('/shop');
    }
}
