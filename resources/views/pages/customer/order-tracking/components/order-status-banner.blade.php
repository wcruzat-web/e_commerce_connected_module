@php
    $badgeColors = [
        'Order Placed' => 'bg-blue-500',
        'Processing' => 'bg-yellow-500',
        'Shipped' => 'bg-cyan-500',
        'In Transit' => 'bg-cyan-500',
        'Out for Delivery' => 'bg-purple-500',
        'Delivered' => 'bg-green-500',
        'pending' => 'bg-yellow-500',
        'processing' => 'bg-blue-500',
        'shipped' => 'bg-cyan-500',
        'delivered' => 'bg-green-500',
    ];
    $displayStatus = $order->tracking ? $order->tracking->order_status : ucfirst($order->status);
    $badgeColor = $badgeColors[$order->tracking ? $order->tracking->order_status : $order->status] ?? 'bg-gray-500';
@endphp

<div class="bg-blue-900 rounded-2xl px-6 py-5 flex flex-wrap items-center justify-between gap-4">
    <div>
        <p class="text-xs text-blue-200/80 mb-1">Order ID</p>
        <div class="flex items-center gap-2">
            <span id="orderIdText" class="text-white text-xl font-bold tracking-wide">{{ $order->order_number }}</span>
            <button type="button" onclick="copyOrderId()" title="Copy Order ID" class="text-blue-200 hover:text-white transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="9" y="9" width="11" height="11" rx="2"></rect>
                    <path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path>
                </svg>
            </button>
        </div>
    </div>
    <div class="text-right">
        <p class="text-xs text-blue-200/80 mb-1">Current Status</p>
        <span class="inline-block {{ $badgeColor }} text-white text-sm font-semibold px-4 py-1.5 rounded-full">
            {{ $displayStatus }}
        </span>
    </div>
</div>
