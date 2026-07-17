<div class="flex justify-between items-center mb-3">
                <h3 class="font-bold text-xl" data-i18n="dash.savedPay">
                    Saved Payment Methods
                </h3>
                <a href="{{ route('payment-methods') }}"
                   class="text-sky-500 text-sm font-semibold hover:underline" data-i18n="dash.manage">
                    Manage
                </a>
            </div>

            <div class="space-y-3">
                @forelse($paymentMethods as $method)
                    <div class="border rounded-lg p-4 flex items-center justify-between">
                        <div class="min-w-0">
                            <p class="font-bold text-sm">
                                {{ $method->provider ?? $method->payment_type }}
                            </p>
                            <p class="text-xs text-gray-500 mt-1">
                                {{ $method->masked_account_number }}
                            </p>
                            <p class="text-xs text-gray-400">
                                {{ $method->account_name }}
                            </p>
                        </div>
                        @if($method->is_default)
                            <span class="bg-sky-100 text-sky-600 text-xs font-semibold px-3 py-1 rounded-full shrink-0">
                                Default
                            </span>
                        @endif
                    </div>
                @empty
                    <div class="border-2 border-dashed border-sky-300 rounded-lg p-5 text-center text-gray-400">
                        No saved payment methods yet.
                    </div>
                @endforelse

                <a href="{{ route('add-payment') }}"
                   class="block w-full border-2 border-dashed border-sky-300 text-sky-500 rounded-lg p-4 mt-3 text-center text-sm font-semibold hover:bg-sky-50" data-i18n="dash.addPay">
                    + Add Payment Method
                </a>
            </div>
