{{-- CRUZAT — cart-scripts: AJAX add/remove, quantity update, voucher apply/remove, toast notifications --}}

<script>
    function changeQty(itemId, delta) {
        const row = document.querySelector(`.cart-item[data-item-id="${itemId}"]`);
        if (!row) return;

        const qtyEl = row.querySelector('.qty-value');
        let qty = parseInt(qtyEl.textContent, 10) + delta;
        const maxQty = parseInt(row.dataset.maxQty, 10);
        if (qty < 1) qty = 1;
        if (qty > maxQty) qty = maxQty;
        qtyEl.textContent = qty;

        const price = parseFloat(row.dataset.price);
        row.querySelector('.line-total').textContent = '₱' + (price * qty).toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 });

        row.querySelector('.qty-input').value = qty;

        clearTimeout(window._qtyTimer);
        window._qtyTimer = setTimeout(() => {
            row.querySelector('.qty-input').closest('form').submit();
        }, 600);
    }

    async function applyVoucher() {
        const input = document.getElementById('voucherInput');
        const msg = document.getElementById('voucherMsg');
        const appliedMsg = document.getElementById('voucherAppliedMsg');
        const code = input?.value.trim().toUpperCase();
        if (!code) return;

        const form = input.closest('form');
        const btn = form?.querySelector('button[type="submit"]');
        if (!btn) return;

        const controller = new AbortController();
        const timeoutId = setTimeout(() => controller.abort(), 12000);

        btn.disabled = true;
        btn.textContent = 'Applying...';
        if (msg) { msg.textContent = ''; msg.className = 'mt-2 text-xs'; }
        if (appliedMsg) { appliedMsg.textContent = ''; }

        try {
            const response = await fetch('{{ route("cart.voucher.apply") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                },
                body: JSON.stringify({ code }),
                signal: controller.signal,
            });

            const raw = await response.text();
            let data = {};

            try {
                data = raw ? JSON.parse(raw) : {};
            } catch {
                throw new Error(raw || 'Unexpected voucher response.');
            }

            if (!response.ok || !data.success) {
                throw new Error(data.message || 'Invalid voucher');
            }

            toastNotify('success', data.message || 'Voucher applied successfully!');
            updateSummary(data.summary);
            showVoucherApplied(data.summary);
            input.value = '';
        } catch (error) {
            const message = error?.name === 'AbortError'
                ? 'Voucher request timed out. Please try again.'
                : (error?.message || 'Network error');
            toastNotify('error', message);
            if (msg) { msg.textContent = message; msg.className = 'mt-2 text-xs text-red-600'; }
        } finally {
            clearTimeout(timeoutId);
            btn.disabled = false;
            btn.textContent = 'Apply';
        }
    }

    function removeVoucher() {
        const msg = document.getElementById('voucherMsg');
        const appliedMsg = document.getElementById('voucherAppliedMsg');
        if (msg) { msg.textContent = ''; msg.className = 'mt-2 text-xs'; }
        if (appliedMsg) { appliedMsg.textContent = ''; }

        fetch('{{ route("cart.voucher.remove") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            },
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                toastNotify('success', data.message);
                updateSummary(data.summary);
                showVoucherForm();
            } else {
                toastNotify('error', 'Failed to remove voucher');
            }
        })
        .catch(() => toastNotify('error', 'Network error'));
    }

    function updateSummary(s) {
        const itemsEl = document.getElementById('summaryItemCount');
        if (itemsEl) itemsEl.textContent = s.itemsCount;

        const subEl = document.getElementById('summarySubtotal');
        if (subEl) subEl.textContent = '₱' + s.subtotal.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 });

        const taxEl = document.getElementById('summaryTax');
        if (taxEl) taxEl.textContent = '₱' + s.tax.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 });

        const totalEl = document.getElementById('summaryGrandTotal');
        if (totalEl) totalEl.textContent = '₱' + s.grandTotal.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 });

        const discountRow = document.getElementById('discountRow');
        const discountEl = document.getElementById('summaryDiscount');
        const discountLabelEl = document.getElementById('summaryDiscountLabel');
        if (discountRow && discountEl && discountLabelEl) {
            if (s.discount > 0) {
                discountRow.classList.remove('hidden');
                discountLabelEl.textContent = ' (' + s.couponLabel + ')';
                discountEl.textContent = '-₱' + s.discount.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 });
            } else {
                discountRow.classList.add('hidden');
            }
        }

        if (s.shippingFee === 0) {
            const shippingValueEl = document.querySelector('#summaryShipping .font-medium');
            if (shippingValueEl) shippingValueEl.textContent = '₱0.00';
            document.querySelector('#summaryShipping .text-green-600')?.remove();
            const freeSpan = document.createElement('span');
            freeSpan.className = 'text-[11px] font-bold text-green-600';
            freeSpan.textContent = s.isFreeShipping ? 'FREE (Voucher)' : 'FREE';
            const shippingCol = document.querySelector('#summaryShipping .flex-col');
            if (shippingCol && !shippingCol.querySelector('.text-green-600')) {
                shippingCol.appendChild(freeSpan);
            }
        }
    }

    function showVoucherApplied(s) {
        const formEl = document.getElementById('voucherForm');
        const appliedEl = document.getElementById('voucherApplied');
        const codeEl = document.getElementById('voucherCode');
        const labelEl = document.getElementById('voucherLabel');

        if (formEl) formEl.classList.add('hidden');
        if (appliedEl) appliedEl.classList.remove('hidden');
        if (codeEl) codeEl.textContent = s.couponCode || '';
        if (labelEl) labelEl.textContent = s.couponLabel || '';

        const savingsEl = document.getElementById('voucherSavings');
        if (savingsEl) {
            if (s.discount > 0) {
                savingsEl.textContent = 'You saved ₱' + s.discount.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 });
                savingsEl.classList.remove('hidden');
            } else {
                savingsEl.textContent = '';
                savingsEl.classList.add('hidden');
            }
        }
    }

    function showVoucherForm() {
        const formEl = document.getElementById('voucherForm');
        const appliedEl = document.getElementById('voucherApplied');
        const codeEl = document.getElementById('voucherCode');
        const labelEl = document.getElementById('voucherLabel');
        const savingsEl = document.getElementById('voucherSavings');

        if (formEl) formEl.classList.remove('hidden');
        if (appliedEl) appliedEl.classList.add('hidden');
        if (codeEl) codeEl.textContent = '';
        if (labelEl) labelEl.textContent = '';
        if (savingsEl) {
            savingsEl.textContent = '';
            savingsEl.classList.add('hidden');
        }

        const input = document.getElementById('voucherInput');
        if (input) input.value = '';
    }

    function toastNotify(type, message) {
        const container = document.getElementById('toastContainer');
        if (!container) return;
        const colors = { success: 'bg-green-500', error: 'bg-red-500', info: 'bg-blue-500' };
        const toast = document.createElement('div');
        toast.className = `${colors[type] || 'bg-gray-700'} text-white text-xs px-4 py-2.5 rounded-lg shadow-lg opacity-0 transition-opacity duration-300`;
        toast.textContent = message;
        container.appendChild(toast);
        requestAnimationFrame(() => toast.style.opacity = '1');
        setTimeout(() => {
            toast.style.opacity = '0';
            setTimeout(() => toast.remove(), 300);
        }, 3000);
    }

    // Initialize voucher state on page load
    document.addEventListener('DOMContentLoaded', function() {
        @if(isset($summary) && $summary->voucherStatus && $summary->voucherStatus !== 'valid' && $summary->voucherMessage)
            toastNotify('error', @json($summary->voucherMessage));
        @endif

        @if(isset($summary) && $summary->couponCode)
            showVoucherApplied({
                couponCode: '{{ $summary->couponCode }}',
                couponLabel: '{{ $summary->couponLabel }}',
                discount: {{ $summary->discount }},
                isFreeShipping: {{ $summary->isFreeShipping ? 'true' : 'false' }},
                shippingFee: {{ $summary->shippingFee }},
            });
        @endif
    });
</script>
