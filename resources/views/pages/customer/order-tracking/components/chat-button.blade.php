{{--
    ==================================================================
    ERP MODULE: Real-Time Order Synchronization (Tracking Page)

    COMPONENT: Floating Chat / Support Button

    DESCRIPTION:
    Fixed-position button in the bottom-right corner
    for opening a support chat widget.

    ==================================================================

    TODO (Backend Integration):
    - Hook into live-chat widget or support modal
    - Wire onclick to open the actual chat system

    ==================================================================
--}}

<button
    type="button"
    onclick="toggleChatWidget()"
    class="fixed bottom-6 right-6 w-12 h-12 rounded-full bg-blue-700 hover:bg-blue-800 text-white shadow-lg flex items-center justify-center transition-colors"
    aria-label="Open support chat"
>
    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"></path>
    </svg>
</button>
