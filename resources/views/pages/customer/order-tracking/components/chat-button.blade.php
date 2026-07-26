<div class="fixed bottom-6 right-6 z-50 flex flex-col items-end gap-3" style="font-family: 'Outfit', sans-serif;">

    <div id="chatModal" class="w-80 sm:w-88 bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-gray-200 dark:border-gray-700" style="display:none;height:420px!important;overflow:hidden!important;flex-direction:column!important">
        <div class="bg-blue-900 px-4 py-3 flex items-center justify-between" style="flex-shrink:0!important">
            <div class="flex items-center gap-2">
                <div class="w-7 h-7 rounded-full bg-blue-600 flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M12 8a4 4 0 0 1 4 4c0 1.5-.8 2.8-2 3.5V17a1 1 0 0 1-1 1h-2a1 1 0 0 1-1-1v-1.5c-1.2-.7-2-2-2-3.5a4 4 0 0 1 4-4z"/>
                        <circle cx="12" cy="4" r="1.5"/>
                    </svg>
                </div>
                <span class="text-sm font-semibold text-white">Customer Support</span>
            </div>
            <button type="button" onclick="toggleChatWidget()" class="text-blue-200 hover:text-white transition-colors p-0.5">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
                </svg>
            </button>
        </div>

        <div id="chatMessages" class="p-4 space-y-3" style="flex:1!important;overflow-y:auto!important;min-height:0!important">
            <div class="flex items-start gap-2.5">
                <div class="w-7 h-7 rounded-full bg-blue-900 flex items-center justify-center shrink-0 mt-0.5">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                    </svg>
                </div>
                <div class="bg-gray-100 dark:bg-gray-700 rounded-2xl rounded-tl-sm px-3.5 py-2.5 max-w-[85%]">
                    <p class="text-xs text-gray-800 dark:text-gray-200 leading-relaxed">
                        Hello! How can I help you with your order?
                    </p>
                </div>
            </div>

            <div id="chatMenuArea" class="pl-9 space-y-2">
                <button type="button" onclick="sendChatMessage('refund')" class="w-full flex items-center gap-2.5 bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-xl px-3.5 py-2.5 hover:border-blue-300 dark:hover:border-blue-500 hover:shadow-sm transition-all text-left group">
                    <span style="font-size:15px;line-height:1">🔄</span>
                    <span class="text-xs font-medium text-gray-700 dark:text-gray-300 group-hover:text-gray-900 dark:group-hover:text-white">Refund / Return Problem</span>
                </button>
                <button type="button" onclick="sendChatMessage('support')" class="w-full flex items-center gap-2.5 bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-xl px-3.5 py-2.5 hover:border-blue-300 dark:hover:border-blue-500 hover:shadow-sm transition-all text-left group">
                    <span style="font-size:15px;line-height:1">📞</span>
                    <span class="text-xs font-medium text-gray-700 dark:text-gray-300 group-hover:text-gray-900 dark:group-hover:text-white">Talk to Support</span>
                </button>
            </div>
        </div>

        <div class="px-4 py-2 border-t border-gray-100 dark:border-gray-700 text-center" style="flex-shrink:0!important">
            <span class="text-[10px] text-gray-400 dark:text-gray-500">ERP Customer Service Management</span>
        </div>
    </div>

    <button type="button" onclick="toggleChatWidget()" id="chatFab" class="w-12 h-12 rounded-full bg-blue-700 hover:bg-blue-800 text-white shadow-lg flex items-center justify-center transition-all" aria-label="Open support chat">
        <svg id="chatFabIcon" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"/>
        </svg>
    </button>

</div>