@extends('layouts.external-sales')

@section('title', 'Sales and Customer Support Management')

@section('content')

<div class="grid grid-cols-4 gap-4 mb-5">
    <div class="stat-card">
        <div class="stat-label">Paid Orders</div>
        <div class="stat-value" id="sales-count">0</div>
    </div>
    <div class="stat-card">
        <div class="stat-label">Needs Fulfillment</div>
        <div class="stat-value" id="sales-pending-count" style="color: #7c3aed">0</div>
    </div>
    <div class="stat-card">
        <div class="stat-label">Delivered</div>
        <div class="stat-value" id="sales-delivered-count" style="color: #16a34a">0</div>
    </div>
    <div class="stat-card">
        <div class="stat-label">Fulfillment Rate</div>
        <div class="stat-value" id="sales-rate" style="color: #6366f1">0%</div>
    </div>
</div>

<div style="display: flex; gap: 1.25rem;">
    <div class="card" style="flex: 1; min-width: 0;">
        <div class="card-header">
            <h3>Fulfillment Queue</h3>
        </div>
        <div class="card-body">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Order #</th>
                        <th>Customer</th>
                        <th style="text-align: right">Amount</th>
                        <th style="text-align: center">Status</th>
                    </tr>
                </thead>
                <tbody id="sales-order-list"></tbody>
            </table>
            <div id="sales-empty" class="text-center py-10 text-xs text-[#94a3b8]" style="display:none">No orders awaiting fulfillment</div>
        </div>
    </div>

    <div class="card" style="width: 420px; flex-shrink: 0;" id="sales-detail-panel">
        <div class="card-header">
            <h3 id="sales-detail-title">Order Fulfillment</h3>
        </div>
        <div id="sales-order-details" class="text-xs text-[#94a3b8] text-center py-12">Select an order to manage fulfillment</div>
    </div>
</div>

<script>
const SALES_TOKEN = '{{ config("external-modules.sales.api_key") }}';
const SALES_API = '/api/external/sales';

const statusColors = {
    pending: '#f1f5f9,#475569',
    processing: '#fef3c7,#d97706',
    shipped: '#dbeafe,#2563eb',
    in_transit: '#ede9fe,#7c3aed',
    out_for_delivery: '#ffedd5,#ea580c',
    delivered: '#dcfce7,#16a34a',
    cancelled: '#fee2e2,#dc2626'
};

function renderDetail(order) {
    const sc = (statusColors[order.status] || '#f1f5f9,#475569').split(',');

    let itemsHtml = '';
    if (order.items && order.items.length) {
        itemsHtml = order.items.map(item =>
            `<tr>
                <td style="padding:0.5rem 0.75rem;border-bottom:1px solid #f1f5f9;font-size:0.8125rem">
                    <div style="font-weight:500;color:#0f172a">${item.product_name || 'Item'}</div>
                    <div style="font-size:0.6875rem;color:#94a3b8">Qty: ${item.quantity}</div>
                </td>
                <td style="padding:0.5rem 0.75rem;border-bottom:1px solid #f1f5f9;text-align:right;font-size:0.8125rem;font-weight:600;color:#0f172a">₱${Number(item.subtotal).toLocaleString('en', {minimumFractionDigits:2})}</td>
            </tr>`
        ).join('');
    }

    document.getElementById('sales-detail-title').textContent = order.order_number;
    document.getElementById('sales-order-details').innerHTML = `
        <div style="padding:1rem">
            <div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:1rem">
                <div>
                    <div style="font-size:0.875rem;font-weight:700;color:#0f172a">${order.order_number}</div>
                    <div style="font-size:0.6875rem;color:#94a3b8;margin-top:2px">${order.created_at ? new Date(order.created_at).toLocaleDateString('en-US',{year:'numeric',month:'short',day:'numeric'}) : ''}</div>
                </div>
                <span class="badge" style="background:${sc[0]};color:${sc[1]}">${order.status.replace(/_/g,' ')}</span>
            </div>

            <div style="background:#f8fafc;border:1px solid #e2e8f0;border-radius:0.375rem;padding:0.75rem;margin-bottom:1rem">
                <div style="font-size:0.6875rem;color:#64748b;margin-bottom:2px">Customer</div>
                <div style="font-size:0.8125rem;font-weight:600;color:#0f172a">${order.shipping_name || 'N/A'}</div>
                <div style="font-size:0.6875rem;color:#64748b">${order.shipping_email || ''}</div>
                ${order.shipping_phone ? `<div style="font-size:0.6875rem;color:#64748b">${order.shipping_phone}</div>` : ''}
            </div>

            <div style="margin-bottom:0.75rem">
                <div style="font-size:0.6875rem;font-weight:600;color:#64748b;text-transform:uppercase;letter-spacing:0.05em;margin-bottom:0.375rem">Order Items</div>
                <table style="width:100%;border-collapse:collapse;border:1px solid #e2e8f0;border-radius:0.375rem">
                    ${itemsHtml || '<tr><td style="padding:0.75rem;font-size:0.75rem;color:#94a3b8">No items</td></tr>'}
                </table>
            </div>

            <div style="display:flex;justify-content:space-between;align-items:center;border-top:1px solid #e2e8f0;padding-top:0.75rem;margin-bottom:0.75rem">
                <span style="font-size:0.8125rem;font-weight:600;color:#0f172a">Total Amount</span>
                <span style="font-size:1.125rem;font-weight:700;color:#0f172a">₱${Number(order.grand_total).toLocaleString('en',{minimumFractionDigits:2})}</span>
            </div>

            <div style="background:#f8fafc;border:1px solid #e2e8f0;border-radius:0.375rem;padding:0.625rem 0.75rem;margin-bottom:1rem">
                <div style="font-size:0.6875rem;color:#64748b">Payment Reference</div>
                <div style="font-size:0.8125rem;font-weight:500;color:#0f172a;margin-top:1px">${order.finance_transaction_id || 'N/A'}</div>
            </div>

            <div style="border-top:1px solid #e2e8f0;padding-top:0.875rem">
                <div style="font-size:0.8125rem;font-weight:600;color:#0f172a;margin-bottom:0.5rem">Update Fulfillment Status</div>
                <div style="display:flex;gap:0.5rem">
                    <select id="sales-status-select" ${order.customer_received ? 'disabled' : ''} onchange="updateStatus('${order.order_number}', this.value)" style="flex:1;border:1px solid #d1d5db;border-radius:0.375rem;padding:0.5rem 0.625rem;font-size:0.8125rem;color:#374151;background:${order.customer_received ? '#f3f4f6' : 'white'};outline:none">
                        <option value="processing" ${order.status === 'processing' ? 'selected' : ''}>Processing</option>
                        <option value="shipped" ${order.status === 'shipped' ? 'selected' : ''}>Shipped</option>
                        <option value="in_transit" ${order.status === 'in_transit' ? 'selected' : ''}>In Transit</option>
                        <option value="out_for_delivery" ${order.status === 'out_for_delivery' ? 'selected' : ''}>Out for Delivery</option>
                        <option value="delivered" ${order.status === 'delivered' ? 'selected' : ''}>Delivered</option>
                        <option value="cancelled" ${order.status === 'cancelled' ? 'selected' : ''}>Cancelled</option>
                    </select>
                </div>
                <div id="sales-feedback" style="margin-top:0.5rem;font-size:0.75rem"></div>
            </div>
        </div>
    `;
}

