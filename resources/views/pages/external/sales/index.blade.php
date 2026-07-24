@extends('layouts.external-sales')

@section('title', 'Fulfillment Queue')

@section('content')

<div class="grid grid-cols-3 max-sm:grid-cols-1 gap-4 mb-6">
    <div class="bg-white rounded-xl border shadow-sm px-5 py-4">
        <p class="text-xs text-gray-400 uppercase tracking-wide">Paid Orders</p>
        <p class="text-2xl font-bold text-gray-900 mt-1" id="sales-count">0</p>
    </div>
    <div class="bg-white rounded-xl border shadow-sm px-5 py-4">
        <p class="text-xs text-gray-400 uppercase tracking-wide">Needs Fulfillment</p>
        <p class="text-2xl font-bold text-purple-600 mt-1" id="sales-pending-count">0</p>
    </div>
    <div class="bg-white rounded-xl border shadow-sm px-5 py-4">
        <p class="text-xs text-gray-400 uppercase tracking-wide">Delivered</p>
        <p class="text-2xl font-bold text-green-600 mt-1" id="sales-delivered-count">0</p>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <div class="bg-white rounded-xl border shadow-sm">
        <div class="px-5 py-4 border-b border-gray-100">
            <h2 class="text-sm font-semibold text-gray-800">Fulfillment Queue</h2>
        </div>
        <div id="sales-order-list" class="p-3 space-y-1.5 max-h-[36rem] overflow-y-auto"></div>
    </div>

    <div class="bg-white rounded-xl border shadow-sm" id="sales-detail-panel">
        <div class="px-5 py-4 border-b border-gray-100">
            <h2 class="text-sm font-semibold text-gray-800">Order Fulfillment</h2>
        </div>
        <div id="sales-order-details" class="p-5 text-sm text-gray-400 text-center py-16">Select an order to manage fulfillment</div>
    </div>
</div>

<script>
const BASE = '{{ url("/external") }}';

const statusColors = {
    pending: 'bg-gray-100 text-gray-600',
    processing: 'bg-amber-100 text-amber-600',
    shipped: 'bg-blue-100 text-blue-700',
    in_transit: 'bg-purple-100 text-purple-700',
    out_for_delivery: 'bg-orange-100 text-orange-600',
    delivered: 'bg-green-100 text-green-700',
    cancelled: 'bg-red-100 text-red-600'
};

function renderDetail(order) {
    const badgeClass = statusColors[order.status] || 'bg-gray-100 text-gray-600';

    let itemsHtml = '';
    if (order.items && order.items.length) {
        itemsHtml = order.items.map(item =>
            `<div class="flex items-center justify-between px-4 py-2.5 border-b border-gray-50 last:border-0">
                <div>
                    <p class="text-sm font-medium text-gray-800">${item.product_name || 'Item'}</p>
                    <p class="text-xs text-gray-400">Qty: ${item.quantity} × ₱${Number(item.unit_price).toLocaleString('en', {minimumFractionDigits: 2})}</p>
                </div>
                <p class="text-sm font-semibold text-gray-900">₱${Number(item.subtotal).toLocaleString('en', {minimumFractionDigits: 2})}</p>
            </div>`
        ).join('');
    } else {
        itemsHtml = '<div class="px-4 py-3 text-sm text-gray-400">No items</div>';
    }

    document.getElementById('sales-order-details').innerHTML = `
        <div class="mb-5">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <p class="text-lg font-bold text-gray-900">${order.order_number}</p>
                    <p class="text-xs text-gray-400">${order.created_at ? new Date(order.created_at).toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' }) : ''}</p>
                </div>
                <span class="text-[11px] font-medium px-3 py-1.5 rounded-full ${badgeClass}">${order.status.charAt(0).toUpperCase() + order.status.slice(1)}</span>
            </div>

            <div class="bg-gray-50 rounded-lg p-4 mb-4">
                <p class="text-xs text-gray-400 mb-1">Customer</p>
                <p class="text-sm font-semibold text-gray-800">${order.shipping_name || 'N/A'}</p>
                <p class="text-xs text-gray-500">${order.shipping_email || ''}</p>
                <p class="text-xs text-gray-500">${order.shipping_phone ? 'Phone: ' + order.shipping_phone : ''}</p>
            </div>

            <div class="mb-4">
                <p class="text-xs text-gray-400 mb-1.5">Items</p>
                <div class="border border-gray-200 rounded-lg divide-y divide-gray-100">${itemsHtml}</div>
            </div>

            <div class="flex items-center justify-between border-t border-gray-200 pt-3 mb-4">
                <p class="text-sm font-semibold text-gray-800">Total Amount</p>
                <p class="text-xl font-bold text-gray-900">₱${Number(order.grand_total).toLocaleString('en', {minimumFractionDigits: 2})}</p>
            </div>

            <div class="bg-gray-50 rounded-lg p-3 mb-4">
                <p class="text-xs text-gray-400 mb-1">Payment Reference</p>
                <p class="text-sm font-medium text-gray-800">${order.finance_transaction_id || 'N/A'}</p>
            </div>
        </div>

        <div class="border-t border-gray-200 pt-4">
            <p class="text-sm font-semibold text-gray-800 mb-3">Update Fulfillment Status</p>
            <div class="flex gap-2">
                <select id="sales-status-select" ${order.customer_received ? 'disabled' : ''} onchange="updateStatus('${order.order_number}', this.value)" class="flex-1 border border-gray-300 rounded-lg px-3 py-2.5 text-sm text-gray-700 ${order.customer_received ? 'bg-gray-100 cursor-not-allowed' : ''} focus:outline-none focus:ring-2 focus:ring-indigo-200 focus:border-indigo-400">
                    <option value="processing" ${order.status === 'processing' ? 'selected' : ''}>Processing</option>
                    <option value="shipped" ${order.status === 'shipped' ? 'selected' : ''}>Shipped</option>
                    <option value="in_transit" ${order.status === 'in_transit' ? 'selected' : ''}>In Transit</option>
                    <option value="out_for_delivery" ${order.status === 'out_for_delivery' ? 'selected' : ''}>Out for Delivery</option>
                    <option value="delivered" ${order.status === 'delivered' ? 'selected' : ''}>Delivered</option>
                    <option value="cancelled" ${order.status === 'cancelled' ? 'selected' : ''}>Cancelled</option>
                </select>
            </div>
            <div id="sales-feedback" class="mt-3 text-sm"></div>
        </div>
    `;
}

