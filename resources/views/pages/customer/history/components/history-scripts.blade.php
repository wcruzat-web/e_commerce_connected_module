<script>
document.getElementById('search-input')?.addEventListener('keyup', function () {
    const query = this.value.trim().toLowerCase();
    let visible = 0;
    document.querySelectorAll('.order-card').forEach(card => {
        const show = !query || card.dataset.search.includes(query);
        card.style.display = show ? '' : 'none';
        if (show) visible++;
    });
    document.getElementById('no-orders').classList.toggle('hidden', visible !== 0);
});
</script>
