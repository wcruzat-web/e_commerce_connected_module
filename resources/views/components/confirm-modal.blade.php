{{-- Shared animated confirmation modal (used by address delete forms) --}}
<div id="confirm-modal" class="fixed inset-0 z-50 flex items-center justify-center p-4 opacity-0 pointer-events-none transition-opacity duration-200">
    <div id="confirm-backdrop" class="absolute inset-0 bg-black/50"></div>

    <div id="confirm-modal-card" class="relative bg-white rounded-2xl shadow-xl w-full max-w-sm p-6 transform scale-95 transition-transform duration-200">
        <div class="flex items-center gap-3 mb-4">
            <div class="w-10 h-10 rounded-full bg-red-100 flex items-center justify-center shrink-0">
                <img src="https://cdn.jsdelivr.net/npm/lucide-static@0.460.0/icons/trash-2.svg" class="w-5 h-5" alt="">
            </div>
            <h3 class="text-lg font-semibold text-gray-800">Delete Address</h3>
        </div>

        <p class="text-gray-600 text-sm mb-6">
            Are you sure you want to delete this address? This action cannot be undone.
        </p>

        <div class="flex justify-end gap-3">
            <button type="button" id="confirm-cancel" class="px-4 py-2 rounded-lg border border-gray-300 text-gray-700 hover:bg-gray-50 transition">
                Cancel
            </button>
            <button type="button" id="confirm-ok" class="px-4 py-2 rounded-lg bg-red-500 text-white hover:bg-red-600 transition">
                Delete
            </button>
        </div>
    </div>
</div>
