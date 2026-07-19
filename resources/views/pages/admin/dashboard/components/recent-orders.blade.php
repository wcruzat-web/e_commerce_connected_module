{{-- CRUZAT — recent-orders: latest orders table with status badges --}}{{--
    ERP MODULE: Admin Dashboard
    COMPONENT: Recent Orders
    DESCRIPTION: Recent orders table with status badges.
    TODO: Replace with $recentOrders from DashboardController
--}}

<div class="xl:col-span-2 bg-white border border-gray-200 rounded-2xl p-5 shadow-sm">
    <div class="flex items-center justify-between mb-4">
        <h2 class="text-sm font-semibold text-gray-900">Recent Orders</h2>
        <a href="{{ route('admin.orders') }}" class="text-xs font-medium text-cyan-500 hover:text-cyan-600">View all &gt;</a>
    </div>

    <div class="divide-y divide-gray-100">
        @forelse ($recentOrders as $order)
            <div class="flex items-center justify-between py-3 {{ $loop->first ? 'pt-0' : '' }}">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-full bg-gray-200 shrink-0"></div>
                    <div>
                        <p class="text-sm font-medium text-gray-900">{{ $order['name'] }}</p>
                        <p class="text-xs text-gray-400 truncate max-w-[200px]">{{ $order['spec'] }}</p>
                    </div>
                </div>
                <div class="flex items-center gap-4">
                    <p class="text-sm font-semibold text-gray-900">₱{{ number_format($order['price'], 2) }}</p>
                    <span class="text-[11px] font-medium px-2.5 py-1 rounded-full
                        @if($order['status'] === 'shipped') bg-blue-100 text-blue-700
                        @elseif($order['status'] === 'processing') bg-amber-100 text-amber-600
                        @elseif($order['status'] === 'delivered') bg-green-100 text-green-700
                        @else bg-gray-100 text-gray-600
                        @endif">
                        {{ ucfirst($order['status']) }}
                    </span>
                </div>
            </div>
        @empty
            <p class="text-sm text-gray-400 py-3">No orders yet.</p>
        @endforelse
    </div>
</div>
