{{--
    ERP MODULE: Shopping Cart (Cart Page)
    COMPONENT: Cart Page JavaScript
    DESCRIPTION: Quantity stepper updates hidden input then submits PATCH form.
                 Voucher is a placeholder stub for future coupon system.
    ToDo: Replace form submits with AJAX (fetch) for live PATCH/DELETE without page reload
    ToDo: Wire applyVoucher() to POST /cart/voucher when coupon system is built
--}}

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
        row.querySelector('.line-total').textContent = '$' + (price * qty).toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 });

        row.querySelector('.qty-input').value = qty;

        clearTimeout(window._qtyTimer);
        window._qtyTimer = setTimeout(() => {
            row.querySelector('.qty-input').closest('form').submit();
        }, 600);
    }

    function applyVoucher() {
        const code = document.getElementById('voucherInput').value.trim();
        if (!code) return;
        // ToDo: POST /cart/voucher with { code }
        console.log('Voucher applied:', code);
    }
</script>
