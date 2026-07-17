{{--
    ERP MODULE: Checkout — Shipping & Contact Details (Checkout Page)
    COMPONENT: Checkout Page JavaScript
    DESCRIPTION: Shipping method radio card visual toggle.
    ToDo: Wire applyVoucher() to POST /cart/voucher when coupon system is built
--}}

<script>
    function applyVoucher() {
        const code = document.getElementById('voucherInput')?.value.trim();
        if (!code) return;
        // ToDo: POST /cart/voucher with { code }
        console.log('Voucher applied:', code);
    }
</script>
