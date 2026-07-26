{{-- Generic confirmation modal (triggered via form.js-confirm-delete or js-confirm) --}}
<div id="confirm-modal" class="fixed inset-0 z-50 flex items-center justify-center p-4 opacity-0 pointer-events-none transition-all duration-200">
    <div id="confirm-backdrop" class="absolute inset-0 bg-black/40 backdrop-blur-sm"></div>

    <div id="confirm-modal-card" class="relative bg-white rounded-2xl shadow-2xl w-full max-w-sm p-6 transform scale-95 transition-all duration-200">
        <div class="flex items-center gap-3 mb-4">
            <div id="confirm-icon" class="w-10 h-10 rounded-full flex items-center justify-center shrink-0 bg-red-100">
                <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4.5c-.77-.833-2.694-.833-3.464 0L3.34 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                </svg>
            </div>
            <h3 id="confirm-title" class="text-lg font-semibold text-gray-800">Confirm Action</h3>
        </div>

        <p id="confirm-message" class="text-gray-600 text-sm mb-6 leading-relaxed">
            Are you sure you want to proceed?
        </p>

        <div class="flex justify-end gap-3">
            <button type="button" id="confirm-cancel" class="px-5 py-2.5 rounded-xl border border-gray-200 text-gray-600 hover:bg-gray-50 hover:border-gray-300 transition font-medium text-sm">
                Cancel
            </button>
            <button type="button" id="confirm-ok" class="px-5 py-2.5 rounded-xl text-white transition font-medium text-sm shadow-sm bg-red-500 hover:bg-red-600">
                Delete
            </button>
        </div>
    </div>
</div>
