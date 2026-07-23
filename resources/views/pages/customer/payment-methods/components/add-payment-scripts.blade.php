<script>
function togglePaymentFields(type) {
    document.getElementById('cardFields').classList.toggle('hidden', type !== 'Visa' && type !== 'Mastercard');
    document.getElementById('gcashFields').classList.toggle('hidden', type !== 'GCash');
}

function toastNotify(type, message) {
    var container = document.getElementById('toastContainer');
    if (!container) {
        container = document.createElement('div');
        container.id = 'toastContainer';
        container.className = 'fixed top-5 right-5 z-[9999] flex flex-col gap-2 pointer-events-none';
        document.body.appendChild(container);
    }
    var colors = { success: 'bg-green-500', error: 'bg-red-500', info: 'bg-blue-500' };
    var toast = document.createElement('div');
    toast.className = (colors[type] || 'bg-gray-500') + ' text-white text-sm px-5 py-3 rounded-xl shadow-lg pointer-events-auto animate-slide-in';
    toast.textContent = message;
    container.appendChild(toast);
    setTimeout(function () { toast.remove(); }, 3000);
}

document.querySelectorAll('#payment-method-options .payment-option').forEach(function (label) {
    label.addEventListener('click', function () {
        document.querySelectorAll('#payment-method-options .payment-option').forEach(function (el) {
            el.classList.remove('border-sky-500', 'bg-sky-50');
            el.classList.add('border-gray-200');
        });
        label.classList.remove('border-gray-200');
        label.classList.add('border-sky-500', 'bg-sky-50');

        var radio = label.querySelector('input[type="radio"]');
        if (radio) togglePaymentFields(radio.value);
    });
});

(function () {
    var checked = document.querySelector('#payment-method-options input[type="radio"]:checked');
    if (checked) togglePaymentFields(checked.value);
})();

(function () {
    var el = document.getElementById('serverErrors');
    if (el) {
        try {
            var errors = JSON.parse(el.getAttribute('data-errors'));
            errors.forEach(function (msg) { toastNotify('error', msg); });
        } catch(e) {}
    }
})();

(function () {
    var gcashInput = document.getElementById('gcashNumber');
    if (!gcashInput) return;

    function formatGcash(raw) {
        var digits = (raw || '').replace(/\D/g, '').slice(0, 10);
        var parts = [];
        if (digits.length > 0) parts.push(digits.slice(0, 3));
        if (digits.length > 3) parts.push(digits.slice(3, 6));
        if (digits.length > 6) parts.push(digits.slice(6, 10));
        return parts.join(' ');
    }

    gcashInput.addEventListener('input', function () {
        gcashInput.value = formatGcash(gcashInput.value);
    });

    var form = document.getElementById('paymentForm');
    if (form) {
        form.addEventListener('submit', function () {
            if (gcashInput.value) {
                gcashInput.value = gcashInput.value.replace(/\D/g, '');
            }
        });
    }
})();
</script>
