@extends('layouts.external-finance')

@section('title', 'Payment Processing')

@section('content')

<div class="grid grid-cols-4 gap-5 mb-6">
    <div class="kpi-card blue">
        <div class="flex items-start justify-between">
            <div class="flex-1 min-w-0">
                <div class="kpi-label">Total Orders</div>
                <div class="kpi-value" id="finance-count">0</div>
                <div class="kpi-trend neutral mt-1">All orders in system</div>
            </div>
            <div class="kpi-chip" style="background:#eff6ff;color:#3b82f6">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="3" width="20" height="14" rx="2"/><path d="M8 21h8"/><path d="M12 17v4"/></svg>
            </div>
        </div>
    </div>
    <div class="kpi-card orange">
        <div class="flex items-start justify-between">
            <div class="flex-1 min-w-0">
                <div class="kpi-label">Pending Payment</div>
                <div class="kpi-value" id="finance-pending-count">0</div>
                <div class="kpi-trend up mt-1">Awaiting confirmation</div>
            </div>
            <div class="kpi-chip" style="background:#fffbeb;color:#f59e0b">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg>
            </div>
        </div>
    </div>
    <div class="kpi-card green">
        <div class="flex items-start justify-between">
            <div class="flex-1 min-w-0">
                <div class="kpi-label">Confirmed</div>
                <div class="kpi-value" id="finance-paid-count">0</div>
                <div class="kpi-trend up mt-1">Payment received</div>
            </div>
            <div class="kpi-chip" style="background:#ecfdf5;color:#10b981">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><path d="M22 4 12 14.01l-3-3"/></svg>
            </div>
        </div>
    </div>
    <div class="kpi-card purple">
        <div class="flex items-start justify-between">
            <div class="flex-1 min-w-0">
                <div class="kpi-label">Pending Rate</div>
                <div class="kpi-value" id="finance-rate">0%</div>
                <div class="kpi-trend neutral mt-1">Of total orders</div>
            </div>
            <div class="kpi-chip" style="background:#f5f3ff;color:#8b5cf6">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 20V10"/><path d="M18 20V4"/><path d="M6 20v-4"/></svg>
            </div>
        </div>
    </div>
</div>

<div class="panel mb-6">
    <div class="panel-body px-5 py-4">
        <div class="flex items-center gap-4">
            <div class="flex-1">
                <div class="flex items-center justify-between mb-1.5">
                    <span class="text-xs font-medium text-[#64748b]">Payment Collection Progress</span>
                    <span class="text-xs font-semibold text-[#0f172a]" id="finance-progress-text">0%</span>
                </div>
                <div class="progress-bar">
                    <div class="progress-bar-fill bg-[#10b981]" id="finance-progress-bar" style="width:0%"></div>
                </div>
            </div>
            <div class="flex items-center gap-4 text-xs text-[#94a3b8]">
                <span class="flex items-center gap-1.5"><span class="status-dot bg-[#10b981]"></span> <span id="finance-paid-count-small">0</span> paid</span>
                <span class="flex items-center gap-1.5"><span class="status-dot bg-[#f59e0b]"></span> <span id="finance-pending-count-small">0</span> pending</span>
            </div>
        </div>
    </div>
</div>

<div class="flex gap-5">
    <div class="panel flex-1 min-w-0">
        <div class="panel-header flex items-center justify-between">
            <h3>Order Registry</h3>
            <span class="text-[10px] font-medium text-[#94a3b8] tracking-wide">LIVE</span>
        </div>
        <div class="panel-body">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Order</th>
                        <th>Customer</th>
                        <th style="text-align: right">Amount</th>
                        <th style="text-align: center">Payment</th>
                    </tr>
                </thead>
                <tbody id="finance-order-list"></tbody>
            </table>
            <div id="finance-empty" class="text-center py-10 text-xs text-[#94a3b8]" style="display:none">No orders found</div>
        </div>
    </div>

    <div class="panel w-[440px] flex-shrink-0" id="finance-detail-panel">
        <div class="panel-header">
            <h3 id="finance-detail-title">Payment Processing</h3>
        </div>
        <div id="finance-order-details" class="text-xs text-[#94a3b8] text-center py-12">Select an order to process payment</div>
    </div>
