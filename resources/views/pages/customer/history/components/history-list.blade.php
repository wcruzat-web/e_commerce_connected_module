<div class="stagger space-y-5 mt-4" id="order-list">

        @forelse ($orders as $order)
        <div class="order-card bg-white rounded-2xl shadow-sm border border-gray-200 p-6 hover-lift"
             data-search="{{ strtolower('#'.str_pad($order->order_id,5,'0',STR_PAD_LEFT).' '.$order->items->pluck('product_name')->implode(' ')) }}">

            <div class="flex items-center justify-between mb-5">
                <div class="flex items-center gap-3">
                    <span class="text-sm font-semibold text-gray-900">{{ $order->order_number ?? '#'.str_pad($order->order_id, 5, '0', STR_PAD_LEFT) }}</span>
                    <span class="text-sm text-gray-400">Placed {{ $order->created_at->format('M d, Y') }}</span>
                </div>
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-700">
                    <img src="https://cdn.jsdelivr.net/npm/lucide-static@0.460.0/icons/check-circle.svg" class="w-3.5 h-3.5" alt="">
                    Delivered
                </span>
            </div>

            <div class="space-y-4">
                @foreach ($order->items as $item)
                <div class="flex items-center gap-4">
                    <img src="{{ $item->product_image ?: 'https://picsum.photos/seed/order'.$item->order_item_id.'/200/200' }}"
                         alt="{{ $item->product_name }}"
                         class="w-16 h-16 object-cover rounded-xl border border-gray-100"
                         onerror="this.onerror=null;this.src='https://picsum.photos/seed/order{{ $item->order_item_id }}/200/200';">
                    <div class="flex-1 min-w-0">
                        <h3 class="font-semibold text-gray-900 truncate">{{ $item->product_name }}</h3>
                        <p class="text-sm text-gray-500">Qty: {{ $item->quantity }} &nbsp;·&nbsp; ₱{{ number_format($item->unit_price, 2) }}</p>
                    </div>
                    <p class="font-bold text-gray-900 shrink-0">₱{{ number_format($item->unit_price * $item->quantity, 2) }}</p>
                </div>
                @endforeach
            </div>

            <div class="flex items-center gap-2 text-xs mt-5 pt-5 border-t border-gray-100 text-emerald-600 font-medium">
                <img src="https://cdn.jsdelivr.net/npm/lucide-static@0.460.0/icons/check-circle.svg" class="w-4 h-4" alt="">
                Order Placed
                <span class="w-10 h-px bg-emerald-200"></span>
                <img src="https://cdn.jsdelivr.net/npm/lucide-static@0.460.0/icons/truck.svg" class="w-4 h-4" alt="">
                Shipped
                <span class="w-10 h-px bg-emerald-200"></span>
                <img src="https://cdn.jsdelivr.net/npm/lucide-static@0.460.0/icons/package-check.svg" class="w-4 h-4" alt="">
                Delivered
            </div>
        </div>
        @empty
        <div class="flex flex-col items-center justify-center text-center py-20 animate-fade-up">
            <div class="w-28 h-28 rounded-full bg-emerald-50 flex items-center justify-center mb-6">
                <img src="https://cdn.jsdelivr.net/npm/lucide-static@0.460.0/icons/archive.svg" class="w-14 h-14" alt="">
            </div>
            <h3 class="text-xl font-bold text-gray-900">No order history yet</h3>
            <p class="text-gray-500 mt-2 max-w-sm">Delivered orders will be archived here so you can look back on what you've bought.</p>
            <a href="{{ route('products') }}"
               class="mt-6 inline-flex items-center gap-2 bg-sky-500 hover:bg-sky-600 text-white px-6 py-3 rounded-lg font-semibold transition hover:-translate-y-0.5">
                Browse products
            </a>
        </div>
        @endforelse

    </div>

    <p id="no-orders" class="hidden text-center text-gray-400 py-16">No orders match your search.</p>
