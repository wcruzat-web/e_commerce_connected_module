<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class ChangePasswordController extends Controller
{
    /**
     * Show the change-password form. Google-authenticated users cannot
     * set a password here, so they see a notice instead of the form.
     */
    public function edit(Request $request): View
    {
        /** @var Customer $customer */
        $customer = $request->user();

        $isGoogle = session('auth_via') === 'google';

        return view('customer.change-password', compact('isGoogle'));
    }

    public function update(Request $request): RedirectResponse
    {
        // Google users have no local password to change.
        abort_if(session('auth_via') === 'google', 403,
            'You signed in with Google, so this feature is not available.');

        /** @var Customer $customer */
        $customer = $request->user();

        $data = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $customer->update([
            'password' => Hash::make($data['password']),
        ]);

        return redirect()->route('profile')
            ->with('success', 'Password changed.');
    }
}
