{{--
    ERP MODULE: Checkout — Payment (Payment Page)
    COMPONENT: Order Summary
    DESCRIPTION: Sidebar card showing items count, subtotal, shipping, tax, and grand total for the payment confirmation page.
    DATA SOURCE: $cart, $summary from PaymentController@index
--}}

<div class="bg-white border border-gray-200 rounded-2xl p-5 shadow-sm">
    <h2 class="text-sm font-semibold text-gray-900 mb-4">Order Summary</h2>

    <div class="space-y-2.5 text-sm">
        <div class="flex items-center justify-between">
            <span class="text-gray-500">Items</span>
            <span id="summaryItemCount" class="font-medium text-gray-900">{{ $summary->itemsCount }}</span>
        </div>
        <div class="flex items-center justify-between">
            <span class="text-gray-500">Subtotal</span>
            <span id="summarySubtotal" class="font-medium text-gray-900">₱{{ number_format($summary->subtotal, 2) }}</span>
        </div>
        <div class="flex items-center justify-between">
            <span class="flex items-center gap-1.5 text-gray-500">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="1" y="3" width="15" height="13"></rect>
                    <polygon points="16 8 20 8 23 11 23 16 16 16 16 8"></polygon>
                    <circle cx="5.5" cy="18.5" r="2.5"></circle>
                    <circle cx="18.5" cy="18.5" r="2.5"></circle>
                </svg>
                Shipping
            </span>
            <div id="summaryShipping" class="flex flex-col items-end">
                @if((float)$summary->shippingFee === 0.0)
                    <span class="font-medium text-gray-900">₱0.00</span>
                    <span class="text-[11px] font-bold text-green-600">{{ $summary->isFreeShipping ? 'FREE (Voucher)' : 'FREE' }}</span>
                @else
                    <span class="font-medium text-gray-900">₱{{ number_format($summary->shippingFee, 2) }}</span>
                @endif
            </div>
        </div>
        <div id="discountRow" class="flex items-center justify-between {{ (float) $summary->discount > 0 ? '' : 'hidden' }}">
            <span class="text-gray-500">Discount <span id="summaryDiscountLabel" class="text-xs text-gray-400">{{ $summary->discount > 0 ? '(' . $summary->couponLabel . ')' : '' }}</span></span>
            <span id="summaryDiscount" class="font-medium text-emerald-600">-₱{{ number_format($summary->discount, 2) }}</span>
        </div>
        <div class="flex items-center justify-between">
            <span class="text-gray-500">Tax (12%)</span>
            <span id="summaryTax" class="font-medium text-gray-900">₱{{ number_format($summary->tax, 2) }}</span>
        </div>
    </div>

    <div class="border-t border-gray-100 my-4"></div>

    <div class="flex items-center justify-between mb-1">
        <span class="text-sm font-semibold text-gray-900">Grand Total</span>
        <span id="summaryGrandTotal" class="text-lg font-bold text-gray-900">₱{{ number_format($summary->grandTotal, 2) }}</span>
    </div>

    <p class="flex items-center gap-1.5 text-xs text-gray-400 mt-3">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <rect x="1" y="3" width="15" height="13"></rect>
            <polygon points="16 8 20 8 23 11 23 16 16 16 16 8"></polygon>
            <circle cx="5.5" cy="18.5" r="2.5"></circle>
            <circle cx="18.5" cy="18.5" r="2.5"></circle>
        </svg>
        @if((float)$summary->shippingFee === 0.0)
            Free shipping on this order
        @else
            Free shipping on orders over ₱3,000
        @endif
    </p>
</div>
