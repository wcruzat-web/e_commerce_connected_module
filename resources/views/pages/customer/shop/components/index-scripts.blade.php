{{-- HAINZ — index-scripts: shop listing JS for filters, wishlist, add-to-cart AJAX (ERPV1.1, ERPV1.3) --}}
<script>
document.querySelectorAll('.js-wish-form').forEach(function(form) {
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        var btn = form.querySelector('.js-wish-btn');
        var svg = form.querySelector('.js-wish-svg');
        var formData = new FormData(form);
        fetch(form.action, { method: 'POST', body: formData, headers: { 'X-Requested-With': 'XMLHttpRequest' } })
        .then(function(r) { return r.json(); })
        .then(function(data) {
            if (data.added) {
                btn.classList.remove('text-gray-400', 'hover:text-red-500');
                btn.classList.add('text-red-500');
                svg.setAttribute('fill', 'currentColor');
                btn.setAttribute('title', 'Remove from Wishlist');
            } else {
                btn.classList.remove('text-red-500');
                btn.classList.add('text-gray-400', 'hover:text-red-500');
                svg.setAttribute('fill', 'none');
                btn.setAttribute('title', 'Add to Wishlist');
            }
        });
    });
});
document.querySelectorAll('.js-cart-form').forEach(function(form) {
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        var formData = new FormData(form);
        fetch(form.action, { method: 'POST', body: formData, headers: { 'X-Requested-With': 'XMLHttpRequest' } })
        .then(function(r) { return r.json(); })
        .then(function(data) {
            var badge = document.querySelector('.js-cart-badge');
            if (badge) { badge.textContent = data.cartCount; badge.classList.remove('hidden'); }
        });
    });
});
</script>
