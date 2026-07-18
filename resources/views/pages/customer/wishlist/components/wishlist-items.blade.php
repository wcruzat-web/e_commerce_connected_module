<div class="mt-6 space-y-4 stagger">

        @forelse($items as $item)

        <div class="border rounded-xl p-5 flex items-center justify-between shadow-sm hover-lift animate-fade-up"
             data-wish-row data-id="{{ $item->wishlist_item_id }}">

            <div class="flex items-center gap-6">

                <input
                    type="checkbox"
                    class="w-6 h-6 js-wish-check accent-sky-500">

                <img src="{{ $item->product_image ?: 'https://picsum.photos/seed/p'.$item->product_id.'/200/200' }}" alt="{{ $item->product_name }}"
                    class="w-16 h-16 rounded-lg object-cover border">

                <div>

                    <h3 class="font-semibold">
                        {{ $item->product_name }}
                    </h3>

                    <p class="text-sm text-gray-500">
                        {{ $item->product_description }}
                    </p>

                    <span class="text-green-600 text-sm font-semibold">
                        {{ $item->in_stock ? 'In Stock' : 'Out of Stock' }}
                    </span>

                </div>

            </div>

            <div class="flex items-center gap-6">

                <div>

                    <h3 class="font-bold">
                        ₱{{ number_format($item->unit_price, 2) }}
                    </h3>

                    <span class="text-xs bg-sky-100 text-sky-500 px-2 rounded">
                        Saved
                    </span>

                </div>

                <form method="POST" action="{{ route('wishlist.moveToCart', $item->wishlist_item_id) }}" class="js-wishlist-move">
                    @csrf
                    <button type="submit" title="Move to Cart"
                        class="border border-sky-500 text-sky-500 hover:bg-sky-500 hover:text-white px-4 py-2 rounded text-sm flex items-center gap-2 transition">
                        <img src="https://cdn.jsdelivr.net/npm/lucide-static@0.460.0/icons/shopping-cart.svg"
                            class="w-4 h-4" alt=""> Move to Cart
                    </button>
                </form>

                <form method="POST" action="{{ route('wishlist.destroy', $item->wishlist_item_id) }}" class="js-wishlist-delete">
                    @csrf
                    @method('DELETE')
                    <button type="submit" title="Remove"
                        class="text-red-500 hover:text-red-700 transition">
                        <img src="https://cdn.jsdelivr.net/npm/lucide-static@0.460.0/icons/trash-2.svg"
                            class="w-5 h-5" alt="Remove">
                    </button>
                </form>

            </div>

        </div>

        @empty

        <div class="border-2 border-dashed border-sky-300 rounded-xl p-10 text-center text-gray-400">
            Your wishlist is empty.
        </div>

        @endforelse

    </div>
