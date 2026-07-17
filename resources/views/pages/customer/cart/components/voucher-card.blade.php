{{--
    ==================================================================
    ERP MODULE: Shopping Cart (Cart Page)

    COMPONENT: Voucher / Coupon Card

    DESCRIPTION:
    Input field for applying a discount coupon code
    to the cart order.

    ==================================================================

    TODO (Backend Integration):
    - Wire form to POST /cart/voucher with { code }
    - Display applied voucher with discount amount
    - Handle invalid / expired voucher feedback

    ==================================================================
--}}

<div class="bg-white border border-gray-200 rounded-2xl p-5 shadow-sm">
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
            placeholder="Enter code (SHOP20)"
            class="flex-1 min-w-0 px-3 py-2.5 text-sm rounded-lg border border-gray-200 text-gray-700 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-cyan-400 focus:border-transparent"
        >
        <button type="submit" class="shrink-0 bg-cyan-500 hover:bg-cyan-600 transition-colors text-white text-sm font-semibold px-5 py-2.5 rounded-lg">
            Apply
        </button>
    </form>
</div>
