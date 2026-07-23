<script>
// AGNER — history search now filters server-side so it works across paginated pages
let searchTimer;
document.getElementById('search-input')?.addEventListener('input', function () {
    clearTimeout(searchTimer);
    const q = this.value.trim();
    searchTimer = setTimeout(function () {
        const url = new URL(window.location.href);
        if (q) {
            url.searchParams.set('search', q);
        } else {
            url.searchParams.delete('search');
        }
        url.searchParams.delete('page');
        window.location.href = url.toString();
    }, 400);
});
</script>