</div>

<script>
const BASE = '{{ url("/external") }}';

function renderDetail(order) {
    const isPaid = order.payment_status === 'paid';

    let itemsHtml = '';
    if (order.items && order.items.length) {
        itemsHtml = order.items.map(item =>
            `<tr>
                <td style="padding:0.625rem 0;border-bottom:1px solid #f1f5f9">
                    <div style="font-size:0.8125rem;font-weight:500;color:#0f172a">${item.product_name || 'Item'}</div>
                    <div style="font-size:0.6875rem;color:#94a3b8">Qty: ${item.quantity}</div>
                </td>
                <td style="padding:0.625rem 0;border-bottom:1px solid #f1f5f9;text-align:right;font-size:0.8125rem;font-weight:600;color:#0f172a">₱${Number(item.subtotal).toLocaleString('en', {minimumFractionDigits:2})}</td>
            </tr>`
        ).join('');
    }

    document.getElementById('finance-detail-title').textContent = order.order_number;
    document.getElementById('finance-order-details').innerHTML = `
        <div class="detail-section">
            <div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:1.25rem">
                <div>
                    <div style="font-size:0.9375rem;font-weight:700;color:#0f172a">${order.order_number}</div>
                    <div style="font-size:0.6875rem;color:#94a3b8;margin-top:2px">${order.created_at ? new Date(order.created_at).toLocaleDateString('en-US',{year:'numeric',month:'short',day:'numeric'}) : ''}</div>
                </div>
                <span class="badge" style="background:#f1f5f9;color:#475569">${order.status.replace(/_/g,' ')}</span>
            </div>

            <div class="info-block" style="margin-bottom:1.25rem">
                <div class="detail-label">Customer</div>
                <div class="detail-value">${order.shipping_name || 'N/A'}</div>
                <div style="font-size:0.75rem;color:#64748b;margin-top:2px">${order.shipping_email || ''}</div>
                ${order.shipping_phone ? `<div style="font-size:0.75rem;color:#64748b;margin-top:1px">${order.shipping_phone}</div>` : ''}
            </div>

            <div style="margin-bottom:1rem">
                <div class="detail-label" style="font-weight:600;margin-bottom:0.5rem">Order Items</div>
                <table style="width:100%;border-collapse:collapse">
                    ${itemsHtml || '<tr><td style="padding:0.75rem 0;font-size:0.75rem;color:#94a3b8">No items</td></tr>'}
                </table>
            </div>

            <div style="display:flex;justify-content:space-between;align-items:center;border-top:1px solid #f1f5f9;padding-top:1rem;margin-bottom:1.25rem">
                <span class="detail-value">Total Amount</span>
                <span style="font-size:1.25rem;font-weight:700;color:#0f172a">₱${Number(order.grand_total).toLocaleString('en',{minimumFractionDigits:2})}</span>
            </div>

            <div style="border-top:1px solid #f1f5f9;padding-top:1.25rem">
                <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1rem">
                    <span class="detail-value" style="font-size:0.8125rem">Payment Status</span>
                    ${isPaid
                        ? `<span class="badge" style="background:#ecfdf5;color:#10b981">Paid</span>`
                        : `<span class="badge" style="background:#fffbeb;color:#d97706">Pending</span>`
                    }
                </div>
                ${isPaid
                    ? `<div class="info-block" style="display:flex;align-items:center;gap:0.5rem;padding:0.625rem 0.875rem">
                            <span class="status-dot bg-[#10b981]"></span>
                            <div>
                                <div style="font-size:0.6875rem;color:#94a3b8">Transaction</div>
                                <div style="font-size:0.75rem;font-weight:600;color:#0f172a;margin-top:1px">${order.finance_transaction_id || 'N/A'}</div>
                            </div>
                        </div>`
                    : `<button type="button" onclick="markAsPaid('${order.order_number}')" style="width:100%;padding:0.625rem 1rem;background:#3b82f6;color:white;border:none;border-radius:0.625rem;font-size:0.8125rem;font-weight:600;cursor:pointer;transition:all 0.15s" onmouseover="this.style.background='#2563eb'" onmouseout="this.style.background='#3b82f6'">Confirm Payment</button>`
                }
                <div id="finance-feedback" style="margin-top:0.75rem;font-size:0.75rem"></div>
            </div>
        </div>
    `;
}

