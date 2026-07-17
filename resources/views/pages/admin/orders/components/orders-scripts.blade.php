<script>
    var ordersSearchInput = document.getElementById('ordersSearch');
    var ordersStatusFilter = document.getElementById('ordersStatusFilter');
    var ordersPaymentFilter = document.getElementById('ordersPaymentFilter');
    var ordersWrapper = document.getElementById('ordersTableWrapper');
    var ordersDebounceTimer = null;

    window.reloadOrders = function (url) {
            var params = new URLSearchParams();
            if (ordersSearchInput && ordersSearchInput.value) params.set('search', ordersSearchInput.value);
            if (ordersStatusFilter && ordersStatusFilter.value) params.set('status', ordersStatusFilter.value);
            if (ordersPaymentFilter && ordersPaymentFilter.value) params.set('payment_status', ordersPaymentFilter.value);
            params.set('partial', '1');
            var qs = params.toString();
            var fetchUrl = '/admin/orders?' + qs;

            if (ordersWrapper) {
                ordersWrapper.innerHTML = '<div class="text-center py-12 text-gray-400">Loading...</div>';
            }

            fetch(fetchUrl)
                .then(function (r) { return r.text(); })
                .then(function (html) {
                    if (ordersWrapper) {
                        ordersWrapper.innerHTML = html;
                    }
                    history.replaceState(null, '', '/admin/orders?' + qs.replace('&partial=1', '').replace('partial=1', ''));
                });
        }

        if (ordersSearchInput) {
            ordersSearchInput.addEventListener('input', function () {
                clearTimeout(ordersDebounceTimer);
                ordersDebounceTimer = setTimeout(function () { window.reloadOrders(); }, 300);
            });
        }

        if (ordersStatusFilter) {
            ordersStatusFilter.addEventListener('change', function () { window.reloadOrders(); });
        }

        if (ordersPaymentFilter) {
            ordersPaymentFilter.addEventListener('change', function () { window.reloadOrders(); });
        }

        if (ordersWrapper) {
            ordersWrapper.addEventListener('click', function (e) {
                var link = e.target.closest('a[href]:not([href="#"])');
                if (link && link.href.indexOf('/admin/orders') !== -1) {
                    e.preventDefault();
                    var url = new URL(link.href);
                    url.searchParams.set('partial', '1');
                    fetch(url.toString())
                        .then(function (r) { return r.text(); })
                        .then(function (html) {
                            if (ordersWrapper) {
                                ordersWrapper.innerHTML = html;
                            }
                            history.replaceState(null, '', link.href);
                        });
                }
            });
        }
</script>
