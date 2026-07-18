<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\CustomerService;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    public function __construct(
        private CustomerService $customerService,
    ) {}

    public function showLoginForm()
    {
        return view('pages.customer.auth.login');
    }

    public function login(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $customer = $this->customerService->authenticate($validated['email'], $validated['password'], $request->boolean('remember'));

        if (!$customer) {
            return back()->withErrors(['email' => 'The email or password you entered is incorrect.'])
                ->onlyInput('email');
        }

        $redirect = $customer->role !== 'customer' ? '/admin/dashboard' : '/shop';

        return redirect()->to($redirect);
    }

    public function logout(Request $request)
    {
        $this->customerService->logout();

        return redirect()->route('login');
    }
}
