{{--
    ERP MODULE: Admin Dashboard
    COMPONENT: Dashboard JavaScript
    DESCRIPTION: Frontend-only stubs for notifications, export, sync logs, sign out.
    TODO: Wire to API endpoints
--}}

<script>
    var chartTooltip = null;

    function showChartTooltip(e, el) {
        if (!chartTooltip) {
            chartTooltip = document.createElement('div');
            chartTooltip.style.cssText = 'position:fixed;background:#1e293b;color:#fff;font-size:12px;padding:8px 14px;border-radius:8px;pointer-events:none;z-index:9999;font-family:Outfit,sans-serif;box-shadow:0 4px 12px rgba(0,0,0,0.15);line-height:1.4';
            document.body.appendChild(chartTooltip);
        }
        var parts = el.getAttribute('data-tooltip').split('|');
        chartTooltip.innerHTML = '<div style="font-weight:600;margin-bottom:2px">' + parts[0] + '</div><div style="color:#06b6d4;font-weight:700">' + parts[1] + '</div>';
        chartTooltip.style.display = 'block';
        moveChartTooltip(e);
    }

    function moveChartTooltip(e) {
        if (chartTooltip) {
            var x = e.clientX + 14;
            var y = e.clientY - 10;
            if (x + 140 > window.innerWidth) x = e.clientX - 140;
            if (y < 0) y = e.clientY + 20;
            chartTooltip.style.left = x + 'px';
            chartTooltip.style.top = y + 'px';
        }
    }

    function hideChartTooltip() {
        if (chartTooltip) chartTooltip.style.display = 'none';
    }

    let exportToastTimer = null;
    function exportReport() {
        window.open('{{ route('admin.dashboard.print') }}', '_blank');

        const toast = document.getElementById('exportToast');
        toast.classList.remove('hidden');

        clearTimeout(exportToastTimer);
        exportToastTimer = setTimeout(() => {
            toast.classList.add('hidden');
        }, 3500);
    }

    function viewSyncLogs() {
        // TODO: navigate to GET /admin/erp/sync-logs
        console.log('Viewing sync logs...');
    }

</script>
