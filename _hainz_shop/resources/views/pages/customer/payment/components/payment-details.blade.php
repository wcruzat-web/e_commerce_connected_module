{{--
    ERP MODULE: Checkout — Payment (Payment Page)
    COMPONENT: Payment Details
    DESCRIPTION: Payment method selection (Visa / Mastercard / G-Cash) with form fields and Place Order button. POSTs to PaymentController@process.
    DATA SOURCE: $summary from PaymentController@index
    ROUTE: POST /payment (payment.process)
--}}

<div class="lg:col-span-2 bg-white border border-gray-200 rounded-2xl p-5 shadow-sm h-fit">
    <div class="flex items-center gap-2 mb-5">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-gray-700" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <rect x="1" y="4" width="22" height="16" rx="2"></rect>
            <line x1="1" y1="10" x2="23" y2="10"></line>
        </svg>
        <h2 class="text-sm font-semibold text-gray-900">Payment Details</h2>
    </div>

    {{-- Payment method tabs --}}
    <div id="paymentMethodTabs" class="flex flex-wrap gap-3 mb-6">
        @php
            $methods = ['visa' => 'Visa', 'mastercard' => 'Mastercard', 'gcash' => 'G-Cash'];
        @endphp
        @foreach ($methods as $key => $label)
            <button
                type="button"
                onclick="selectPaymentMethod('{{ $key }}')"
                data-method="{{ $key }}"
                class="payment-method-btn flex-1 min-w-[100px] text-sm font-medium px-4 py-2.5 rounded-lg border transition-colors
                    {{ $key === 'visa' ? 'border-cyan-500 text-cyan-500' : 'border-gray-200 text-gray-500' }}"
            >
                {{ $label }}
            </button>
        @endforeach
    </div>

    <form method="POST" action="{{ route('payment.process') }}" id="paymentForm" class="space-y-4">
        @csrf
        <input type="hidden" name="payment_method" id="paymentMethod" value="visa">

        <div id="cardFields" class="space-y-4">
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1.5">Cardholder Name</label>
                <input type="text" name="cardholder_name" id="cardholderName" placeholder="Alex Morgan"
                    class="w-full px-4 py-2.5 text-sm rounded-lg border border-gray-200 bg-gray-100 text-gray-700 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-cyan-400 focus:border-transparent">
                @error('cardholder_name') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1.5">Card Number</label>
                <input type="text" name="card_number" id="cardNumber" placeholder="0123 4567 8901 2345" maxlength="19"
                    class="w-full px-4 py-2.5 text-sm rounded-lg border border-gray-200 bg-gray-100 text-gray-700 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-cyan-400 focus:border-transparent">
                @error('card_number') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1.5">Expiry Date</label>
                    <input type="text" name="expiry_date" id="expiryDate" placeholder="MM/YY" maxlength="5"
                        class="w-full px-4 py-2.5 text-sm rounded-lg border border-gray-200 bg-gray-100 text-gray-700 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-cyan-400 focus:border-transparent">
                    @error('expiry_date') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1.5">CVV</label>
                    <input type="password" name="cvv" id="cvv" placeholder="•••" maxlength="4"
                        class="w-full px-4 py-2.5 text-sm rounded-lg border border-gray-200 bg-gray-100 text-gray-700 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-cyan-400 focus:border-transparent">
                    @error('cvv') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>
            </div>
        </div>

        <div id="gcashFields" class="space-y-4 hidden">
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1.5">GCash Name</label>
                <input type="text" name="gcash_name" id="gcashName" placeholder="Alex Morgan"
                    class="w-full px-4 py-2.5 text-sm rounded-lg border border-gray-200 bg-gray-100 text-gray-700 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-cyan-400 focus:border-transparent">
                @error('gcash_name') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1.5">GCash Number</label>
                <div class="flex">
                    <span class="inline-flex items-center px-3 py-2.5 text-sm rounded-l-lg border border-r-0 border-gray-200 bg-gray-50 text-gray-500 font-medium">+63</span>
                    <input type="text" name="gcash_number" id="gcashNumber" placeholder="9123456789" maxlength="10"
                        class="w-full px-4 py-2.5 text-sm rounded-r-lg border border-gray-200 bg-gray-100 text-gray-700 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-cyan-400 focus:border-transparent">
                </div>
                @error('gcash_number') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>
        </div>

        <div class="flex items-center gap-3 pt-2">
            <button type="button" onclick="window.history.back()"
                class="flex items-center gap-2 px-5 py-3 rounded-xl border border-gray-200 text-sm font-medium text-gray-600 hover:bg-gray-50">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="19" y1="12" x2="5" y2="12"></line>
                    <polyline points="12 19 5 12 12 5"></polyline>
                </svg>
                Back
            </button>
            <button type="button" id="placeOrderBtn"
                class="flex-1 bg-cyan-500 hover:bg-cyan-600 transition-colors text-white text-sm font-semibold py-3 rounded-xl">
                Place Order — <span id="placeOrderTotal">${{ number_format($summary->grandTotal, 2) }}</span>
            </button>
        </div>
    </form>
</div>
