@extends('layouts.customer')

@section('content')

<div class="px-7 py-6">

    <div class="flex justify-between items-start">

        <div>

            <h1 class="text-4xl font-bold" data-i18n="wishlist.title">
                Wishlist
            </h1>

            <p class="text-gray-500 mt-1">
                Save your favorite items and buy them later.
            </p>

        </div>

        <button
            class="js-move-all bg-sky-500 hover:bg-sky-600 text-white px-5 py-3 rounded font-semibold flex items-center gap-2 transition hover:-translate-y-0.5">
            <img src="https://cdn.jsdelivr.net/npm/lucide-static@0.460.0/icons/shopping-cart.svg"
                class="w-4 h-4" alt=""> <span data-i18n="wishlist.moveAll">Move All to Cart</span>
        </button>

    </div>

    <!-- Filters -->

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

    <!-- Wishlist -->

    <div class="mt-6 space-y-4 stagger">

        @forelse($items as $item)

        <div class="border rounded-xl p-5 flex items-center justify-between shadow-sm hover-lift animate-fade-up"
             data-wish-row data-id="{{ $item->wishlist_item_id }}">

            <div class="flex items-center gap-6">

                <input
                    type="checkbox"
                    class="w-6 h-6 js-wish-check accent-sky-500">

                <img src="{{ $item->product_image }}" alt="{{ $item->product_name }}"
                    class="w-16 h-16 rounded-lg object-cover border"
                    onerror="this.onerror=null;this.src='https://picsum.photos/seed/p{{ $item->product_id }}/200/200';">

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

    <!-- Footer -->

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

    <!-- Empty state (shown by JS once every row is removed) -->
    <div id="wishlist-empty" class="hidden border-2 border-dashed border-sky-300 rounded-xl p-10 text-center text-gray-400 mt-10">
        Your wishlist is empty.
    </div>

</div>

@endsection