function buildTableHtml(orders, selectedId) {
    if (!orders.length) {
        document.getElementById('finance-empty').style.display = 'block';
        return '';
    }
    document.getElementById('finance-empty').style.display = 'none';
    return orders.map(o => {
        const sel = o.order_id == selectedId ? ' selected' : '';
        const payClass = o.payment_status === 'paid' ? 'background:#ecfdf5;color:#10b981' : 'background:#fffbeb;color:#d97706';
        const name = o.customer ? (o.customer.first_name || (o.customer.email || 'N/A')) : 'N/A';
        return `<tr class="finance-order-item${sel}" data-id="${o.order_id}" data-number="${o.order_number}" style="cursor:pointer">
            <td style="font-weight:600;color:#0f172a">${o.order_number}</td>
            <td style="color:#475569">${name}</td>
            <td style="text-align:right;font-weight:600;color:#0f172a">₱${Number(o.grand_total).toLocaleString()}</td>
            <td style="text-align:center"><span class="badge" style="${payClass}">${o.payment_status}</span></td>
        </tr>`;
    }).join('');
}

function refreshFinance() {
    fetch(`${BASE}/finance/orders`).then(r => r.json()).then(data => {
        const list = document.getElementById('finance-order-list');
        const current = list.querySelector('.finance-order-item.selected');
        const selId = current ? current.dataset.id : null;

        list.innerHTML = buildTableHtml(data.orders, selId);

        const total = data.orders.length;
        const paid = data.orders.filter(o => o.payment_status === 'paid').length;
        const pendingPay = total - paid;
        const rate = total ? (pendingPay / total * 100) : 0;

        document.getElementById('finance-count').textContent = total;
        document.getElementById('finance-pending-count').textContent = pendingPay;
        document.getElementById('finance-paid-count').textContent = paid;
        document.getElementById('finance-rate').textContent = rate.toFixed(0) + '%';

        const pct = total ? (paid / total * 100) : 0;
        document.getElementById('finance-progress-text').textContent = pct.toFixed(0) + '%';
        document.getElementById('finance-progress-bar').style.width = pct + '%';
        document.getElementById('finance-paid-count-small').textContent = paid;
        document.getElementById('finance-pending-count-small').textContent = pendingPay;
    });
}

document.getElementById('finance-order-list').addEventListener('click', function (e) {
    const item = e.target.closest('.finance-order-item');
    if (!item) return;
    document.querySelectorAll('.finance-order-item').forEach(i => i.classList.remove('selected'));
    item.classList.add('selected');
    fetch(`${BASE}/order/${item.dataset.number}`)
        .then(r => r.json())
        .then(o => renderDetail(o));
});

function markAsPaid(orderNumber) {
    const feedback = document.getElementById('finance-feedback');
    feedback.innerHTML = '<span style="color:#64748b">Processing payment...</span>';
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
            feedback.innerHTML = '<span style="color:#10b981;font-weight:600">Payment confirmed — ' + data.transaction_id + '</span>';
            refreshFinance();
            const item = document.querySelector(`.finance-order-item[data-number="${orderNumber}"]`);
            if (item) item.click();
        } else {
            feedback.innerHTML = '<span style="color:#ef4444">' + (data.message || 'Payment failed') + '</span>';
        }
    }).catch(() => {
        feedback.innerHTML = '<span style="color:#ef4444">Payment confirmation failed</span>';
    });
}

refreshFinance();
setInterval(refreshFinance, 5000);
</script>

@endsection