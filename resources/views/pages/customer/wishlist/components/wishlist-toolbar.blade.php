<div class="flex justify-between items-center mt-8">

        <div class="flex gap-10 font-semibold">

            <button class="border-b-2 border-sky-500 pb-1">
                <span data-i18n="wishlist.allItems">All Items</span> ({{ $items->count() }})
            </button>

            <button class="text-gray-500">
                On Sale (0)
            </button>

        </div>

        <div class="flex items-center gap-3">

            <span class="font-semibold">
                Sort by:
            </span>

            <select
                class="border rounded px-3 py-1">

                <option>
                    Recently Added
                </option>

            </select>

        </div>

    </div>
