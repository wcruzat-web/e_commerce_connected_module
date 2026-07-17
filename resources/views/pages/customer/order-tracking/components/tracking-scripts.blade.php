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
</script>
