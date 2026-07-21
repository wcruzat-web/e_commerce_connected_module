{{-- HAINZ — index-scripts: shop listing JS for filters, wishlist, add-to-cart AJAX (ERPV1.1, ERPV1.3) --}}
<script>
function toastNotify(type, message) {
    var container = document.getElementById('toastContainer');
    if (!container) return;
    var colors = { success: 'bg-green-600', error: 'bg-red-600', info: 'bg-blue-600', warning: 'bg-yellow-500 text-black' };
    var toast = document.createElement('div');
    toast.className = (colors[type] || 'bg-gray-700') + ' text-white text-xs px-4 py-2.5 rounded-lg shadow-lg opacity-0 transition-opacity duration-300';
    toast.textContent = message;
    container.appendChild(toast);
    requestAnimationFrame(function() { toast.style.opacity = '1'; });
    setTimeout(function() {
        toast.style.opacity = '0';
        setTimeout(function() { toast.remove(); }, 300);
    }, 3000);
}
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
                toastNotify('success', 'Added to wishlist');
            } else {
                btn.classList.remove('text-red-500');
                btn.classList.add('text-gray-400', 'hover:text-red-500');
                svg.setAttribute('fill', 'none');
                btn.setAttribute('title', 'Add to Wishlist');
                toastNotify('info', 'Removed from wishlist');
            }
        })
        .catch(function() { toastNotify('error', 'Network error'); });
    });
});
document.querySelectorAll('.js-cart-form').forEach(function(form) {
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        var formData = new FormData(form);
        fetch(form.action, { method: 'POST', body: formData, headers: { 'X-Requested-With': 'XMLHttpRequest' } })
        .then(function(r) { return r.json(); })
        .then(function(data) {
            if (data.ok) {
                var badge = document.querySelector('.js-cart-badge');
                if (badge) { badge.textContent = data.cartCount; badge.classList.remove('hidden'); }
                toastNotify('success', data.message || 'Item added to cart');
            } else {
                toastNotify('error', data.message || 'Failed to add item');
            }
        })
        .catch(function() { toastNotify('error', 'Network error'); });
    });
});
</script>
