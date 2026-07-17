<?php

namespace App\Http\Controllers;

use App\DTOs\PaymentDataDTO;
use App\Rules\ValidCard;
use App\Services\CartService;
use App\Services\PaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    public function __construct(
        private PaymentService $paymentService,
        private CartService $cartService,
    ) {}

    public function index()
    {
        $checkoutData = session('checkout_data');

        if (!$checkoutData) {
            return redirect()->route('checkout')->with('error', 'Please complete the checkout form first.');
        }

        $customer = Auth::user();
        $cart = $this->cartService->getOrCreateCart($customer->customer_id);

        if ($cart->items->isEmpty()) {
            return redirect()->route('cart')->with('error', 'Your cart is empty.');
        }

        $summary = $this->cartService->getSummary($cart);

        $paymentMethods = $customer->paymentMethods()
            ->orderByDesc('is_default')
            ->orderBy('payment_method_id')
            ->get();

        $defaultMethod = $paymentMethods->firstWhere('is_default', true);

        return view('pages.customer.payment.payment', compact('cart', 'summary', 'checkoutData', 'defaultMethod', 'paymentMethods'));
    }

    public function process(Request $request)
    {
        $checkoutData = session('checkout_data');

        if (!$checkoutData) {
            return redirect()->route('checkout')->with('error', 'Please complete the checkout form first.');
        }

        $customer = Auth::user();
        $cart = $this->cartService->getOrCreateCart($customer->customer_id);

        if ($cart->items->isEmpty()) {
            return redirect()->route('cart')->with('error', 'Your cart is empty.');
        }

        $paymentMethod = $request->input('payment_method');

        $rules = [
            'payment_method' => 'required|string|in:visa,mastercard,gcash',
        ];

        if (in_array($paymentMethod, ['visa', 'mastercard'])) {
            $rules['cardholder_name'] = 'required|string|max:255';
            $rules['card_number'] = ['required', 'string', 'max:19', new ValidCard];
            $rules['expiry_date'] = ['required', 'string', 'max:5', function ($attr, $value, $fail) {
                if (!preg_match('/^(0[1-9]|1[0-2])\/(\d{2})$/', $value, $m)) {
                    $fail('The :attribute format must be MM/YY.');
                    return;
                }
                $exp = \Carbon\Carbon::createFromDate(2000 + (int)$m[2], (int)$m[1], 1)->endOfMonth();
                if ($exp->isPast()) {
                    $fail('The :attribute is expired.');
                }
            }];
            $rules['cvv'] = ['required', 'string', function ($attr, $value, $fail) {
                if (!preg_match('/^\d{3,4}$/', $value)) {
                    $fail('The :attribute must be 3 or 4 digits.');
                }
            }];
        }

        if ($paymentMethod === 'gcash') {
            $rules['gcash_name'] = 'required|string|max:255';
            $rules['gcash_number'] = ['required', 'string', function ($attr, $value, $fail) {
                if (!preg_match('/^\d{10}$/', $value)) {
                    $fail('The :attribute must be exactly 10 digits.');
                }
            }];
        }

        $validated = $request->validate($rules);

        $shippingAddress = $checkoutData['street'] . ', ' . $checkoutData['barangay'] . ', ' . $checkoutData['city'] . ', ' . $checkoutData['province'] . ' ' . $checkoutData['postal_code'] . ', ' . $checkoutData['country'];

        $dto = new PaymentDataDTO(
            paymentMethod: $paymentMethod,
            shippingName: $checkoutData['first_name'] . ' ' . $checkoutData['last_name'],
            shippingEmail: $checkoutData['shipping_email'],
            shippingPhone: $checkoutData['shipping_phone'] ?? '',
            shippingAddress: $shippingAddress,
            notes: $checkoutData['notes'] ?? '',
        );

        $order = $this->paymentService->processPayment($cart, $dto);

        session()->forget('checkout_data');
        session()->put('order_id', $order->order_id);

        return redirect()->route('success');
    }
}
