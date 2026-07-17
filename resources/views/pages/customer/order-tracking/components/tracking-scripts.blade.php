<script>
    function toastNotify(type, message) {
        var container = document.getElementById('toastContainer');
        if (!container) return;
        var colors = { success: 'bg-green-500', error: 'bg-red-500', info: 'bg-blue-500' };
        var toast = document.createElement('div');
        toast.className = (colors[type] || 'bg-gray-500') + ' text-white text-sm px-5 py-3 rounded-xl shadow-lg pointer-events-auto animate-slide-in';
        toast.textContent = message;
        container.appendChild(toast);
        setTimeout(function () { toast.remove(); }, 3000);
    }

    document.addEventListener('DOMContentLoaded', function () {
        @if(session('error'))
            toastNotify('error', '{{ session('error') }}');
        @endif
    });

    function toggleTimelineDetails() {
        const collapsed = document.getElementById('timelineCollapsed');
        const expanded = document.getElementById('timelineExpanded');
        const label = document.getElementById('timelineToggleLabel');
        const icon = document.getElementById('timelineToggleIcon');

        const isExpanded = !expanded.classList.contains('hidden');

        if (isExpanded) {
            expanded.classList.add('hidden');
            collapsed.classList.remove('hidden');
            label.textContent = 'Show more details';
            icon.classList.remove('rotate-180');
        } else {
            collapsed.classList.add('hidden');
            expanded.classList.remove('hidden');
            label.textContent = 'Show less details';
            icon.classList.add('rotate-180');
        }
    }

    function copyOrderId() {
        const orderId = document.getElementById('orderIdText').textContent.trim();
        if (navigator.clipboard) {
            navigator.clipboard.writeText(orderId).then(function () {
                toastNotify('success', 'Order ID copied to clipboard');
            });
        }
    }

    function toggleChatWidget() {
        console.log('Support chat toggled');
    }

    @if(isset($order))
    // [AGNER] Live polling — fetches timeline/banner/meta every 3s
    (function () {
        var orderId = {{ $order->order_id }};
        var pollInterval = 3000;

        setInterval(function () {
            fetch('/tracking/' + orderId + '/poll')
                .then(function (res) { return res.json(); })
                .then(function (data) {
                    var timelineContainer = document.getElementById('timelineContainer');
                    var bannerContainer = document.getElementById('statusBannerContainer');
                    var metaContainer = document.getElementById('shipmentMetaContainer');
                    var receivedContainer = document.getElementById('receivedContainer');
                    if (!timelineContainer || !bannerContainer || !metaContainer) return;

                    var wasExpanded = false;
                    var expandedEl = document.getElementById('timelineExpanded');
                    if (expandedEl) {
                        wasExpanded = !expandedEl.classList.contains('hidden');
                    }

                    timelineContainer.innerHTML = data.timeline_html;
                    bannerContainer.innerHTML = data.banner_html;
                    metaContainer.innerHTML = data.meta_html;
                    if (receivedContainer) receivedContainer.innerHTML = data.received_html;

                    if (wasExpanded) {
                        var newExpanded = document.getElementById('timelineExpanded');
                        var newCollapsed = document.getElementById('timelineCollapsed');
                        var label = document.getElementById('timelineToggleLabel');
                        var icon = document.getElementById('timelineToggleIcon');
                        if (newExpanded && newCollapsed) {
                            newCollapsed.classList.add('hidden');
                            newExpanded.classList.remove('hidden');
                            if (label) label.textContent = 'Show less details';
                            if (icon) icon.classList.add('rotate-180');
                        }
                    }
                })
                .catch(function () {});
        }, pollInterval);
    })();
    @endif
</script>
