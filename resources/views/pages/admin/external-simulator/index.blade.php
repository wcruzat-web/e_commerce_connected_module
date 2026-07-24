@extends('layouts.admin')

@section('title', 'ERP Module Integration')

@section('content')

<div class="flex min-h-screen bg-slate-50" style="font-family: 'Outfit', sans-serif;">

    @include('components.admin.sidebar')

    <div class="flex-1 min-w-0">
        {{-- Mobile header with sidebar toggle --}}
        <div class="lg:hidden flex items-center gap-3 px-4 py-3 bg-white border-b border-gray-200">
            <button type="button" onclick="toggleMobileSidebar()" class="p-2 -ml-2 text-gray-600 hover:text-gray-900 hover:bg-gray-100 rounded-lg transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="18" x2="21" y2="18"/></svg>
            </button>
            <h1 class="text-lg font-bold text-gray-900 truncate">ERP Module Integration</h1>
            <div class="ml-auto flex items-center gap-1.5 text-xs text-gray-400">
                <span class="w-1.5 h-1.5 rounded-full bg-green-400"></span>
                Active
            </div>
        </div>
        <div class="p-4 lg:p-6">
        <div class="hidden lg:flex items-center justify-between mb-6">
            <h1 class="text-2xl font-bold text-gray-900">ERP Module Integration</h1>
            <div class="flex items-center gap-2 text-sm text-gray-400">
                <span class="w-2 h-2 rounded-full bg-green-400"></span>
                Web Services Active
            </div>
        </div>

    <div class="flex gap-1 mb-6 border-b">
        <button class="tab-btn px-5 py-2.5 text-sm font-medium rounded-t-lg bg-blue-600 text-white" data-tab="finance">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 inline mr-1.5 -mt-0.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M16 8h-6a2 2 0 1 0 0 4h4a2 2 0 1 1 0 4H8"/><path d="M12 18V6"/></svg>
            Finance & Accounting
        </button>
        <button class="tab-btn px-5 py-2.5 text-sm font-medium rounded-t-lg text-gray-500 hover:text-gray-700 bg-gray-100" data-tab="sales">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 inline mr-1.5 -mt-0.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 5H7a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-2"/><rect x="9" y="3" width="6" height="4" rx="1"/><path d="M9 14l2 2 4-4"/></svg>
            Sales Management
        </button>
    </div>

    {{-- ──────────────────────────────────────────────── --}}
    {{-- FINANCE & ACCOUNTING TAB --}}
    {{-- ──────────────────────────────────────────────── --}}
    <div id="tab-finance" class="tab-content">
        <div class="grid grid-cols-3 max-sm:grid-cols-1 gap-4 mb-5">
            <div class="bg-white rounded-xl border shadow-sm px-5 py-4 flex-1">
                <p class="text-xs text-gray-400 uppercase tracking-wide">Total Orders</p>
                <p class="text-2xl font-bold text-gray-900 mt-1" id="finance-count">0</p>
            </div>
            <div class="bg-white rounded-xl border shadow-sm px-5 py-4 flex-1">
                <p class="text-xs text-gray-400 uppercase tracking-wide">Pending Payment</p>
                <p class="text-2xl font-bold text-amber-500 mt-1" id="finance-pending-count">0</p>
            </div>
            <div class="bg-white rounded-xl border shadow-sm px-5 py-4 flex-1">
                <p class="text-xs text-gray-400 uppercase tracking-wide">Confirmed</p>
                <p class="text-2xl font-bold text-green-600 mt-1" id="finance-paid-count">0</p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div class="bg-white rounded-xl border shadow-sm">
                <div class="px-5 py-4 border-b border-gray-100">
                    <h2 class="text-sm font-semibold text-gray-800">Order Registry</h2>
                </div>
                <div id="finance-order-list" class="p-3 space-y-1.5 max-h-[18rem] lg:max-h-[36rem] overflow-y-auto"></div>
            </div>

            <div class="bg-white rounded-xl border shadow-sm" id="finance-detail-panel">
                <div class="px-5 py-4 border-b border-gray-100">
                    <h2 class="text-sm font-semibold text-gray-800">Payment Processing</h2>
                </div>
                <div id="finance-order-details" class="p-5 text-sm text-gray-400 text-center py-16">Select an order to process payment</div>
            </div>
        </div>
    </div>

    {{-- ──────────────────────────────────────────────── --}}
    {{-- SALES MANAGEMENT TAB --}}
    {{-- ──────────────────────────────────────────────── --}}
    <div id="tab-sales" class="tab-content hidden">
        <div class="grid grid-cols-3 max-sm:grid-cols-1 gap-4 mb-5">
            <div class="bg-white rounded-xl border shadow-sm px-5 py-4 flex-1">
                <p class="text-xs text-gray-400 uppercase tracking-wide">Paid Orders</p>
                <p class="text-2xl font-bold text-gray-900 mt-1" id="sales-count">0</p>
            </div>
            <div class="bg-white rounded-xl border shadow-sm px-5 py-4 flex-1">
                <p class="text-xs text-gray-400 uppercase tracking-wide">Needs Fulfillment</p>
                <p class="text-2xl font-bold text-purple-600 mt-1" id="sales-pending-count">0</p>
            </div>
            <div class="bg-white rounded-xl border shadow-sm px-5 py-4 flex-1">
                <p class="text-xs text-gray-400 uppercase tracking-wide">Delivered</p>
                <p class="text-2xl font-bold text-green-600 mt-1" id="sales-delivered-count">0</p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div class="bg-white rounded-xl border shadow-sm">
                <div class="px-5 py-4 border-b border-gray-100">
                    <h2 class="text-sm font-semibold text-gray-800">Fulfillment Queue</h2>
                </div>
                <div id="sales-order-list" class="p-3 space-y-1.5 max-h-[18rem] lg:max-h-[36rem] overflow-y-auto"></div>
            </div>

            <div class="bg-white rounded-xl border shadow-sm" id="sales-detail-panel">
                <div class="px-5 py-4 border-b border-gray-100">
                    <h2 class="text-sm font-semibold text-gray-800">Order Fulfillment</h2>
                </div>
                <div id="sales-order-details" class="p-5 text-sm text-gray-400 text-center py-16">Select an order to manage fulfillment</div>
            </div>
        </div>
    </div>
