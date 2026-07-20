<div class="mt-6 space-y-4 stagger">

        @forelse($items as $item)

        <div class="border rounded-xl p-5 flex items-center justify-between gap-4 shadow-sm hover-lift animate-fade-up"
             data-wish-row data-id="{{ $item->wishlist_item_id }}">

            <div class="flex items-center gap-6 flex-1 min-w-0">

                <input
                    type="checkbox"
                    class="w-6 h-6 js-wish-check accent-sky-500">

                <img src="{{ $item->product_image ?: 'https://picsum.photos/seed/p'.$item->product_id.'/200/200' }}" alt="{{ $item->product_name }}"
                    class="w-16 h-16 rounded-lg object-cover border">

                <div class="min-w-0">

                    <h3 class="font-semibold truncate">
                        {{ $item->product_name }}
                    </h3>

                    <p class="text-sm text-gray-500 truncate">
                        {{ $item->product_description }}
                    </p>

                    <span class="text-sm font-semibold {{ $item->in_stock ? 'text-green-600' : 'text-red-600' }}">
                        {{ $item->in_stock ? 'In Stock' : 'Out of Stock' }}
                    </span>

                </div>

            </div>

            <div class="flex items-center gap-6 shrink-0">

                <div>

                    <h3 class="font-bold">
                        ₱{{ number_format($item->unit_price, 2) }}
                    </h3>

                    <span class="text-xs bg-sky-100 text-sky-500 px-2 rounded">
                        Saved
                    </span>

                </div>

                @if($item->in_stock)
                <form method="POST" action="{{ route('wishlist.moveToCart', $item->wishlist_item_id) }}" class="js-wishlist-move">
                    @csrf
                    <button type="submit" title="Move to Cart"
                        class="w-9 h-9 flex items-center justify-center rounded border border-sky-500 text-sky-500 hover:bg-sky-500 hover:text-white transition">
                        <img src="https://cdn.jsdelivr.net/npm/lucide-static@0.460.0/icons/shopping-cart.svg"
                            class="w-5 h-5" alt="Move to Cart">
                    </button>
                </form>

                <form method="POST" action="{{ route('wishlist.destroy', $item->wishlist_item_id) }}" class="js-wishlist-delete">
                    @csrf
                    @method('DELETE')
                    <button type="submit" title="Remove"
                        class="w-9 h-9 flex items-center justify-center rounded border border-red-200 text-red-500 hover:bg-red-50 transition">
                        <img src="https://cdn.jsdelivr.net/npm/lucide-static@0.460.0/icons/trash-2.svg"
                            class="w-5 h-5" alt="Remove">
                    </button>
                </form>
                @else
                <span class="text-sm font-semibold text-gray-400 border border-gray-200 rounded-full px-3 py-1">
                    Wait for Stock
                </span>

                <form method="POST" action="{{ route('wishlist.destroy', $item->wishlist_item_id) }}" class="js-wishlist-delete">
                    @csrf
                    @method('DELETE')
                    <button type="submit" title="Remove"
                        class="w-9 h-9 flex items-center justify-center rounded border border-red-200 text-red-500 hover:bg-red-50 transition">
                        <img src="https://cdn.jsdelivr.net/npm/lucide-static@0.460.0/icons/trash-2.svg"
                            class="w-5 h-5" alt="Remove">
                    </button>
                </form>
                @endif

            </div>

        </div>

        @empty

        <div class="border-2 border-dashed border-sky-300 rounded-xl p-10 text-center text-gray-400">
            Your wishlist is empty.
        </div>

        @endforelse

    </div>
