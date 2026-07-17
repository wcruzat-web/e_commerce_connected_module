{{-- [AGNER] Mark as Received button — same logic as orders page --}}

<div class="bg-white border border-gray-200 rounded-2xl p-5 shadow-sm">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-sm font-semibold text-gray-900">Order Receipt</h2>
            <p class="text-xs text-gray-400 mt-0.5">Confirm once you've received your order.</p>
        </div>

        @if($order->customer_received)
            <span class="inline-flex items-center gap-2 px-5 py-2.5 rounded-lg font-medium bg-green-100 text-green-700 pointer-events-none cursor-default">
                <img src="https://cdn.jsdelivr.net/npm/lucide-static@0.460.0/icons/check-circle.svg" class="w-4 h-4" alt="">
                Received
            </span>
        @elseif($order->status === 'delivered')
            <form method="POST" action="{{ route('orders.receive', $order) }}">
                @csrf
                <button type="submit"
                        class="inline-flex items-center gap-2 bg-emerald-500 hover:bg-emerald-600 text-white px-5 py-2.5 rounded-lg font-medium transition hover:-translate-y-0.5">
                    <img src="https://cdn.jsdelivr.net/npm/lucide-static@0.460.0/icons/package-check.svg" class="w-4 h-4" alt="">
                    Mark as Received
                </button>
            </form>
        @else
            <span class="inline-flex items-center gap-2 px-5 py-2.5 rounded-lg font-medium bg-gray-100 text-gray-400 pointer-events-none cursor-default">
                <img src="https://cdn.jsdelivr.net/npm/lucide-static@0.460.0/icons/clock.svg" class="w-4 h-4" alt="">
                Awaiting Delivery
            </span>
        @endif
    </div>
</div>