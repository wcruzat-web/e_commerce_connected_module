{{-- CRUZAT — success order-summary: finalized order totals after placement --}}
{{--
    ERP MODULE: Checkout — Order Confirmation (Success Page)
    COMPONENT: Order Summary
    DESCRIPTION: Sidebar card showing finalized order totals. No voucher or checkout button — order is already placed.
    DATA SOURCE: $order from route /success
--}}

<div class="bg-white border border-gray-200 rounded-2xl p-5 shadow-sm">
    <h2 class="text-sm font-semibold text-gray-900 mb-4">Order Summary</h2>

    <div class="space-y-2.5 text-sm">
        <div class="flex items-center justify-between">
            <span class="text-gray-500">Items ({{ $order->items->sum('quantity') }})</span>
            <span class="font-medium text-gray-900">₱{{ number_format($order->subtotal, 2) }}</span>
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
            @php $shippingFee = $order->subtotal >= 3000 ? 0 : 120; @endphp
            @if((float)$shippingFee === 0.0)
                <div class="flex flex-col items-end">
                    <span class="font-medium text-gray-900">₱0.00</span>
                    <span class="text-[11px] font-bold text-green-600">FREE</span>
                </div>
            @else
                <span class="font-medium text-gray-900">₱{{ number_format($shippingFee, 2) }}</span>
            @endif
        </div>
        <div class="flex items-center justify-between">
            <span class="text-gray-500">Tax (8%)</span>
            <span class="font-medium text-gray-900">₱{{ number_format($order->tax, 2) }}</span>
        </div>
    </div>

    <div class="border-t border-gray-100 my-4"></div>

    <div class="flex items-center justify-between mb-1">
        <span class="text-sm font-semibold text-gray-900">Grand Total</span>
        <span class="text-lg font-bold text-gray-900">₱{{ number_format($order->grand_total, 2) }}</span>
    </div>

    <p class="flex items-center gap-1.5 text-xs text-gray-400 mt-3">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <rect x="1" y="3" width="15" height="13"></rect>
            <polygon points="16 8 20 8 23 11 23 16 16 16 16 8"></polygon>
            <circle cx="5.5" cy="18.5" r="2.5"></circle>
            <circle cx="18.5" cy="18.5" r="2.5"></circle>
        </svg>
        @if((float)$shippingFee === 0.0)
            Free shipping on this order
        @else
            Free shipping on orders over ₱3,000
        @endif
    </p>
</div>