</div>

<script>
const BASE = '{{ url("/admin/external/simulator") }}';

document.querySelectorAll('.tab-btn').forEach(btn => {
    btn.addEventListener('click', function () {
        document.querySelectorAll('.tab-btn').forEach(b => {
            b.classList.remove('bg-blue-600', 'text-white');
            b.classList.add('bg-gray-100', 'text-gray-500');
        });
        this.classList.remove('bg-gray-100', 'text-gray-500');
        this.classList.add('bg-blue-600', 'text-white');
        document.querySelectorAll('.tab-content').forEach(t => t.classList.add('hidden'));
        document.getElementById('tab-' + this.dataset.tab).classList.remove('hidden');
    });
});

const statusColors = {
    pending: 'bg-gray-100 text-gray-600',
    processing: 'bg-amber-100 text-amber-600',
    shipped: 'bg-blue-100 text-blue-700',
    in_transit: 'bg-purple-100 text-purple-700',
    out_for_delivery: 'bg-orange-100 text-orange-600',
    delivered: 'bg-green-100 text-green-700',
    cancelled: 'bg-red-100 text-red-600'
};

function renderFinanceDetail(order) {
    const isPaid = order.payment_status === 'paid';
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

    document.getElementById('finance-order-details').innerHTML = `
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
        </div>

        <div class="border-t border-gray-200 pt-4">
            <div class="flex items-center justify-between mb-3">
                <span class="text-sm font-semibold text-gray-800">Payment</span>
                ${isPaid
                    ? `<span class="text-sm font-medium px-3 py-1.5 rounded-full bg-green-100 text-green-700">Paid</span>`
                    : `<span class="text-sm font-medium px-3 py-1.5 rounded-full bg-amber-100 text-amber-600">Pending</span>`
                }
            </div>
            ${isPaid
                ? `<p class="text-xs text-gray-400">Transaction: ${order.finance_transaction_id || 'N/A'}</p>`
                : `<button type="button" onclick="markAsPaidFinance('${order.order_number}')" class="w-full py-2.5 bg-green-600 hover:bg-green-700 text-white text-sm font-semibold rounded-lg transition-colors">Confirm Payment</button>`
            }
            <div id="finance-feedback" class="mt-3 text-sm"></div>
        </div>
    `;
}

