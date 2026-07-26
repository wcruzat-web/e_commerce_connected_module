<div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl p-5 shadow-sm">
    <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
        <div>
            <p class="text-xs text-gray-400 dark:text-gray-500 mb-1">Carrier</p>
            <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ $order->tracking->courier_name ?? 'TBD' }}</p>
        </div>
        <div>
            <p class="text-xs text-gray-400 dark:text-gray-500 mb-1">Tracking #</p>
            <p class="text-sm font-semibold text-cyan-500">{{ $order->tracking->tracking_number ?? 'TBD' }}</p>
        </div>
        <div>
            <p class="text-xs text-gray-400 dark:text-gray-500 mb-1">Shipped From</p>
            <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ $order->tracking->shipped_from ?? 'TBD' }}</p>
        </div>
        <div>
            <p class="text-xs text-gray-400 dark:text-gray-500 mb-1">Est. Delivery</p>
            <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ $order->tracking ? $order->tracking->estimated_delivery_date->format('F d, Y') : 'TBD' }}</p>
        </div>
    </div>
</div>
