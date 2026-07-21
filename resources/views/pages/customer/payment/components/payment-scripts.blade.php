{{-- CRUZAT — payment-scripts: saved method selection, form toggle, validation (ERPV0.2.7, ERPV0.2.12) --}}

<script>
    function selectPaymentMethod(method, fillData) {
        document.querySelectorAll('.payment-method-btn').forEach(btn => {
            if (btn.dataset.method === method) {
                btn.classList.remove('border-gray-200', 'text-gray-500');
                btn.classList.add('border-cyan-500', 'text-cyan-500');
            } else {
                btn.classList.remove('border-cyan-500', 'text-cyan-500');
                btn.classList.add('border-gray-200', 'text-gray-500');
            }
        });

        document.getElementById('paymentMethod').value = method;

        document.getElementById('cardFields').classList.toggle('hidden', method === 'gcash');
        document.getElementById('gcashFields').classList.toggle('hidden', method !== 'gcash');

        if (fillData) {
            if (method === 'gcash') {
                document.getElementById('gcashName').value = fillData.account || '';
                document.getElementById('gcashNumber').value = fillData.number || '';
            } else {
                document.getElementById('cardholderName').value = fillData.account || '';
                var cn = document.getElementById('cardNumber');
                cn.value = fillData.number || '';
                cn.dispatchEvent(new Event('input'));
                var ex = document.getElementById('expiryDate');
                ex.value = fillData.expiry || '';
                ex.dispatchEvent(new Event('input'));
                document.getElementById('cvv').value = fillData.cvv || '';
            }
        }
    }

    function selectSavedPayment(card) {
        document.querySelectorAll('.payment-method-card').forEach(function (c) {
            c.classList.remove('border-cyan-500', 'bg-cyan-50/40');
            c.classList.add('border-gray-200');
            var radio = c.querySelector('input[type="radio"]');
            if (radio) radio.checked = false;
            var outer = c.querySelector('.rounded-full.border-2');
            if (outer) { outer.classList.remove('border-cyan-500'); outer.classList.add('border-gray-300'); }
            var dot = c.querySelector('.w-2\\.5.h-2\\.5');
            if (dot) dot.classList.remove('bg-cyan-500');
        });
        card.classList.remove('border-gray-200');
        card.classList.add('border-cyan-500', 'bg-cyan-50/40');
        var radio = card.querySelector('input[type="radio"]');
        if (radio) radio.checked = true;
        var outer = card.querySelector('.rounded-full.border-2');
        if (outer) { outer.classList.remove('border-gray-300'); outer.classList.add('border-cyan-500'); }
        var dot = card.querySelector('.w-2\\.5.h-2\\.5');
        if (dot) dot.classList.add('bg-cyan-500');

        var type = card.dataset.type;
        var fillData = {
            account: card.dataset.account,
            number: card.dataset.number,
            expiry: card.dataset.expiry,
            cvv: card.dataset.cvv,
        };
        selectPaymentMethod(type, fillData);
    }

    function luhnCheck(cardNumber) {
        const digits = cardNumber.replace(/\D/g, '');
        if (digits.length < 13) return false;
        let sum = 0, alternate = false;
        for (let i = digits.length - 1; i >= 0; i--) {
            let n = parseInt(digits[i], 10);
            if (alternate) { n *= 2; if (n > 9) n -= 9; }
            sum += n;
            alternate = !alternate;
        }
        return sum % 10 === 0;
    }

    function isValidExpiry(expiry) {
        const m = expiry.trim().match(/^(\d{2})\/(\d{2})$/);
        if (!m) return false;
        const month = parseInt(m[1], 10), year = parseInt(m[2], 10) + 2000;
        if (month < 1 || month > 12) return false;
        return new Date(year, month) > new Date();
    }

    function isValidCVV(cvv) {
        return /^\d{3,4}$/.test(cvv);
    }

    document.addEventListener('DOMContentLoaded', function () {
        @if(session('payment_error'))
            toastNotify('error', '{{ session('payment_error') }}');
        @endif

        @if(isset($summary) && $summary->voucherStatus === 'valid')
            showVoucherApplied(@json($summary));
        @endif

        @if(isset($summary) && $summary->voucherStatus && $summary->voucherStatus !== 'valid' && $summary->voucherMessage)
            toastNotify('error', @json($summary->voucherMessage));
        @endif

        document.querySelectorAll('.payment-method-card').forEach(function (card) {
            card.addEventListener('click', function () { selectSavedPayment(this); });
        });

        var addBtn = document.getElementById('addPaymentBtn');
        if (addBtn) {
            addBtn.addEventListener('click', function () {
                document.getElementById('manualPaymentSection').classList.remove('hidden');
                this.classList.add('hidden');
                document.getElementById('cardholderName').value = '';
                document.getElementById('cardNumber').value = '';
                document.getElementById('expiryDate').value = '';
                document.getElementById('cvv').value = '';
                document.getElementById('gcashName').value = '';
                document.getElementById('gcashNumber').value = '';
            });
        }

        // Auto-format card number: space every 4 digits
        document.getElementById('cardNumber').addEventListener('input', function () {
            var raw = this.value.replace(/\D/g, '').slice(0, 16);
            this.value = raw.replace(/(\d{4})(?=\d)/g, '$1 ');
        });

        // Auto-format expiry: add slash after MM
        document.getElementById('expiryDate').addEventListener('input', function () {
            var raw = this.value.replace(/\D/g, '').slice(0, 4);
            if (raw.length >= 3) {
                this.value = raw.slice(0, 2) + '/' + raw.slice(2);
            } else if (raw.length === 2 && this.value.length === 3 && this.value[2] === '/') {
                // let the slash stay when user types MM/
            } else {
                this.value = raw;
            }
        });

        setInterval(async function() {
            try {
                const r = await fetch('{{ route("cart.summary") }}');
                if (r.ok) updateSummary(await r.json());
            } catch {}
        }, 300);
    });

    function savedPaymentExists(rawNumber, excludeSelected) {
        const clean = rawNumber.replace(/\D/g, '');
        return Array.from(document.querySelectorAll('.payment-method-card')).some(function (card) {
            if (excludeSelected && card.querySelector('input[type="radio"]')?.checked) return false;
            var cardClean = (card.dataset.number || '').replace(/\D/g, '');
            return cardClean.length > 0 && cardClean === clean;
        });
    }

    document.getElementById('placeOrderBtn').addEventListener('click', function () {
        const method = document.getElementById('paymentMethod').value;
        const form = document.getElementById('paymentForm');

        if (method === 'gcash') {
            const name = document.getElementById('gcashName').value.trim();
            const number = document.getElementById('gcashNumber').value.trim();
            if (!name) return toastNotify('error', 'Please enter the GCash name.');
            if (!/^\d{10}$/.test(number)) return toastNotify('error', 'GCash number must be exactly 10 digits.');
            if (savedPaymentExists(number, true)) return toastNotify('error', 'Payment already exists.');
            form.submit();
            return;
        }

        const name = document.getElementById('cardholderName').value.trim();
        const cardNumber = document.getElementById('cardNumber').value.trim();
        const expiry = document.getElementById('expiryDate').value.trim();
        const cvv = document.getElementById('cvv').value.trim();

        if (!name) return toastNotify('error', 'Please enter the cardholder name.');
        if (!luhnCheck(cardNumber.replace(/\s/g, ''))) return toastNotify('error', 'Invalid card number.');
        if (savedPaymentExists(cardNumber, true)) return toastNotify('error', 'Payment already exists.');
        if (!isValidExpiry(expiry)) return toastNotify('error', 'Invalid or expired date.');
        if (!isValidCVV(cvv)) return toastNotify('error', 'Invalid CVV.');

        form.submit();
    });

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

        formEl?.classList.remove('hidden');
        appliedEl?.classList.add('hidden');
        if (codeEl) codeEl.textContent = '';
        if (labelEl) labelEl.textContent = '';
        if (savingsEl) {
            savingsEl.textContent = '';
            savingsEl.classList.add('hidden');
        }

        const input = document.getElementById('voucherInput');
        if (input) input.value = '';
        document.getElementById('voucherInput').value = '';
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
</script>