function renderSalesDetail(order) {
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
                <select id="sales-status-select" ${order.customer_received ? 'disabled' : ''} onchange="updateSalesStatus('${order.order_number}', this.value)" class="flex-1 border border-gray-300 rounded-lg px-3 py-2.5 text-sm text-gray-700 ${order.customer_received ? 'bg-gray-100 cursor-not-allowed' : ''} focus:outline-none focus:ring-2 focus:ring-purple-200 focus:border-purple-400">
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

function buildFinanceListHtml(orders, selectedId) {
    return orders.map(o => {
        const sel = o.order_id == selectedId ? 'ring-2 ring-blue-400 bg-blue-50' : '';
        const payClass = o.payment_status === 'paid' ? 'bg-green-100 text-green-700' : 'bg-amber-100 text-amber-600';
        const name = o.customer ? (o.customer.first_name || 'N/A') : 'N/A';
        return `<div class="finance-order-item p-3 rounded-lg cursor-pointer hover:bg-gray-50 transition-colors border border-transparent hover:border-gray-200 ${sel}" data-id="${o.order_id}" data-number="${o.order_number}">
            <div class="flex items-center justify-between">
                <div class="min-w-0 flex-1">
                    <p class="text-sm font-medium text-gray-900 truncate">${o.order_number}</p>
                    <p class="text-xs text-gray-500 truncate">${name}</p>
                </div>
                <div class="text-right ml-3">
                    <p class="text-sm font-semibold text-gray-900">₱${Number(o.grand_total).toLocaleString()}</p>
                    <span class="text-[10px] font-medium px-2 py-0.5 rounded-full ${payClass} payment-badge">${o.payment_status}</span>
                </div>
            </div>
        </div>`;
    }).join('');
}

function buildSalesListHtml(orders, selectedId) {
    if (!orders.length) return '<p class="text-sm text-gray-400 text-center py-12">No orders awaiting fulfillment</p>';
    return orders.map(o => {
        const sel = o.order_id == selectedId ? 'ring-2 ring-purple-400 bg-purple-50' : '';
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

function refreshLists(keepSelection) {
    fetch(`${BASE}/list`).then(r => r.json()).then(data => {
        const financeList = document.getElementById('finance-order-list');
        const salesList = document.getElementById('sales-order-list');
        const currentFinance = financeList.querySelector('.finance-order-item.ring-2');
        const currentSales = salesList.querySelector('.sales-order-item.ring-2');
        const selFinanceId = keepSelection ? (currentFinance ? currentFinance.dataset.id : null) : null;
        const selSalesId = keepSelection ? (currentSales ? currentSales.dataset.id : null) : null;

        financeList.innerHTML = buildFinanceListHtml(data.orders, selFinanceId);
        salesList.innerHTML = buildSalesListHtml(data.paidOrders, selSalesId);

        const total = data.orders.length;
        const paid = data.orders.filter(o => o.payment_status === 'paid').length;
        const pendingPay = total - paid;

        document.getElementById('finance-count').textContent = total;
        document.getElementById('finance-pending-count').textContent = pendingPay;
        document.getElementById('finance-paid-count').textContent = paid;

        const salesPaid = data.paidOrders.length;
        const salesDelivered = data.paidOrders.filter(o => o.status === 'delivered').length;
        const needsFulfillment = salesPaid - salesDelivered;

        document.getElementById('sales-count').textContent = salesPaid;
        document.getElementById('sales-pending-count').textContent = needsFulfillment;
        document.getElementById('sales-delivered-count').textContent = salesDelivered;
    });
}

document.getElementById('finance-order-list').addEventListener('click', function (e) {
    const item = e.target.closest('.finance-order-item');
    if (!item) return;
    document.querySelectorAll('.finance-order-item').forEach(i => i.classList.remove('ring-2', 'ring-blue-400', 'bg-blue-50'));
    item.classList.add('ring-2', 'ring-blue-400', 'bg-blue-50');
    fetch(`${BASE}/order/${item.dataset.number}`)
        .then(r => r.json())
        .then(o => renderFinanceDetail(o));
});

document.getElementById('sales-order-list').addEventListener('click', function (e) {
    const item = e.target.closest('.sales-order-item');
    if (!item) return;
    document.querySelectorAll('.sales-order-item').forEach(i => i.classList.remove('ring-2', 'ring-purple-400', 'bg-purple-50'));
    item.classList.add('ring-2', 'ring-purple-400', 'bg-purple-50');
    fetch(`${BASE}/order/${item.dataset.number}`)
        .then(r => r.json())
        .then(o => renderSalesDetail(o));
});

function markAsPaidFinance(orderNumber) {
    const feedback = document.getElementById('finance-feedback');
    feedback.innerHTML = '<span class="text-gray-500">Processing payment...</span>';
    const txnId = 'FA-' + Date.now().toString(16).toUpperCase();
    fetch('/api/external/finance/payment-confirmed', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Authorization': 'Bearer {{ config("external-modules.finance.api_key") }}'
        },
        body: JSON.stringify({ order_number: orderNumber, finance_transaction_id: txnId, paid_at: new Date().toISOString() })
    }).then(r => r.json()).then(data => {
        if (data.success) {
            feedback.innerHTML = `
                <div class="bg-green-50 border border-green-200 rounded-lg p-3">
                    <p class="text-green-700 font-semibold text-sm">Payment Confirmed</p>
                    <p class="text-xs text-gray-500 mt-0.5">Transaction: ${data.transaction_id}</p>
                </div>
            `;
            refreshLists(true);
            const item = document.querySelector(`.finance-order-item[data-number="${orderNumber}"]`);
            if (item) item.click();
        } else {
            feedback.innerHTML = '<span class="text-red-600">' + (data.message || 'Payment failed') + '</span>';
        }
    }).catch(() => {
        feedback.innerHTML = '<span class="text-red-600">Payment confirmation failed</span>';
    });
}

function updateSalesStatus(orderNumber, status) {
    const feedback = document.getElementById('sales-feedback');
    feedback.innerHTML = '<span class="text-gray-500">Updating status...</span>';
    fetch('/api/external/sales/update-status', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Authorization': 'Bearer {{ config("external-modules.sales.api_key") }}'
        },
        body: JSON.stringify({ order_number: orderNumber, status: status })
    }).then(r => r.json()).then(data => {
        if (data.success) {
            feedback.innerHTML = `<span class="text-green-600 font-medium text-sm">Status updated to "${status.charAt(0).toUpperCase() + status.slice(1).replace('_', ' ')}"</span>`;
            refreshLists(true);
        } else {
            feedback.innerHTML = `<span class="text-red-600 text-sm">${data.message}</span>`;
        }
    }).catch(() => {
        feedback.innerHTML = '<span class="text-red-600 text-sm">Failed to update status</span>';
    });
}

refreshLists();
setInterval(refreshLists, 3000);
</script>
    </div>{{-- flex-1 --}}
</div>{{-- flex container --}}
@endsection
