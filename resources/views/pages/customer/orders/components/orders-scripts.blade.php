<script>
function filterOrders(group, btn) {
    document.querySelectorAll('.tab-btn').forEach(b => {
        b.classList.remove('border-sky-500', 'text-sky-600');
        b.classList.add('border-transparent');
    });
    btn.classList.add('border-sky-500', 'text-sky-600');
    btn.classList.remove('border-transparent');

    let visible = 0;
    let isProcessing = group === 'processing';
    let isDelivered = group === 'delivered';

    document.querySelectorAll('.order-card').forEach(card => {
        const status = card.dataset.status;
        let match;

        if (group === 'all') {
            match = true;
        } else if (isProcessing) {
            match = ['pending','processing','shipped','in_transit','out_for_delivery'].includes(status);
        } else if (isDelivered) {
            match = status === 'delivered';
        }

        card.style.display = match ? '' : 'none';
        if (match) visible++;
    });

    document.getElementById('no-orders').classList.toggle('hidden', visible !== 0);
}
</script>
