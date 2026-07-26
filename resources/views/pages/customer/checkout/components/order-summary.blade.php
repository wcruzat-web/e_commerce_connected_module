{{--
    ERP MODULE: Checkout — Shipping & Contact Details (Checkout Page)
    COMPONENT: Order Summary
    DESCRIPTION: Sidebar card showing items count, subtotal, shipping, grand total.
    DATA SOURCE: $summary (CartSummaryDTO)
--}}

<div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl p-5 shadow-sm">
    <h2 class="text-sm font-semibold text-gray-900 dark:text-white mb-4">Order Summary</h2>

    <div class="space-y-2.5 text-sm">
        <div class="flex items-center justify-between">
            <span class="text-gray-500 dark:text-gray-400">Items</span>
            <span id="summaryItemCount" class="font-medium text-gray-900 dark:text-white">{{ $summary->itemsCount }}</span>
        </div>
        <div class="flex items-center justify-between">
            <span class="text-gray-500 dark:text-gray-400">Subtotal</span>
            <span id="summarySubtotal" class="font-medium text-gray-900 dark:text-white">₱{{ number_format($summary->subtotal, 2) }}</span>
        </div>
        <div class="flex items-center justify-between">
            <span class="flex items-center gap-1.5 text-gray-500 dark:text-gray-400">
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
                    <span class="font-medium text-gray-900 dark:text-white">₱0.00</span>
                    <span class="text-[11px] font-bold text-green-600 dark:text-green-400">{{ $summary->isFreeShipping ? 'FREE (Voucher)' : 'FREE' }}</span>
                @else
                    <span class="font-medium text-gray-900 dark:text-white">₱{{ number_format($summary->shippingFee, 2) }}</span>
                @endif
            </div>
        </div>
        <div id="discountRow" class="flex items-center justify-between {{ (float) $summary->discount > 0 ? '' : 'hidden' }}">
            <span class="text-gray-500 dark:text-gray-400">Discount <span id="summaryDiscountLabel" class="text-xs text-gray-400 dark:text-gray-500">{{ $summary->discount > 0 ? '(' . $summary->couponLabel . ')' : '' }}</span></span>
            <span id="summaryDiscount" class="font-medium text-emerald-600 dark:text-emerald-400">-₱{{ number_format($summary->discount, 2) }}</span>
        </div>
    </div>

    <div class="border-t border-gray-100 dark:border-gray-700 my-4"></div>

    <div class="flex items-center justify-between mb-5">
        <span class="text-sm font-semibold text-gray-900 dark:text-white">Grand Total</span>
        <span id="summaryGrandTotal" class="text-lg font-bold text-gray-900 dark:text-white">₱{{ number_format($summary->grandTotal, 2) }}</span>
    </div>

    <p class="flex items-center gap-1.5 text-xs text-gray-400 dark:text-gray-500">
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
