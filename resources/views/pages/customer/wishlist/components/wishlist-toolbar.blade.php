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

            <form method="GET" action="{{ route('wishlist') }}">
                <select name="sort" class="border rounded px-3 py-1" onchange="this.form.submit()">
                    <option value="recent" {{ $sort === 'recent' ? 'selected' : '' }}>Recently Added</option>
                    <option value="price_asc" {{ $sort === 'price_asc' ? 'selected' : '' }}>Price: Low to High</option>
                    <option value="price_desc" {{ $sort === 'price_desc' ? 'selected' : '' }}>Price: High to Low</option>
                </select>
            </form>

        </div>

    </div>
