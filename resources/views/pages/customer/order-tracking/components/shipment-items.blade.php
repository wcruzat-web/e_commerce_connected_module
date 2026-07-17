<div class="bg-white border border-gray-200 rounded-2xl p-5 shadow-sm">
    <h2 class="text-sm font-semibold text-gray-900 mb-4">Items in this Shipment</h2>
    <div class="space-y-3">
        @forelse ($order->items as $item)
            <div class="flex items-center justify-between bg-gray-50 rounded-xl px-4 py-3">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-lg bg-white border border-gray-200 flex items-center justify-center text-gray-500 shrink-0">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4.5 h-4.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="6" y="6" width="12" height="12" rx="1"></rect>
                            <rect x="10" y="10" width="4" height="4"></rect>
                            <line x1="6" y1="2" x2="6" y2="5"></line>
                            <line x1="10" y1="2" x2="10" y2="5"></line>
                            <line x1="14" y1="2" x2="14" y2="5"></line>
                            <line x1="18" y1="2" x2="18" y2="5"></line>
                            <line x1="6" y1="19" x2="6" y2="22"></line>
                            <line x1="10" y1="19" x2="10" y2="22"></line>
                            <line x1="14" y1="19" x2="14" y2="22"></line>
                            <line x1="18" y1="19" x2="18" y2="22"></line>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-900">{{ $item->product->name ?? 'Product #' . $item->product_id }}</p>
                        <p class="text-xs text-gray-400">Qty: {{ $item->quantity }}</p>
                    </div>
                </div>
                <p class="text-sm font-semibold text-gray-900">₱{{ number_format($item->subtotal, 2) }}</p>
            </div>
        @empty
            <p class="text-sm text-gray-400">No items found.</p>
        @endforelse
    </div>
</div>