function buildListHtml(orders, selectedId) {
    if (!orders.length) return '<p class="text-sm text-gray-400 text-center py-12">No orders awaiting fulfillment</p>';
    return orders.map(o => {
        const sel = o.order_id == selectedId ? 'ring-2 ring-indigo-400 bg-indigo-50' : '';
        const name = o.customer ? (o.customer.first_name || 'N/A') : 'N/A';
        const stClass = statusColors[o.status] || 'bg-gray-100 text-gray-600';
        return `<div class="sales-order-item p-3 rounded-lg cursor-pointer hover:bg-gray-50 transition-colors border border-transparent hover:border-gray-200 ${sel}" data-id="${o.order_id}" data-number="${o.order_number}">
            <div class="flex items-center justify-between">
                <div class="min-w-0 flex-1">
                    <p class="text-sm font-medium text-gray-900 truncate">${o.order_number}</p>
                    <p class="text-xs text-gray-500 truncate">${name}</p>
                </div>
                <div class="text-right ml-3">
                    <p class="text-sm font-semibold text-gray-900">₱${Number(o.grand_total).toLocaleString()}</p>
                    <span class="text-[10px] font-medium px-2 py-0.5 rounded-full ${stClass}">${o.status}</span>
                </div>
            </div>
        </div>`;
    }).join('');
}

function refreshSales() {
    fetch(`${BASE}/sales/orders`).then(r => r.json()).then(data => {
        const list = document.getElementById('sales-order-list');
        const current = list.querySelector('.sales-order-item.ring-2');
        const selId = current ? current.dataset.id : null;

        list.innerHTML = buildListHtml(data.orders, selId);

        const total = data.orders.length;
        const delivered = data.orders.filter(o => o.status === 'delivered').length;
        const needsFulfillment = total - delivered;

        document.getElementById('sales-count').textContent = total;
        document.getElementById('sales-pending-count').textContent = needsFulfillment;
        document.getElementById('sales-delivered-count').textContent = delivered;
    });
}

document.getElementById('sales-order-list').addEventListener('click', function (e) {
    const item = e.target.closest('.sales-order-item');
    if (!item) return;
    document.querySelectorAll('.sales-order-item').forEach(i => i.classList.remove('ring-2', 'ring-indigo-400', 'bg-indigo-50'));
    item.classList.add('ring-2', 'ring-indigo-400', 'bg-indigo-50');
    fetch(`${BASE}/order/${item.dataset.number}`)
        .then(r => r.json())
        .then(o => renderDetail(o));
});

function updateStatus(orderNumber, status) {
    const feedback = document.getElementById('sales-feedback');
    feedback.innerHTML = '<span class="text-gray-500">Updating status...</span>';
    fetch('/api/external/sales/orders/' + orderNumber, {
        method: 'PATCH',
        headers: {
            'Content-Type': 'application/json',
            'Authorization': 'Bearer {{ config("external-modules.sales.api_key") }}'
        },
        body: JSON.stringify({ status: status })
    }).then(r => r.json()).then(data => {
        if (data.success) {
            feedback.innerHTML = `<span class="text-green-600 font-medium text-sm">Status updated to "${status.charAt(0).toUpperCase() + status.slice(1).replace('_', ' ')}"</span>`;
            refreshSales();
        } else {
            feedback.innerHTML = `<span class="text-red-600 text-sm">${data.message}</span>`;
        }
    }).catch(() => {
        feedback.innerHTML = '<span class="text-red-600 text-sm">Failed to update status</span>';
    });
}

refreshSales();
setInterval(refreshSales, 5000);
</script>

@endsection
