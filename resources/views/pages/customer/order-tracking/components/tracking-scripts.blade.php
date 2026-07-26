{{-- CRUZAT — tracking-scripts: 3-second polling, DOM swap, receipt refresh (ERPV0.2.2, ERPV0.2.4) --}}
<style>
    .dark #chatModal .bg-gray-100 { background-color: #374151; }
    .dark #chatModal .text-gray-800 { color: #e5e7eb; }
    .dark #chatModal .text-gray-500 { color: #9ca3af; }
</style>
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
        var modal = document.getElementById('chatModal');
        modal.style.display = modal.style.display === 'none' || modal.style.display === '' ? 'flex' : 'none';
        document.getElementById('chatFab').classList.toggle('ring-2');
        document.getElementById('chatFab').classList.toggle('ring-blue-300');
    }

    function sendChatMessage(topic) {
        var container = document.getElementById('chatMessages');
        var menuArea = document.getElementById('chatMenuArea');
        var userLabel = topic === 'refund' ? '🔄 Refund / Return Problem' : '📞 Talk to Support';

        var userDiv = document.createElement('div');
        userDiv.className = 'flex justify-end';
        userDiv.innerHTML = '<div class="bg-blue-700 text-white text-xs rounded-2xl rounded-br-sm px-3.5 py-2.5 max-w-[80%] leading-relaxed">' + userLabel + '</div>';
        container.insertBefore(userDiv, menuArea);

        var botDiv = document.createElement('div');
        botDiv.className = 'flex items-start gap-2.5';

        if (topic === 'refund') {
            botDiv.innerHTML =
                '<div class="w-7 h-7 rounded-full bg-blue-900 flex items-center justify-center shrink-0 mt-0.5">' +
                    '<svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">' +
                        '<rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/>' +
                    '</svg>' +
                '</div>' +
                '<div class="bg-gray-100 rounded-2xl rounded-tl-sm px-3.5 py-3 max-w-[85%]">' +
                    '<p class="text-xs text-gray-800 leading-relaxed mb-2.5">For refund or return concerns, please visit our Customer Service Management portal:</p>' +
                    '<a href="#" class="inline-flex items-center gap-1.5 bg-blue-900 text-white text-[11px] font-semibold px-3.5 py-2 rounded-lg hover:bg-blue-800 transition-colors">' +
                        'ERP Customer Service Management' +
                        '<svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>' +
                    '</a>' +
                    '<button type="button" onclick="resetChat()" class="block mt-2 text-[11px] text-blue-600 hover:text-blue-800 font-medium">← Back to menu</button>' +
                '</div>';
        } else {
            botDiv.innerHTML =
                '<div class="w-7 h-7 rounded-full bg-blue-900 flex items-center justify-center shrink-0 mt-0.5">' +
                    '<svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">' +
                        '<rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/>' +
                    '</svg>' +
                '</div>' +
                '<div class="bg-gray-100 rounded-2xl rounded-tl-sm px-3.5 py-3 max-w-[85%]">' +
                    '<p class="text-xs text-gray-800 font-medium mb-1">📞 (02) 8123-4567</p>' +
                    '<p class="text-xs text-gray-800 font-medium mb-1">📧 support@business-name.com</p>' +
                    '<p class="text-xs text-gray-500 mb-2.5">🕐 Mon-Fri, 9AM - 6PM</p>' +
                    '<a href="#" class="inline-flex items-center gap-1.5 bg-blue-900 text-white text-[11px] font-semibold px-3.5 py-2 rounded-lg hover:bg-blue-800 transition-colors">' +
                        'ERP Customer Service Management' +
                        '<svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>' +
                    '</a>' +
                    '<button type="button" onclick="resetChat()" class="block mt-2 text-[11px] text-blue-600 hover:text-blue-800 font-medium">← Back to menu</button>' +
                '</div>';
        }

        container.insertBefore(botDiv, menuArea);
        container.scrollTop = container.scrollHeight;
    }

    function resetChat() {
        var container = document.getElementById('chatMessages');
        var greeting = container.querySelector('.flex.items-start');
        var menuArea = document.getElementById('chatMenuArea');
        container.innerHTML = '';
        container.appendChild(greeting);
        container.appendChild(menuArea);
        container.scrollTop = 0;
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
