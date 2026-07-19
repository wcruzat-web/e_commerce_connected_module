{{-- CRUZAT — order-confirmed: confirmation card with checkmark, order number, action buttons --}}
{{--
    ERP MODULE: Checkout — Order Confirmation (Success Page)
    COMPONENT: Order Confirmed Card
    DESCRIPTION: Main confirmation card with checkmark, order number, confirmation message, order meta strip (total, est. delivery, tracking), and action buttons.
    DATA SOURCE: $order from route /success
--}}

<div class="lg:col-span-2 bg-white border border-gray-200 rounded-2xl p-10 shadow-sm h-fit text-center">
    <div class="w-16 h-16 mx-auto rounded-full bg-cyan-500 flex items-center justify-center mb-5">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
            <polyline points="20 6 9 17 4 12"></polyline>
        </svg>
    </div>

    <h1 class="text-xl font-bold text-gray-900 mb-1">Order Confirmed!</h1>
    <p class="text-sm text-gray-400 mb-1">Order #{{ $order->order_number }}</p>
    <p class="text-sm text-gray-400 max-w-md mx-auto">
        A confirmation email has been sent to your email address. Your order will be processed within 24 hours.
    </p>

    <div class="bg-gray-100 rounded-xl mt-6 mb-6 py-5 px-6 grid grid-cols-1 sm:grid-cols-3 gap-4 text-left sm:text-center">
        <div>
            <p class="text-xs text-gray-400 mb-1">Order Total</p>
            <p class="text-sm font-bold text-gray-900">₱{{ number_format($order->grand_total, 2) }}</p>
        </div>
        <div>
            <p class="text-xs text-gray-400 mb-1">Est. Delivery</p>
            <p class="text-sm font-bold text-gray-900">{{ $order->tracking ? $order->tracking->estimated_delivery_date->format('M d, Y') : 'TBD' }}</p>
        </div>
        <div>
            <p class="text-xs text-gray-400 mb-1">Tracking</p>
            <p class="text-sm font-bold text-cyan-500">{{ $order->tracking->tracking_number ?? $order->order_number }}</p>
        </div>
    </div>

    <div class="flex flex-col sm:flex-row items-center justify-center gap-3">
        <button type="button" onclick="trackOrder()"
            class="w-full sm:w-auto flex items-center justify-center gap-2 bg-cyan-500 hover:bg-cyan-600 transition-colors text-white text-sm font-semibold px-8 py-3 rounded-xl">
            Track Order
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <line x1="5" y1="12" x2="19" y2="12"></line>
                <polyline points="12 5 19 12 12 19"></polyline>
            </svg>
        </button>
        <a href="{{ route('products.index') }}"
            class="w-full sm:w-auto bg-white border border-gray-200 hover:bg-gray-50 transition-colors text-gray-700 text-sm font-semibold px-8 py-3 rounded-xl inline-block text-center">
            Continue Shopping
        </a>
    </div>
</div>
