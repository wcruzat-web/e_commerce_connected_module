<div class="stagger space-y-5 mt-6" id="order-list">

        @forelse ($orders as $order)
        @php
            $statusName = $order->status === 'in_transit' ? 'In Transit' : ($order->status === 'out_for_delivery' ? 'Out for Delivery' : ucfirst($order->status));
            $badgeColors = [
                'pending' => 'bg-gray-100 text-gray-600',
                'processing' => 'bg-amber-100 text-amber-700',
                'shipped' => 'bg-sky-100 text-sky-700',
                'in_transit' => 'bg-purple-100 text-purple-700',
                'out_for_delivery' => 'bg-orange-100 text-orange-600',
                'delivered' => 'bg-emerald-100 text-emerald-700',
                'cancelled' => 'bg-red-100 text-red-600',
            ];
            $badgeClass = $badgeColors[$order->status] ?? 'bg-gray-100 text-gray-600';
            $isProcessing = in_array($order->status, ['pending','processing','shipped','in_transit','out_for_delivery']);
            $itemCount = $order->items->count();
        @endphp
        <div class="order-card bg-white rounded-2xl shadow-sm border border-gray-200 p-6 hover-lift"
             data-status="{{ $order->status }}"
             data-group="{{ $isProcessing ? 'processing' : ($order->status === 'delivered' ? 'delivered' : 'all') }}">

            <div class="flex items-center justify-between mb-5">
                <div class="flex items-center gap-3">
                    <span class="text-sm font-semibold text-gray-900">#{{ $order->order_number ?? str_pad($order->order_id, 5, '0', STR_PAD_LEFT) }}</span>
                    <span class="text-sm text-gray-400">{{ $order->created_at->format('M d, Y · h:i A') }}</span>
                </div>

                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold {{ $badgeClass }}">
                    @if($order->status === 'delivered')
                        <img src="https://cdn.jsdelivr.net/npm/lucide-static@0.460.0/icons/check-circle.svg" class="w-3.5 h-3.5" alt="">
                    @elseif($order->status === 'cancelled')
                        <img src="https://cdn.jsdelivr.net/npm/lucide-static@0.460.0/icons/x-circle.svg" class="w-3.5 h-3.5" alt="">
                    @else
                        <span class="w-1.5 h-1.5 rounded-full {{ $order->status === 'pending' ? 'bg-gray-400' : ($order->status === 'processing' ? 'bg-amber-500 animate-pulse' : 'bg-sky-500 animate-pulse') }}"></span>
                    @endif
                    {{ $statusName }}
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

            <div class="flex items-center justify-between flex-wrap gap-4 mt-5 pt-5 border-t border-gray-100">
                <div class="flex items-center gap-2 text-xs">
                    @if($order->status === 'delivered' || $order->status === 'cancelled')
                        <span class="inline-flex items-center gap-1 {{ $order->status === 'delivered' ? 'text-emerald-600 font-medium' : 'text-red-500 font-medium' }}">
                            <img src="https://cdn.jsdelivr.net/npm/lucide-static@0.460.0/icons/{{ $order->status === 'delivered' ? 'check-circle' : 'x-circle' }}.svg" class="w-4 h-4" alt="">
                            {{ $statusName }}
                        </span>
                    @elseif($isProcessing)
                        <span class="inline-flex items-center gap-1 text-emerald-600 font-medium">
                            <img src="https://cdn.jsdelivr.net/npm/lucide-static@0.460.0/icons/check-circle.svg" class="w-4 h-4" alt="">
                            Placed
                        </span>
                        <span class="w-10 h-px bg-gray-200"></span>
                        <span class="inline-flex items-center gap-1 {{ in_array($order->status, ['shipped','in_transit','out_for_delivery']) ? 'text-sky-600 font-medium' : 'text-gray-400' }}">
                            <img src="https://cdn.jsdelivr.net/npm/lucide-static@0.460.0/icons/truck.svg"
                                 class="w-4 h-4 {{ in_array($order->status, ['shipped','in_transit','out_for_delivery']) ? '' : 'opacity-40' }}" alt="">
                            {{ in_array($order->status, ['shipped','in_transit','out_for_delivery']) ? $statusName : 'In transit' }}
                        </span>
                    @endif
                    <span class="ml-4 text-sm text-gray-400 font-semibold">Total: ₱{{ number_format($order->grand_total, 2) }}</span>
                </div>

                <div class="flex items-center gap-2">
                    <a href="{{ route('tracking.show', $order->order_id) }}"
                       class="inline-flex items-center gap-2 px-4 py-2.5 rounded-lg font-medium border border-gray-200 text-gray-600 hover:bg-gray-50 transition">
                        <img src="https://cdn.jsdelivr.net/npm/lucide-static@0.460.0/icons/truck.svg" class="w-4 h-4" alt="">
                        Track
                    </a>

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
                            @if($order->status === 'cancelled')
                                <img src="https://cdn.jsdelivr.net/npm/lucide-static@0.460.0/icons/x-circle.svg" class="w-4 h-4" alt="">
                                Cancelled
                            @else
                                <img src="https://cdn.jsdelivr.net/npm/lucide-static@0.460.0/icons/clock.svg" class="w-4 h-4" alt="">
                                {{ $order->status === 'pending' ? 'Pending' : ($order->status === 'processing' ? 'Processing' : ($order->status === 'shipped' ? 'Shipped' : ($order->status === 'in_transit' ? 'In Transit' : 'Out for Delivery'))) }}
                            @endif
                        </span>
                    @endif
                </div>
            </div>
        </div>
        @empty
        <div class="flex flex-col items-center justify-center text-center py-20 animate-fade-up">
            <div class="w-28 h-28 rounded-full bg-sky-50 flex items-center justify-center mb-6">
                <img src="https://cdn.jsdelivr.net/npm/lucide-static@0.460.0/icons/package-open.svg" class="w-14 h-14" alt="">
            </div>
            <h3 class="text-xl font-bold text-gray-900">No orders yet</h3>
            <p class="text-gray-500 mt-2 max-w-sm">When you buy something, it'll appear here. Start shopping to place your first order.</p>
            <a href="{{ route('products') }}"
               class="mt-6 inline-flex items-center gap-2 bg-sky-500 hover:bg-sky-600 text-white px-6 py-3 rounded-lg font-semibold transition hover:-translate-y-0.5">
                Browse products
            </a>
        </div>
        @endforelse

    </div>

    <p id="no-orders" class="hidden text-center text-gray-400 py-16">No orders for this filter.</p>
