<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\CustomerAddress;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AddressController extends Controller
{
    public function index(Request $request): View
    {
        /** @var Customer $customer */
        $customer = $request->user();

        $addresses = $customer->addresses()
            ->orderByDesc('is_default')
            ->orderBy('address_id')
            ->get();

        return view('pages.customer.addresses.addresses', compact('addresses'));
    }

    public function store(Request $request): RedirectResponse
    {
        /** @var Customer $customer */
        $customer = $request->user();

        $data = $request->validate([
            'address_type' => ['required', 'in:Home,Work,Other'],
            'recipient_name' => ['required', 'string', 'max:255'],
            'phone_number' => ['nullable', 'string', 'max:50'],
            'street' => ['required', 'string', 'max:255'],
            'barangay' => ['nullable', 'string', 'max:255'],
            'city' => ['required', 'string', 'max:255'],
            'province' => ['nullable', 'string', 'max:255'],
            'postal_code' => ['nullable', 'string', 'max:20'],
            'country' => ['nullable', 'string', 'max:100'],
            'is_default' => ['nullable', 'boolean'],
        ]);

        $isDefault = (bool) ($data['is_default'] ?? false);

        if ($isDefault) {
            $customer->addresses()->update(['is_default' => false]);
        }

        $customer->addresses()->create(array_merge($data, [
            'is_default' => $isDefault,
            'country' => $data['country'] ?? 'Philippines',
        ]));

        return redirect()->route('addresses')
            ->with('success', 'Address saved.');
    }

    public function destroy(Request $request, int $address): RedirectResponse
    {
        /** @var Customer $customer */
        $customer = $request->user();
        $addressModel = $customer->addresses()->findOrFail($address);
        $addressModel->delete();

        return redirect()->route('addresses')
            ->with('success', 'Address removed.');
    }

    public function edit(Request $request, int $address)
    {
        /** @var Customer $customer */
        $customer = $request->user();
        $addressModel = $customer->addresses()->findOrFail($address);

        return view('pages.customer.addresses.edit-address', compact('addressModel'));
    }

    public function update(Request $request, int $address): RedirectResponse
    {
        /** @var Customer $customer */
        $customer = $request->user();
        $addressModel = $customer->addresses()->findOrFail($address);

        $data = $request->validate([
            'address_type' => ['required', 'in:Home,Work,Other'],
            'recipient_name' => ['required', 'string', 'max:255'],
            'phone_number' => ['nullable', 'string', 'max:50'],
            'street' => ['required', 'string', 'max:255'],
            'barangay' => ['nullable', 'string', 'max:255'],
            'city' => ['required', 'string', 'max:255'],
            'province' => ['nullable', 'string', 'max:255'],
            'postal_code' => ['nullable', 'string', 'max:20'],
            'country' => ['nullable', 'string', 'max:100'],
            'is_default' => ['nullable', 'boolean'],
        ]);

        $isDefault = (bool) ($data['is_default'] ?? false);

        if ($isDefault) {
            $customer->addresses()->where('address_id', '!=', $address)->update(['is_default' => false]);
        }

        $addressModel->update(array_merge($data, [
            'is_default' => $isDefault,
            'country' => $data['country'] ?? 'Philippines',
        ]));

        return redirect()->route('addresses')
            ->with('success', 'Address updated.');
    }
}
