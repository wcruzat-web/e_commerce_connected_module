<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\SavedPaymentMethod;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PaymentMethodController extends Controller
{
    public function index(Request $request): View
    {
        /** @var Customer $customer */
        $customer = $request->user();

        $paymentMethods = $customer->paymentMethods()
            ->orderByDesc('is_default')
            ->orderBy('payment_method_id')
            ->get();

        return view('customer.payment-methods', compact('paymentMethods'));
    }

    public function create(): View
    {
        return view('customer.add-payment');
    }

    public function store(Request $request): RedirectResponse
    {
        /** @var Customer $customer */
        $customer = $request->user();

        $data = $request->validate([
            'payment_type' => ['required', 'in:Credit Card,Debit Card,GCash,Maya,Bank Transfer'],
            'provider' => ['nullable', 'string', 'max:255'],
            'account_name' => ['required', 'string', 'max:255'],
            'masked_account_number' => ['required', 'string', 'max:255'],
            'expiry_date' => ['nullable', 'date'],
            'is_default' => ['nullable', 'boolean'],
        ]);

        $isDefault = (bool) ($data['is_default'] ?? false);

        if ($isDefault) {
            $customer->paymentMethods()->update(['is_default' => false]);
        }

        $customer->paymentMethods()->create(array_merge($data, [
            'is_default' => $isDefault,
            'status' => 'Active',
        ]));

        return redirect()->route('payment-methods')
            ->with('success', 'Payment method saved.');
    }

    public function edit(Request $request, int $paymentMethod): View
    {
        /** @var Customer $customer */
        $customer = $request->user();
        $method = $customer->paymentMethods()->findOrFail($paymentMethod);

        return view('customer.add-payment', compact('method'));
    }

    public function update(Request $request, int $paymentMethod): RedirectResponse
    {
        /** @var Customer $customer */
        $customer = $request->user();
        $method = $customer->paymentMethods()->findOrFail($paymentMethod);

        $data = $request->validate([
            'payment_type' => ['required', 'in:Credit Card,Debit Card,GCash,Maya,Bank Transfer'],
            'provider' => ['nullable', 'string', 'max:255'],
            'account_name' => ['required', 'string', 'max:255'],
            'masked_account_number' => ['required', 'string', 'max:255'],
            'expiry_date' => ['nullable', 'date'],
            'is_default' => ['nullable', 'boolean'],
        ]);

        $isDefault = (bool) ($data['is_default'] ?? false);

        if ($isDefault) {
            $customer->paymentMethods()->where('payment_method_id', '<>', $method->payment_method_id)
                ->update(['is_default' => false]);
        }

        $method->update(array_merge($data, ['is_default' => $isDefault]));

        return redirect()->route('payment-methods')
            ->with('success', 'Payment method updated.');
    }

    public function destroy(Request $request, int $paymentMethod): RedirectResponse
    {
        /** @var Customer $customer */
        $customer = $request->user();
        $method = $customer->paymentMethods()->findOrFail($paymentMethod);
        $method->delete();

        return redirect()->route('payment-methods')
            ->with('success', 'Payment method removed.');
    }

    public function setDefault(Request $request, int $paymentMethod): RedirectResponse
    {
        /** @var Customer $customer */
        $customer = $request->user();
        $method = $customer->paymentMethods()->findOrFail($paymentMethod);

        $customer->paymentMethods()->update(['is_default' => false]);
        $method->update(['is_default' => true]);

        return redirect()->route('payment-methods')
            ->with('success', 'Default payment method updated.');
    }
}
