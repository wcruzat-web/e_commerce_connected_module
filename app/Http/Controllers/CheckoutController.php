<?php
// CRUZAT — checkout: shipping, contact, address selection, order placement (ERPV0.2)

namespace App\Http\Controllers;

use App\Services\AddressService;
use App\Services\CartService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckoutController extends Controller
{
    public function __construct(
        private CartService $cartService,
        private AddressService $addressService,
    ) {}

    public function index()
    {
        $customer = Auth::user();
        $cart = $this->cartService->getOrCreateCart($customer->customer_id);

        if ($cart->items->isEmpty()) {
            return redirect()->route('cart')->with('error', 'Your cart is empty.');
        }

        $summary = $this->cartService->getSummary($cart);
        $addresses = $this->addressService->getAddresses($customer);

        return view('pages.customer.checkout.checkout', compact('cart', 'summary', 'addresses'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'shipping_email' => 'required|email|max:255',
            'shipping_phone' => 'nullable|string|max:20',
            'street' => 'required|string|max:150',
            'barangay' => 'required|string|max:100',
            'city' => 'required|string|max:100',
            'province' => 'required|string|max:100',
            'postal_code' => 'required|string|max:10',
            'country' => 'required|string|max:100',
            'address_type' => 'nullable|string|max:20',
            'notes' => 'nullable|string|max:500',
        ]);

        $customer = Auth::user();
        $cart = $this->cartService->getOrCreateCart($customer->customer_id);

        if ($cart->items->isEmpty()) {
            return redirect()->route('cart')->with('error', 'Your cart is empty.');
        }

        $this->addressService->saveFromOrder($customer, $validated);

        session()->put('checkout_data', $validated);

        return redirect()->route('payment');
    }

    public function saveAddress(Request $request)
    {
        $validated = $request->validate([
            'address_id' => 'nullable|integer|exists:customer_addresses,address_id',
            'address_type' => 'required|string|max:20',
            'street' => 'required|string|max:150',
            'barangay' => 'required|string|max:100',
            'city' => 'required|string|max:100',
            'province' => 'required|string|max:100',
            'postal_code' => 'required|string|max:10',
            'country' => 'required|string|max:100',
        ]);

        $customer = Auth::user();
        $address = $this->addressService->saveOrUpdate($customer, $validated);

        return response()->json(['address' => $address->toArray()]);
    }
}
