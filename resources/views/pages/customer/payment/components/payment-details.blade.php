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

    {{-- Saved payment methods --}}
    @if($paymentMethods->count())
        <div id="savedPaymentCards" class="space-y-3 mb-4">
            @foreach ($paymentMethods as $pm)
                @php
                    $pmType = strtolower($pm->payment_type);
                    $pmIsCard = $pmType !== 'gcash';
                    $pmNumber = $pmIsCard ? $pm->masked_account_number : preg_replace('/^\+63/', '', $pm->masked_account_number);
                    $pmExpiry = $pmIsCard && $pm->expiry_date ? $pm->expiry_date->format('m/y') : '';
                @endphp
                <label class="payment-method-card block border border-gray-200 rounded-xl p-4 cursor-pointer transition-colors hover:border-cyan-300 {{ $pm->is_default ? 'border-cyan-500 bg-cyan-50/40' : '' }}"
                       data-type="{{ $pmType }}"
                       data-account="{{ $pm->account_name }}"
                       data-number="{{ $pmNumber }}"
                       data-expiry="{{ $pmExpiry }}"
                       data-cvv="{{ $pm->cvv ?? '' }}">
                    <input type="radio" name="saved_payment" value="{{ $pm->payment_method_id }}" class="hidden" {{ $pm->is_default ? 'checked' : '' }}>
                    <div class="flex items-start justify-between">
                        <div>
                            <div class="flex items-center gap-2 mb-1">
                                @if($pm->is_default)
                                    <span class="text-[10px] font-semibold bg-cyan-100 text-cyan-700 px-2 py-0.5 rounded-full">DEFAULT</span>
                                @endif
                                <span class="text-[10px] font-medium text-gray-400 uppercase">{{ $pm->payment_type }}</span>
                            </div>
                            <p class="text-xs text-gray-500">{{ $pm->account_name }}</p>
                            <p class="text-xs text-gray-400">{{ $pmIsCard ? "**** **** **** " . substr(str_replace(' ', '', $pmNumber), -4) : "+63" . $pmNumber }}</p>
                            @if($pmIsCard && $pmExpiry)
                                <p class="text-xs text-gray-400">Expires {{ $pmExpiry }}</p>
                            @endif
                        </div>
                        <div class="w-5 h-5 rounded-full border-2 flex items-center justify-center shrink-0 mt-1 {{ $pm->is_default ? 'border-cyan-500' : 'border-gray-300' }}">
                            <div class="w-2.5 h-2.5 rounded-full {{ $pm->is_default ? 'bg-cyan-500' : '' }}"></div>
                        </div>
                    </div>
                </label>
            @endforeach
        </div>
    @endif

    @if($paymentMethods->count())
        <button type="button" id="addPaymentBtn"
            class="w-full flex items-center justify-center gap-2 border-2 border-dashed border-gray-300 rounded-xl py-3 text-sm font-medium text-gray-500 hover:border-cyan-400 hover:text-cyan-500 transition-colors mb-4">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <line x1="12" y1="5" x2="12" y2="19"></line>
                <line x1="5" y1="12" x2="19" y2="12"></line>
            </svg>
            Add Payment Method
        </button>
    @endif

    <div id="manualPaymentSection" class="{{ $paymentMethods->count() ? 'hidden' : '' }}">
        {{-- Payment method tabs --}}
        <div id="paymentMethodTabs" class="flex flex-wrap gap-3 mb-6">
    @php $methods = ['visa' => 'Visa', 'mastercard' => 'Mastercard', 'gcash' => 'G-Cash']; @endphp
            @foreach ($methods as $key => $label)
                <button
                    type="button"
                    onclick="selectPaymentMethod('{{ $key }}')"
                    data-method="{{ $key }}"
                    class="payment-method-btn flex-1 min-w-[100px] text-sm font-medium px-4 py-2.5 rounded-lg border transition-colors
                        {{ $key === $defaultType ? 'border-cyan-500 text-cyan-500' : 'border-gray-200 text-gray-500' }}"
                >
                    {{ $label }}
                </button>
            @endforeach
        </div>

        <form method="POST" action="{{ route('payment.process') }}" id="paymentForm" class="space-y-4">
            @csrf
            <input type="hidden" name="payment_method" id="paymentMethod" value="{{ $defaultType }}">

            <div id="cardFields" class="space-y-4 {{ $defaultType === 'gcash' ? 'hidden' : '' }}">
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1.5">Cardholder Name</label>
                <input type="text" name="cardholder_name" id="cardholderName" placeholder="Alex Morgan" value="{{ old('cardholder_name', $defaultMethod->account_name ?? '') }}"
                    class="w-full px-4 py-2.5 text-sm rounded-lg border border-gray-200 bg-gray-100 text-gray-700 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-cyan-400 focus:border-transparent">
                @error('cardholder_name') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1.5">Card Number</label>
                <input type="text" name="card_number" id="cardNumber" placeholder="0123 4567 8901 2345" maxlength="19" value="{{ old('card_number', $defaultCardNumber) }}"
                    class="w-full px-4 py-2.5 text-sm rounded-lg border border-gray-200 bg-gray-100 text-gray-700 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-cyan-400 focus:border-transparent">
                @error('card_number') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1.5">Expiry Date</label>
                    <input type="text" name="expiry_date" id="expiryDate" placeholder="MM/YY" maxlength="5" value="{{ old('expiry_date', $defaultExpiry) }}"
                        class="w-full px-4 py-2.5 text-sm rounded-lg border border-gray-200 bg-gray-100 text-gray-700 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-cyan-400 focus:border-transparent">
                    @error('expiry_date') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1.5">CVV</label>
                    <input type="password" name="cvv" id="cvv" placeholder="•••" maxlength="4" value="{{ old('cvv', $defaultCvv) }}"
                        class="w-full px-4 py-2.5 text-sm rounded-lg border border-gray-200 bg-gray-100 text-gray-700 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-cyan-400 focus:border-transparent">
                    @error('cvv') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>
            </div>
        </div>

        <div id="gcashFields" class="space-y-4 {{ $defaultType !== 'gcash' ? 'hidden' : '' }}">
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1.5">GCash Name</label>
                <input type="text" name="gcash_name" id="gcashName" placeholder="Alex Morgan" value="{{ old('gcash_name', $defaultMethod->account_name ?? '') }}"
                    class="w-full px-4 py-2.5 text-sm rounded-lg border border-gray-200 bg-gray-100 text-gray-700 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-cyan-400 focus:border-transparent">
                @error('gcash_name') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1.5">GCash Number</label>
                <div class="flex">
                    <span class="inline-flex items-center px-3 py-2.5 text-sm rounded-l-lg border border-r-0 border-gray-200 bg-gray-50 text-gray-500 font-medium">+63</span>
                    <input type="text" name="gcash_number" id="gcashNumber" placeholder="9123456789" maxlength="10" value="{{ old('gcash_number', $defaultGcashNumber) }}"
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
                Place Order — <span id="placeOrderTotal">₱{{ number_format($summary->grandTotal, 2) }}</span>
            </button>
        </div>
    </form>
    </div>
</div>
