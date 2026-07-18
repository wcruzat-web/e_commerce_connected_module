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
</script>
