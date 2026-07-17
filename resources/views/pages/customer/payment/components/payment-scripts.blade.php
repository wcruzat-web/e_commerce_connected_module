{{--
    ERP MODULE: Checkout — Payment (Payment Page)
    COMPONENT: Payment Page JavaScript
    DESCRIPTION: Payment method tab switching, client-side card validation (Luhn, expiry, CVV), click handler that submits form on valid.
--}}

<script>
    function selectPaymentMethod(method) {
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
    });

    document.getElementById('placeOrderBtn').addEventListener('click', function () {
        const method = document.getElementById('paymentMethod').value;
        const form = document.getElementById('paymentForm');

        if (method === 'gcash') {
            const name = document.getElementById('gcashName').value.trim();
            const number = document.getElementById('gcashNumber').value.trim();
            if (!name) return toastNotify('error', 'Please enter the GCash name.');
            if (!/^\d{10}$/.test(number)) return toastNotify('error', 'GCash number must be exactly 10 digits.');
            form.submit();
            return;
        }

        const name = document.getElementById('cardholderName').value.trim();
        const cardNumber = document.getElementById('cardNumber').value.trim();
        const expiry = document.getElementById('expiryDate').value.trim();
        const cvv = document.getElementById('cvv').value.trim();

        if (!name) return toastNotify('error', 'Please enter the cardholder name.');
        if (!luhnCheck(cardNumber.replace(/\s/g, ''))) return toastNotify('error', 'Invalid card number.');
        if (!isValidExpiry(expiry)) return toastNotify('error', 'Invalid or expired date.');
        if (!isValidCVV(cvv)) return toastNotify('error', 'Invalid CVV.');

        form.submit();
    });

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
