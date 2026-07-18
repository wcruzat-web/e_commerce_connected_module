{{--
    ==================================================================
    ERP MODULE: Real-Time Order Synchronization (Tracking Page)

    COMPONENT: Support Shortcuts

    DESCRIPTION:
    Displays Call Support and Email Support cards
    for customer assistance.

    ==================================================================

    TODO (Backend Integration):
    - Replace href="#" with real support routes
    - Replace phone number with configurable value
    - Replace email with configurable value
    - Future: link to live chat widget or support modal

    ==================================================================
--}}

<div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
    <a href="#" class="flex items-center gap-3 bg-white border border-gray-200 rounded-2xl px-5 py-4 hover:border-cyan-300 transition-colors">
        <div class="w-9 h-9 rounded-full bg-blue-900 flex items-center justify-center text-white shrink-0">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 22 16.92z"></path>
            </svg>
        </div>
        <div>
            <p class="text-sm font-semibold text-gray-900">Call Support</p>
            <p class="text-xs text-gray-400">(02) 8123-4567 - Mon-Fri, 9AM-6PM</p>
        </div>
    </a>
    <a href="#" class="flex items-center gap-3 bg-white border border-gray-200 rounded-2xl px-5 py-4 hover:border-cyan-300 transition-colors">
        <div class="w-9 h-9 rounded-full bg-cyan-500 flex items-center justify-center text-white shrink-0">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
                <polyline points="22,6 12,13 2,6"></polyline>
            </svg>
        </div>
        <div>
            <p class="text-sm font-semibold text-gray-900">Email Support</p>
            <p class="text-xs text-gray-400">support@business-name.com</p>
        </div>
    </a>
</div>
