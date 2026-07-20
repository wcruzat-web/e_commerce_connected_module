<?php
// AGNER — payment methods: card/GCash fields, validation, Luhn check (ERPV0.2.5-0.2.12)

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\SavedPaymentMethod;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
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

        return view('pages.customer.payment-methods.payment-methods', compact('paymentMethods'));
    }

    public function create(): View
    {
        return view('pages.customer.payment-methods.add-payment');
    }

    public function store(Request $request): RedirectResponse
    {
        /** @var Customer $customer */
        $customer = $request->user();

        $data = $this->validatePaymentMethod($request);

        $isDefault = (bool) ($data['is_default'] ?? false);

        if ($isDefault) {
            $customer->paymentMethods()->update(['is_default' => false]);
        }

        if (($data['payment_type'] ?? '') === 'GCash') {
            $data['account_name'] = $data['gcash_name'];
            $data['masked_account_number'] = '+63' . preg_replace('/\D/', '', $data['gcash_number']);
        }

        unset($data['gcash_name'], $data['gcash_number']);

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

        return view('pages.customer.payment-methods.add-payment', compact('method'));
    }

    public function update(Request $request, int $paymentMethod): RedirectResponse
    {
        /** @var Customer $customer */
        $customer = $request->user();
        $method = $customer->paymentMethods()->findOrFail($paymentMethod);

        $data = $this->validatePaymentMethod($request);

        $isDefault = (bool) ($data['is_default'] ?? false);

        if ($isDefault) {
            $customer->paymentMethods()->where('payment_method_id', '<>', $method->payment_method_id)
                ->update(['is_default' => false]);
        }

        if (($data['payment_type'] ?? '') === 'GCash') {
            $data['account_name'] = $data['gcash_name'];
            $data['masked_account_number'] = '+63' . preg_replace('/\D/', '', $data['gcash_number']);
        }

        unset($data['gcash_name'], $data['gcash_number']);

        $method->update(array_merge($data, ['is_default' => $isDefault]));

        return redirect()->route('payment-methods')
            ->with('success', 'Payment method updated.');
    }

    private function validatePaymentMethod(Request $request): array
    {
        $rules = [
            'payment_type' => ['required', 'in:Visa,Mastercard,GCash'],
            'is_default' => ['nullable', 'boolean'],
        ];

        $messages = [
            'payment_type.required' => 'Select a payment method.',
            'payment_type.in' => 'Invalid payment method selected.',
        ];

        if (in_array($request->payment_type, ['Visa', 'Mastercard'])) {
            $rules['account_name'] = ['required', 'string', 'max:255'];
            $rules['masked_account_number'] = ['required', 'string', 'max:19'];
            $rules['expiry_date'] = ['required', 'string', 'max:5'];
            $rules['cvv'] = ['nullable', 'string', 'max:4'];

            $messages['account_name.required'] = 'Enter the cardholder name.';
            $messages['masked_account_number.required'] = 'Enter the card number.';
            $messages['expiry_date.required'] = 'Enter the expiry date.';
        } elseif ($request->payment_type === 'GCash') {
            $rules['gcash_name'] = ['required', 'string', 'max:255'];
            $rules['gcash_number'] = ['required', 'string', 'max:12'];

            $messages['gcash_name.required'] = 'Enter the GCash name.';
            $messages['gcash_number.required'] = 'Enter the GCash number.';
        }

        $validator = Validator::make($request->all(), $rules, $messages);

        $validator->after(function ($v) use ($request) {
            $type = $request->payment_type;

            if (in_array($type, ['Visa', 'Mastercard'])) {
                $number = preg_replace('/\D/', '', $request->masked_account_number);

                if ($number && strlen($number) < 13) {
                    $v->errors()->add('masked_account_number', 'Card number too short.');
                } elseif ($number && !$this->luhnCheck($number)) {
                    $v->errors()->add('masked_account_number', 'Invalid card number.');
                }

                $expiry = $request->expiry_date;
                if ($expiry && !preg_match('/^\d{2}\/\d{2}$/', $expiry)) {
                    $v->errors()->add('expiry_date', 'Invalid expiry date format. Use MM/YY.');
                } elseif ($expiry) {
                    $parts = explode('/', $expiry);
                    $month = (int) $parts[0];
                    $year = (int) $parts[1] + 2000;
                    if ($month < 1 || $month > 12) {
                        $v->errors()->add('expiry_date', 'Invalid month in expiry date.');
                    } elseif (new \DateTime("$year-$month-01") <= new \DateTime('first day of this month')) {
                        $v->errors()->add('expiry_date', 'Card is expired.');
                    }
                }

                $cvv = $request->cvv;
                if ($cvv && !preg_match('/^\d{3,4}$/', $cvv)) {
                    $v->errors()->add('cvv', 'Invalid CVV. Must be 3-4 digits.');
                }
            }

            if ($type === 'GCash') {
                $gcashNum = preg_replace('/\D/', '', (string) $request->gcash_number);
                if ($gcashNum === '' || !preg_match('/^9\d{9}$/', $gcashNum)) {
                    $v->errors()->add('gcash_number', 'Enter a valid GCash number (10 digits starting with 9).');
                }
            }
        });

        return $validator->validate();
    }

    private function luhnCheck(string $digits): bool
    {
        $sum = 0;
        $alt = false;
        for ($i = strlen($digits) - 1; $i >= 0; $i--) {
            $n = (int) $digits[$i];
            if ($alt) {
                $n *= 2;
                if ($n > 9) $n -= 9;
            }
            $sum += $n;
            $alt = !$alt;
        }
        return $sum % 10 === 0;
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
