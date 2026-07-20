{{-- HAINZ — shop show page JS: review form, tab navigation, wishlist toggle (ERPV1.1) --}}
<script>
function switchTab(tab) {
    document.querySelectorAll('.tab-content').forEach(el => el.classList.add('hidden'));
    document.getElementById('tab-' + tab).classList.remove('hidden');
    document.querySelectorAll('[id$="-btn"]').forEach(el => {
        el.classList.remove('border-blue-600', 'text-blue-600', 'bg-white', 'font-bold');
        el.classList.add('border-transparent', 'text-gray-500', 'font-semibold');
    });
    var btn = document.getElementById('tab-' + tab + '-btn');
    btn.classList.remove('border-transparent', 'text-gray-500', 'font-semibold');
    btn.classList.add('border-blue-600', 'text-blue-600', 'bg-white', 'font-bold');
}
function setRating(val) {
    document.getElementById('review-rating').value = val;
    document.querySelectorAll('.star-btn').forEach(function(btn) {
        var star = parseInt(btn.getAttribute('data-star'));
        var svg = btn.querySelector('svg');
        if (star <= val) {
            svg.classList.remove('text-gray-300');
            svg.classList.add('text-yellow-400');
        } else {
            svg.classList.remove('text-yellow-400');
            svg.classList.add('text-gray-300');
        }
    });
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
            } else {
                btn.classList.remove('text-red-500');
                btn.classList.add('text-gray-400', 'hover:text-red-500');
                svg.setAttribute('fill', 'none');
                btn.setAttribute('title', 'Add to Wishlist');
            }
        });
    });
});
</script>
