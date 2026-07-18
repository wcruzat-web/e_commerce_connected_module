<div id="orderModal" class="hidden fixed inset-0 z-50">
    <div class="absolute inset-0 bg-black/30" onclick="closeOrderModal()"></div>
    <div class="absolute right-0 top-0 h-full w-full max-w-lg bg-white shadow-2xl overflow-y-auto max-lg:max-w-full">
        <div class="p-6">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-lg font-bold text-gray-900">Order Details</h2>
                <button type="button" onclick="closeOrderModal()" class="w-8 h-8 rounded-lg border border-gray-200 flex items-center justify-center text-gray-500 hover:bg-gray-50 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="18" y1="6" x2="6" y2="18"></line>
                        <line x1="6" y1="6" x2="18" y2="18"></line>
                    </svg>
                </button>
            </div>

            <div id="modalLoading" class="text-center py-12 text-gray-400">Loading...</div>

            <div id="modalContent" class="hidden">
                <div class="mb-6">
                    <p class="text-xs text-gray-400 mb-1">Recipient (Checkout Details)</p>
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-gray-200 shrink-0"></div>
                        <div>
                            <p class="text-sm font-semibold text-gray-900" id="modalRecipientName"></p>
                            <p class="text-xs text-gray-400" id="modalRecipientEmail"></p>
                            <p class="text-xs text-gray-400" id="modalRecipientPhone"></p>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4 mb-6">
                    <div>
                        <p class="text-xs text-gray-400 mb-0.5">Order Number</p>
                        <p class="text-sm font-medium text-gray-900" id="modalOrderNumber"></p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 mb-0.5">Date</p>
                        <p class="text-sm font-medium text-gray-900" id="modalOrderDate"></p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 mb-0.5">Status</p>
                        <span id="modalStatusBadge" class="status-badge text-[11px] font-medium px-2.5 py-1 rounded-full"></span>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 mb-0.5">Payment</p>
                        <p class="text-sm font-medium text-gray-900" id="modalPaymentInfo"></p>
                    </div>
                </div>

                <div class="mb-6">
                    <p class="text-xs text-gray-400 mb-1">Shipping Address</p>
                    <p class="text-sm text-gray-700" id="modalShippingAddress"></p>
                </div>

                <div class="mb-6">
                    <p class="text-xs text-gray-400 mb-1">Notes</p>
                    <p class="text-sm text-gray-700" id="modalNotes">—</p>
                </div>

                <div class="mb-6">
                    <p class="text-xs text-gray-400 mb-2" id="modalItemsLabel">Items</p>
                    <div class="divide-y divide-gray-100 border border-gray-200 rounded-xl" id="modalItemsList"></div>
                </div>

                <div class="flex items-center justify-between border-t border-gray-200 pt-4 mb-8">
                    <p class="text-sm font-semibold text-gray-900">Total</p>
                    <p class="text-lg font-bold text-gray-900" id="modalTotal"></p>
                </div>

                <div class="border-t border-gray-200 pt-6 space-y-4">
                    <h3 class="text-sm font-bold text-gray-900">Actions</h3>

                    <div id="paymentActionBlock" class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">Payment Status</span>
                        <button type="button" id="markPaidBtn" onclick="markAsPaid()" class="text-sm font-medium px-4 py-2 rounded-lg bg-green-100 text-green-700 hover:bg-green-200 transition-colors">
                            Mark as Paid
                        </button>
                        <span id="paidBadge" class="hidden text-sm font-medium px-3 py-1.5 rounded-full bg-green-100 text-green-700">Paid</span>
                    </div>

                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">Fulfillment Status</span>
                        <select id="fulfillmentSelect" name="status" class="text-sm border border-gray-200 rounded-lg px-3 py-2 text-gray-600 focus:outline-none focus:ring-2 focus:ring-cyan-200">
                            <option value="pending">Pending</option>
                            <option value="processing">Processing</option>
                            <option value="shipped">Shipped</option>
                            <option value="in_transit">In Transit</option>
                            <option value="out_for_delivery">Out for Delivery</option>
                            <option value="delivered">Delivered</option>
                            <option value="cancelled">Cancelled</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    var currentOrderId = null;

    function openOrderModal(orderId) {
        currentOrderId = orderId;
        var modal = document.getElementById('orderModal');
        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
        loadOrderDetails(orderId);
    }

    function closeOrderModal() {
        document.getElementById('orderModal').classList.add('hidden');
        document.body.style.overflow = '';
        document.getElementById('modalContent').classList.add('hidden');
        document.getElementById('modalLoading').classList.remove('hidden');
        currentOrderId = null;
        if (typeof reloadOrders === 'function') reloadOrders();
    }

    function getCSRFToken() {
        var meta = document.querySelector('meta[name="csrf-token"]');
        return meta ? meta.getAttribute('content') : '';
    }

    function loadOrderDetails(orderId) {
        var loading = document.getElementById('modalLoading');
        var content = document.getElementById('modalContent');
        loading.classList.remove('hidden');
        content.classList.add('hidden');

        fetch('/admin/orders/' + orderId)
            .then(function (r) { return r.json(); })
            .then(function (order) {
                loading.classList.add('hidden');
                content.classList.remove('hidden');

                document.getElementById('modalRecipientName').textContent = order.shipping_name || '';
                document.getElementById('modalRecipientEmail').textContent = order.shipping_email || '';
                document.getElementById('modalRecipientPhone').textContent = order.shipping_phone ? 'Phone: ' + order.shipping_phone : '';
                document.getElementById('modalOrderNumber').textContent = order.order_number;
                document.getElementById('modalOrderDate').textContent = order.created_at ? new Date(order.created_at).toLocaleDateString() : '';

                var badge = document.getElementById('modalStatusBadge');
                var status = order.status || 'pending';
                var statusColors = { pending: 'bg-gray-100 text-gray-600', processing: 'bg-amber-100 text-amber-600', shipped: 'bg-blue-100 text-blue-700', in_transit: 'bg-purple-100 text-purple-700', out_for_delivery: 'bg-orange-100 text-orange-600', delivered: 'bg-green-100 text-green-700', cancelled: 'bg-red-100 text-red-600' };
                badge.className = 'status-badge text-[11px] font-medium px-2.5 py-1 rounded-full ' + (statusColors[status] || 'bg-gray-100 text-gray-600');
                badge.textContent = status.charAt(0).toUpperCase() + status.slice(1);

                var paymentInfo = order.payment_status === 'paid' ? 'Paid' : 'Pending';
                if (order.payment_method) paymentInfo += ' via ' + order.payment_method.charAt(0).toUpperCase() + order.payment_method.slice(1);
                document.getElementById('modalPaymentInfo').textContent = paymentInfo;
                document.getElementById('modalShippingAddress').textContent = order.shipping_address || '';
                document.getElementById('modalNotes').textContent = order.notes || '—';

                var itemsList = document.getElementById('modalItemsList');
                itemsList.innerHTML = '';
                if (order.items && order.items.length) {
                    document.getElementById('modalItemsLabel').textContent = 'Items (' + order.items.length + ')';
                    order.items.forEach(function (item) {
                        var name = item.product ? item.product.name : 'Product #' + item.product_id;
                        var div = document.createElement('div');
                        div.className = 'flex items-center justify-between px-4 py-3';
                        div.innerHTML = '<div><p class="text-sm font-medium text-gray-900">' + name + '</p><p class="text-xs text-gray-400">Qty: ' + item.quantity + '</p></div><p class="text-sm font-semibold text-gray-900">₱' + parseFloat(item.subtotal).toLocaleString('en', {minimumFractionDigits: 2}) + '</p>';
                        itemsList.appendChild(div);
                    });
                } else {
                    itemsList.innerHTML = '<div class="px-4 py-3 text-sm text-gray-400">No items</div>';
                }

                document.getElementById('modalTotal').textContent = '₱' + parseFloat(order.grand_total).toLocaleString('en', {minimumFractionDigits: 2});

                var isPaid = order.payment_status === 'paid';
                var markBtn = document.getElementById('markPaidBtn');
                var paidBadge = document.getElementById('paidBadge');
                if (isPaid) {
                    markBtn.classList.add('hidden');
                    paidBadge.classList.remove('hidden');
                } else {
                    markBtn.classList.remove('hidden');
                    paidBadge.classList.add('hidden');
                }

                var fulfillmentSelect = document.getElementById('fulfillmentSelect');
                fulfillmentSelect.disabled = !isPaid;
                fulfillmentSelect.classList.toggle('opacity-50', !isPaid);
                fulfillmentSelect.classList.toggle('cursor-not-allowed', !isPaid);
                fulfillmentSelect.value = status;
            })
            .catch(function () {
                loading.textContent = 'Failed to load order details.';
            });
    }

    function markAsPaid() {
        if (!currentOrderId) return;
        var btn = document.getElementById('markPaidBtn');
        btn.disabled = true;
        btn.textContent = 'Processing...';

        fetch('/admin/orders/' + currentOrderId + '/payment', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': getCSRFToken() },
            body: JSON.stringify({ payment_status: 'paid' })
        })
        .then(function (r) { return r.json(); })
        .then(function (data) {
            if (data.success) {
                btn.classList.add('hidden');
                document.getElementById('paidBadge').classList.remove('hidden');
                document.getElementById('modalPaymentInfo').textContent = 'Paid';

                var badge = document.getElementById('modalStatusBadge');
                badge.className = 'status-badge text-[11px] font-medium px-2.5 py-1 rounded-full bg-amber-100 text-amber-600';
                badge.textContent = 'Processing';

                var fulfillmentSelect = document.getElementById('fulfillmentSelect');
                fulfillmentSelect.disabled = false;
                fulfillmentSelect.classList.remove('opacity-50', 'cursor-not-allowed');
                fulfillmentSelect.value = 'processing';

                if (typeof reloadOrders === 'function') reloadOrders();
            } else {
                btn.disabled = false;
                btn.textContent = 'Mark as Paid';
            }
        })
        .catch(function () {
            btn.disabled = false;
            btn.textContent = 'Mark as Paid';
        });
    }

    document.addEventListener('change', function (e) {
        if (e.target.id === 'fulfillmentSelect' && currentOrderId) {
            fetch('/admin/orders/' + currentOrderId + '/status', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': getCSRFToken() },
                body: JSON.stringify({ status: e.target.value })
            })
            .then(function () {
                if (typeof reloadOrders === 'function') reloadOrders();
            });
        }
    });
</script>
