<div class="flex items-center justify-between flex-wrap gap-3">
    <div class="relative flex-1 max-w-xs">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-gray-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <circle cx="11" cy="11" r="8"></circle>
            <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
        </svg>
        <input type="text" id="ordersSearch" placeholder="Search orders..." value="{{ $filters['search'] ?? '' }}" class="w-full pl-9 pr-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-cyan-200 focus:border-cyan-400">
    </div>
    <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2 sm:gap-3">
        <select id="ordersStatusFilter" class="text-sm border border-gray-200 rounded-lg px-3 py-2 text-gray-600 focus:outline-none focus:ring-2 focus:ring-cyan-200 focus:border-cyan-400">
            <option value="">All Status</option>
            <option value="pending" {{ ($filters['status'] ?? '') === 'pending' ? 'selected' : '' }}>Pending</option>
            <option value="processing" {{ ($filters['status'] ?? '') === 'processing' ? 'selected' : '' }}>Processing</option>
            <option value="shipped" {{ ($filters['status'] ?? '') === 'shipped' ? 'selected' : '' }}>Shipped</option>
            <option value="in_transit" {{ ($filters['status'] ?? '') === 'in_transit' ? 'selected' : '' }}>In Transit</option>
            <option value="out_for_delivery" {{ ($filters['status'] ?? '') === 'out_for_delivery' ? 'selected' : '' }}>Out for Delivery</option>
            <option value="delivered" {{ ($filters['status'] ?? '') === 'delivered' ? 'selected' : '' }}>Delivered</option>
            <option value="cancelled" {{ ($filters['status'] ?? '') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
        </select>
        <select id="ordersPaymentFilter" class="text-sm border border-gray-200 rounded-lg px-3 py-2 text-gray-600 focus:outline-none focus:ring-2 focus:ring-cyan-200 focus:border-cyan-400">
            <option value="">All Payments</option>
            <option value="pending" {{ ($filters['payment_status'] ?? '') === 'pending' ? 'selected' : '' }}>Pending</option>
            <option value="paid" {{ ($filters['payment_status'] ?? '') === 'paid' ? 'selected' : '' }}>Paid</option>
        </select>
    </div>
</div>
