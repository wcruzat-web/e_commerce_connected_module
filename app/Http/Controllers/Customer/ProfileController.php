<?php
// [AGNER] Profile management

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function index(Request $request): View
    {
        /** @var Customer $customer */
        $customer = $request->user();

        $isGoogle = session('auth_via') === 'google';
        $addresses = $customer->addresses()
            ->orderByDesc('is_default')
            ->orderBy('address_id')
            ->get();

        return view('customer.profile', compact('customer', 'isGoogle', 'addresses'));
    }

    public function update(Request $request): RedirectResponse
    {
        /** @var Customer $customer */
        $customer = $request->user();

        $data = $request->validate([
            'first_name' => ['required', 'string', 'max:100'],
            'last_name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:255', 'unique:customers,email,'.$customer->customer_id.',customer_id'],
            'phone_number' => ['nullable', 'string', 'max:50'],
            'profile_picture' => ['nullable', 'image', 'max:2048'],
        ]);

        if ($request->hasFile('profile_picture')) {
            $path = $request->file('profile_picture')->store('profiles', 'public');
            $data['profile_picture'] = 'storage/'.$path;
        } else {
            unset($data['profile_picture']);
        }

        $customer->update($data);

        auth()->login($customer);

        return redirect()->route('profile')
            ->with('success', 'Profile updated.');
    }
}
