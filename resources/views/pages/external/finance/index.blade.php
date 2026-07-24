@extends('layouts.external-finance')

@section('title', 'Payment Processing')

@section('content')

<div class="grid grid-cols-3 max-sm:grid-cols-1 gap-4 mb-6">
    <div class="bg-white rounded-xl border shadow-sm px-5 py-4">
        <p class="text-xs text-gray-400 uppercase tracking-wide">Total Orders</p>
        <p class="text-2xl font-bold text-gray-900 mt-1" id="finance-count">0</p>
    </div>
    <div class="bg-white rounded-xl border shadow-sm px-5 py-4">
        <p class="text-xs text-gray-400 uppercase tracking-wide">Pending Payment</p>
        <p class="text-2xl font-bold text-amber-500 mt-1" id="finance-pending-count">0</p>
    </div>
    <div class="bg-white rounded-xl border shadow-sm px-5 py-4">
        <p class="text-xs text-gray-400 uppercase tracking-wide">Confirmed</p>
        <p class="text-2xl font-bold text-green-600 mt-1" id="finance-paid-count">0</p>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <div class="bg-white rounded-xl border shadow-sm">
        <div class="px-5 py-4 border-b border-gray-100">
            <h2 class="text-sm font-semibold text-gray-800">Order Registry</h2>
        </div>
        <div id="finance-order-list" class="p-3 space-y-1.5 max-h-[36rem] overflow-y-auto"></div>
    </div>

    <div class="bg-white rounded-xl border shadow-sm" id="finance-detail-panel">
        <div class="px-5 py-4 border-b border-gray-100">
            <h2 class="text-sm font-semibold text-gray-800">Payment Processing</h2>
        </div>
        <div id="finance-order-details" class="p-5 text-sm text-gray-400 text-center py-16">Select an order to process payment</div>
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
                : `<button type="button" onclick="markAsPaid('${order.order_number}')" class="w-full py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-semibold rounded-lg transition-colors">Confirm Payment</button>`
            }
            <div id="finance-feedback" class="mt-3 text-sm"></div>
        </div>
    `;
}

function buildListHtml(orders, selectedId) {
    return orders.map(o => {
        const sel = o.order_id == selectedId ? 'ring-2 ring-emerald-400 bg-emerald-50' : '';
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

function refreshFinance() {
    fetch(`${BASE}/finance/orders`).then(r => r.json()).then(data => {
        const list = document.getElementById('finance-order-list');
        const current = list.querySelector('.finance-order-item.ring-2');
        const selId = current ? current.dataset.id : null;

        list.innerHTML = buildListHtml(data.orders, selId);

        const total = data.orders.length;
        const paid = data.orders.filter(o => o.payment_status === 'paid').length;
        const pendingPay = total - paid;

        document.getElementById('finance-count').textContent = total;
        document.getElementById('finance-pending-count').textContent = pendingPay;
        document.getElementById('finance-paid-count').textContent = paid;
    });
}

document.getElementById('finance-order-list').addEventListener('click', function (e) {
    const item = e.target.closest('.finance-order-item');
    if (!item) return;
    document.querySelectorAll('.finance-order-item').forEach(i => i.classList.remove('ring-2', 'ring-emerald-400', 'bg-emerald-50'));
    item.classList.add('ring-2', 'ring-emerald-400', 'bg-emerald-50');
    fetch(`${BASE}/order/${item.dataset.number}`)
        .then(r => r.json())
        .then(o => renderDetail(o));
});

function markAsPaid(orderNumber) {
    const feedback = document.getElementById('finance-feedback');
    feedback.innerHTML = '<span class="text-gray-500">Processing payment...</span>';
    const txnId = 'FA-' + Date.now().toString(16).toUpperCase();
    fetch('/api/external/finance/orders/' + orderNumber + '/payments', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Authorization': 'Bearer {{ config("external-modules.finance.api_key") }}'
        },
        body: JSON.stringify({ finance_transaction_id: txnId, paid_at: new Date().toISOString() })
    }).then(r => r.json()).then(data => {
        if (data.success) {
            feedback.innerHTML = `
                <div class="bg-green-50 border border-green-200 rounded-lg p-3">
                    <p class="text-green-700 font-semibold text-sm">Payment Confirmed</p>
                    <p class="text-xs text-gray-500 mt-0.5">Transaction: ${data.transaction_id}</p>
                </div>
            `;
            refreshFinance();
            const item = document.querySelector(`.finance-order-item[data-number="${orderNumber}"]`);
            if (item) item.click();
        } else {
            feedback.innerHTML = '<span class="text-red-600">' + (data.message || 'Payment failed') + '</span>';
        }
    }).catch(() => {
        feedback.innerHTML = '<span class="text-red-600">Payment confirmation failed</span>';
    });
}

refreshFinance();
setInterval(refreshFinance, 5000);
</script>

@endsection