function buildTableHtml(orders, selectedId) {
    if (!orders.length) {
        document.getElementById('sales-empty').style.display = 'block';
        return '';
    }
    document.getElementById('sales-empty').style.display = 'none';
    return orders.map(o => {
        const sel = o.order_id == selectedId ? ' selected' : '';
        const sc = (statusColors[o.status] || '#f1f5f9,#475569').split(',');
        return `<tr class="sales-order-item${sel}" data-id="${o.order_id}" data-number="${o.order_number}" style="cursor:pointer">
            <td style="font-weight:500;color:#0f172a">${o.order_number}</td>
            <td style="color:#475569">${o.customer || 'N/A'}</td>
            <td style="text-align:right;font-weight:600;color:#0f172a">₱${Number(o.total || o.grand_total).toLocaleString()}</td>
            <td style="text-align:center"><span class="badge" style="background:${sc[0]};color:${sc[1]}">${o.status.replace(/_/g,' ')}</span></td>
        </tr>`;
    }).join('');
}

function refreshSales() {
    fetch(`${SALES_API}/orders`, { headers: { 'Authorization': 'Bearer ' + SALES_TOKEN } }).then(r => r.json()).then(data => {
        const list = document.getElementById('sales-order-list');
        const current = list.querySelector('.sales-order-item.selected');
        const selId = current ? current.dataset.id : null;

        list.innerHTML = buildTableHtml(data.orders, selId);

        const total = data.orders.length;
        const delivered = data.orders.filter(o => o.status === 'delivered').length;
        const needsFulfillment = total - delivered;

        document.getElementById('sales-count').textContent = total;
        document.getElementById('sales-pending-count').textContent = needsFulfillment;
        document.getElementById('sales-delivered-count').textContent = delivered;
        document.getElementById('sales-rate').textContent = total ? (delivered / total * 100).toFixed(0) + '%' : '0%';
    });
}

document.getElementById('sales-order-list').addEventListener('click', function (e) {
    const item = e.target.closest('.sales-order-item');
    if (!item) return;
    document.querySelectorAll('.sales-order-item').forEach(i => i.classList.remove('selected'));
    item.classList.add('selected');
    fetch(`${SALES_API}/orders/${item.dataset.number}`, { headers: { 'Authorization': 'Bearer ' + SALES_TOKEN } })
        .then(r => r.json())
        .then(o => renderDetail(o));
});

function updateStatus(orderNumber, status) {
    const feedback = document.getElementById('sales-feedback');
    feedback.innerHTML = '<span style="color:#64748b">Updating status...</span>';
    fetch('/api/external/sales/orders/' + orderNumber, {
        method: 'PATCH',
        headers: {
            'Content-Type': 'application/json',
            'Authorization': 'Bearer {{ config("external-modules.sales.api_key") }}'
        },
        body: JSON.stringify({ status: status })
    }).then(r => r.json()).then(data => {
        if (data.success) {
            feedback.innerHTML = '<span style="color:#16a34a;font-weight:500">Status updated to "' + status.replace(/_/g,' ').replace(/\b\w/g,c=>c.toUpperCase()) + '"</span>';
            refreshSales();
        } else {
            feedback.innerHTML = '<span style="color:#dc2626">' + data.message + '</span>';
        }
    }).catch(() => {
        feedback.innerHTML = '<span style="color:#dc2626">Failed to update status</span>';
    });
}

refreshSales();
setInterval(refreshSales, 5000);
</script>

@endsection
