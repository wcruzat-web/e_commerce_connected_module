<div id="wishlist-footer" class="flex justify-between items-center mt-10 {{ $items->count() === 0 ? 'hidden' : '' }}">

        <p class="font-semibold text-gray-600">

            {{ $items->count() }} Items

        </p>

        <div class="flex gap-4">

            <button
                class="js-remove-selected border border-red-400 text-red-500 px-5 py-2 rounded hover:bg-red-50 transition">
                <span data-i18n="wishlist.removeSel">Remove Selected</span>
            </button>

            <button
                class="js-move-selected bg-sky-500 hover:bg-sky-600 text-white px-5 py-2 rounded transition">
                <span data-i18n="wishlist.moveSel">Move Selected to Cart</span>
            </button>

        </div>

    </div>

    <div id="wishlist-empty" class="hidden border-2 border-dashed border-sky-300 rounded-xl p-10 text-center text-gray-400 mt-10">
        Your wishlist is empty.
    </div>
