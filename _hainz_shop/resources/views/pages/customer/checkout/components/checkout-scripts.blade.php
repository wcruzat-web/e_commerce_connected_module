{{--
    ERP MODULE: Checkout — Shipping & Contact Details (Checkout Page)
    COMPONENT: Checkout Page JavaScript
    DESCRIPTION: Shipping method radio card visual toggle.
    ToDo: Wire applyVoucher() to POST /cart/voucher when coupon system is built
--}}

<script>
    function selectShippingOption(radio) {
        document.querySelectorAll('.shipping-option').forEach(label => {
            label.classList.remove('border-cyan-500', 'bg-cyan-50/40');
            label.classList.add('border-gray-200');
        });

        const selectedLabel = radio.closest('.shipping-option');
        selectedLabel.classList.remove('border-gray-200');
        selectedLabel.classList.add('border-cyan-500', 'bg-cyan-50/40');
    }

    function applyVoucher() {
        const code = document.getElementById('voucherInput')?.value.trim();
        if (!code) return;
        // ToDo: POST /cart/voucher with { code }
        console.log('Voucher applied:', code);
    }
</script>
