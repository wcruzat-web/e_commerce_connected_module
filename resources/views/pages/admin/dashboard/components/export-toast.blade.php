{{-- CRUZAT — export-toast: CSV export confirmation toast --}}{{--
    ERP MODULE: Admin Dashboard
    COMPONENT: Export Report Toast
    DESCRIPTION: Transient toast notification shown when export starts.
    TODO: trigger on GET /admin/reports/export response
--}}

<div id="exportToast" class="hidden fixed top-20 right-6 z-[60] bg-green-50 border border-green-300 rounded-xl shadow-lg px-4 py-3 flex items-start gap-3 max-w-xs transition-all duration-300">
    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-green-600 shrink-0 mt-0.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <circle cx="12" cy="12" r="10"></circle>
        <polyline points="9 12 11 14 15 10"></polyline>
    </svg>
    <div>
        <p class="text-sm font-semibold text-green-800">Download Started!</p>
        <p class="text-xs text-green-700">Your report download has started.</p>
    </div>
</div>
