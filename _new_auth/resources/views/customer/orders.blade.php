@extends('layouts.customer')

@section('content')

<div class="max-w-5xl mx-auto">

    <!-- HEADER -->
    <div class="flex items-center justify-between flex-wrap gap-4 mb-6 animate-fade-up">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">My Orders</h1>
            <p class="text-gray-500 mt-1">Track the items currently on their way to you.</p>
        </div>
        <a href="{{ route('products') }}"
           class="inline-flex items-center gap-2 bg-sky-500 hover:bg-sky-600 text-white px-5 py-2.5 rounded-lg font-medium transition hover:-translate-y-0.5 hover-lift">
            <img src="https://cdn.jsdelivr.net/npm/lucide-static@0.460.0/icons/shopping-bag.svg"
                 class="w-4 h-4" alt="">
            Continue shopping
        </a>
    </div>

    <!-- TABS -->
    <div class="flex gap-8 mt-2 font-semibold text-gray-400 border-b border-gray-200 sticky top-0 bg-gray-100 z-10 py-1" id="tabs">
        <button type="button" onclick="filterOrders('all', this)"
                class="tab-btn pb-3 border-b-2 border-sky-500 text-sky-600">
            All
        </button>
        <button type="button" onclick="filterOrders('processing', this)"
                class="tab-btn pb-3 border-b-2 border-transparent">
            Processing
        </button>
        <button type="button" onclick="filterOrders('shipped', this)"
                class="tab-btn pb-3 border-b-2 border-transparent">
            Shipped
        </button>
    </div>

    <!-- ORDER LIST -->
    <div class="stagger space-y-5 mt-6" id="order-list">

        @forelse ($orders as $order)
        <div class="order-card bg-white rounded-2xl shadow-sm border border-gray-200 p-6 hover-lift"
             data-status="{{ strtolower($order->status) }}">

            <!-- top row -->
            <div class="flex items-center justify-between mb-5">
                <div class="flex items-center gap-3">
                    <span class="text-sm font-semibold text-gray-900">#{{ str_pad($order->order_id, 5, '0', STR_PAD_LEFT) }}</span>
                    <span class="text-sm text-gray-400">{{ $order->created_at->format('M d, Y · h:i A') }}</span>
                </div>

                @if($order->status === 'Shipped')
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-sky-100 text-sky-700">
                        <span class="w-1.5 h-1.5 rounded-full bg-sky-500 animate-pulse"></span> Shipped
                    </span>
                @else
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-amber-100 text-amber-700">
                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span> Processing
                    </span>
                @endif
            </div>

            <!-- items -->
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

            <!-- progress + actions -->
            <div class="flex items-center justify-between flex-wrap gap-4 mt-5 pt-5 border-t border-gray-100">
                <div class="flex items-center gap-2 text-xs">
                    <span class="inline-flex items-center gap-1 text-emerald-600 font-medium">
                        <img src="https://cdn.jsdelivr.net/npm/lucide-static@0.460.0/icons/check-circle.svg" class="w-4 h-4" alt="">
                        Placed
                    </span>
                    <span class="w-10 h-px bg-gray-200"></span>
                    <span class="inline-flex items-center gap-1 {{ $order->status === 'Shipped' ? 'text-sky-600 font-medium' : 'text-gray-400' }}">
                        <img src="https://cdn.jsdelivr.net/npm/lucide-static@0.460.0/icons/truck.svg"
                             class="w-4 h-4 {{ $order->status === 'Shipped' ? '' : 'opacity-40' }}" alt="">
                        {{ $order->status === 'Shipped' ? 'Shipped' : 'In transit' }}
                    </span>
                </div>

                <form method="POST" action="{{ route('orders.receive', $order) }}">
                    @csrf
                    <button type="submit"
                            class="inline-flex items-center gap-2 bg-emerald-500 hover:bg-emerald-600 text-white px-5 py-2.5 rounded-lg font-medium transition hover:-translate-y-0.5">
                        <img src="https://cdn.jsdelivr.net/npm/lucide-static@0.460.0/icons/package-check.svg" class="w-4 h-4" alt="">
                        Mark as Received
                    </button>
                </form>
            </div>
        </div>
        @empty
        <!-- EMPTY STATE -->
        <div class="flex flex-col items-center justify-center text-center py-20 animate-fade-up">
            <div class="w-28 h-28 rounded-full bg-sky-50 flex items-center justify-center mb-6">
                <img src="https://cdn.jsdelivr.net/npm/lucide-static@0.460.0/icons/package-open.svg" class="w-14 h-14" alt="">
            </div>
            <h3 class="text-xl font-bold text-gray-900">No active orders</h3>
            <p class="text-gray-500 mt-2 max-w-sm">When you buy something, it'll appear here while it ships. Once delivered, it moves to Order History.</p>
            <a href="{{ route('products') }}"
               class="mt-6 inline-flex items-center gap-2 bg-sky-500 hover:bg-sky-600 text-white px-6 py-3 rounded-lg font-semibold transition hover:-translate-y-0.5">
                Browse products
            </a>
        </div>
        @endforelse

    </div>

    <p id="no-orders" class="hidden text-center text-gray-400 py-16">No orders for this filter.</p>

</div>

<script>
function filterOrders(status, btn) {
    document.querySelectorAll('.tab-btn').forEach(b => {
        b.classList.remove('border-sky-500', 'text-sky-600');
        b.classList.add('border-transparent');
    });
    btn.classList.add('border-sky-500', 'text-sky-600');
    btn.classList.remove('border-transparent');

    let visible = 0;
    document.querySelectorAll('.order-card').forEach(card => {
        const match = (status === 'all') || (card.dataset.status === status);
        card.style.display = match ? '' : 'none';
        if (match) visible++;
    });

    document.getElementById('no-orders').classList.toggle('hidden', visible !== 0);
}
</script>

@endsection
