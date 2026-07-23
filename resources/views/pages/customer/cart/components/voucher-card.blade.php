{{-- CRUZAT — voucher-card: promo code input and apply button / applied badge --}}

<div id="voucherCard" class="bg-white border border-gray-200 rounded-2xl p-5 shadow-sm">
    <div id="voucherApplied" class="{{ isset($summary) && $summary->couponCode ? '' : 'hidden' }}">
        <div class="flex items-center gap-2 mb-4">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-gray-700" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M20.59 13.41L13.42 20.58a2 2 0 0 1-2.83 0L2.59 12.58V6a2 2 0 0 1 2-2h6.58a2 2 0 0 1 1.41.59l8 8a2 2 0 0 1 0 2.82z"></path>
                <line x1="7" y1="7" x2="7.01" y2="7"></line>
            </svg>
            <h2 class="text-sm font-semibold text-gray-900">Voucher Applied</h2>
        </div>
        <p id="voucherAppliedMsg" class="text-xs text-red-500 mb-3 min-h-[14px]"></p>
        <div class="flex items-center justify-between bg-green-50 border border-green-200 rounded-xl p-4">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-full bg-green-100 flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-green-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="20 6 9 17 4 12"></polyline>
                    </svg>
                </div>
                <div>
                    <p id="voucherCode" class="text-sm font-semibold text-gray-900">{{ $summary->couponCode ?? '' }}</p>
                    <p id="voucherLabel" class="text-xs text-gray-500">{{ $summary->couponLabel ?? '' }}</p>
                    <p id="voucherSavings" class="text-xs font-medium text-emerald-600 {{ isset($summary) && (float) $summary->discount > 0 ? '' : 'hidden' }}">
                        {{ isset($summary) && (float) $summary->discount > 0 ? 'You saved ₱' . number_format($summary->discount, 2) : '' }}
                    </p>
                </div>
            </div>
            <button type="button" onclick="removeVoucher()" class="text-sm font-medium text-red-600 hover:text-red-700 transition-colors shrink-0">Remove</button>
        </div>
    </div>

    <div id="voucherForm" class="{{ isset($summary) && $summary->couponCode ? 'hidden' : '' }}">
        <div class="flex items-center gap-2 mb-4">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-gray-700" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M20.59 13.41L13.42 20.58a2 2 0 0 1-2.83 0L2.59 12.58V6a2 2 0 0 1 2-2h6.58a2 2 0 0 1 1.41.59l8 8a2 2 0 0 1 0 2.82z"></path>
                <line x1="7" y1="7" x2="7.01" y2="7"></line>
            </svg>
            <h2 class="text-sm font-semibold text-gray-900">Voucher / Coupon</h2>
        </div>
        <form onsubmit="event.preventDefault(); applyVoucher();" class="flex items-center gap-2">
            <input
                type="text"
                id="voucherInput"
                placeholder="Enter code (e.g. SHOP20)"
                class="flex-1 min-w-0 px-3 py-2.5 text-sm rounded-lg border border-gray-200 text-gray-700 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-cyan-400 focus:border-transparent"
            >
            <button type="submit" class="shrink-0 bg-cyan-500 hover:bg-cyan-600 transition-colors text-white text-sm font-semibold px-5 py-2.5 rounded-lg">
                Apply
            </button>
        </form>
        <div id="voucherMsg" class="mt-3 text-xs text-red-500 min-h-[14px]"></div>
    </div>
</div>
