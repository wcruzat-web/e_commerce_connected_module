{{--
    ERP MODULE: Admin Orders
    COMPONENT: Orders Table
    DESCRIPTION: Full orders table with status badges.
    TODO: Replace with $orders from backend
--}}

<div class="orders-print-area bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
    <table class="w-full text-sm">
        {{-- Table header --}}
        <thead>
            <tr class="border-b border-gray-100 text-left text-xs text-gray-400 uppercase tracking-wider">
                <th class="px-5 py-3 font-medium">Customer</th>
                <th class="px-5 py-3 font-medium">Items</th>
                <th class="px-5 py-3 font-medium">Total</th>
                <th class="px-5 py-3 font-medium">Status</th>
                <th class="px-5 py-3 font-medium">Date</th>
            </tr>
        </thead>
        {{-- Table body --}}
        <tbody class="divide-y divide-gray-50">
            @foreach ($orders as $order)
                @php
                    $customerName = $order->shipping_name;
                    $itemSummary = $order->items->take(2)->pluck('product.name')->implode(', ');
                    if ($order->items->count() > 2) $itemSummary .= ' +' . ($order->items->count() - 2) . ' more';
                @endphp
                <tr data-order-row
                    data-order-id="{{ $order->order_id }}"
                    data-customer-name="{{ $customerName }}"
                    data-order-status="{{ $order->status }}"
                    data-order-date="{{ $order->created_at->format('Y-m-d') }}"
                    class="hover:bg-gray-50 transition-colors cursor-pointer"
                    onclick="openOrderModal({{ $order->order_id }})">
                    <td class="px-5 py-3">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full bg-gray-200 shrink-0"></div>
                            <span class="font-medium text-gray-900">{{ $customerName }}</span>
                        </div>
                    </td>
                    <td class="px-5 py-3 text-gray-600">{{ $itemSummary ?: 'N/A' }}</td>
                    <td class="px-5 py-3 font-semibold text-gray-900">₱{{ number_format($order->grand_total, 2) }}</td>
                    <td class="px-5 py-3">
                        <span class="status-badge text-[11px] font-medium px-2.5 py-1 rounded-full
                            @if($order->status === 'shipped') bg-blue-100 text-blue-700
                            @elseif($order->status === 'processing') bg-amber-100 text-amber-600
                            @elseif($order->status === 'in_transit') bg-purple-100 text-purple-700
                            @elseif($order->status === 'out_for_delivery') bg-orange-100 text-orange-600
                            @elseif($order->status === 'cancelled') bg-red-100 text-red-600
                            @elseif($order->status === 'delivered') bg-green-100 text-green-700
                            @else bg-gray-100 text-gray-600
                            @endif">
                            {{ ucfirst($order->status) }}
                        </span>
                    </td>
                    <td class="px-5 py-3 text-gray-400">{{ $order->created_at->format('Y-m-d') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    </div>
</div>
