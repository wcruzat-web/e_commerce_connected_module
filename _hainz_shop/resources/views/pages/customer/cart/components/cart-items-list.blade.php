{{--
    ERP MODULE: Shopping Cart (Cart Page)
    COMPONENT: Cart Items List
    DESCRIPTION: Displays all items in the cart with product image, brand, name,
                 SKU, stock status, quantity stepper, remove button, and line total.
    DATA SOURCE: $cart->items (CartItem model) from CartController@index
    ToDo: Wire quantity changes to PATCH /cart/{cartItem} via AJAX for live updates
    ToDo: Sync stock levels from Product model in real time
--}}

<div class="lg:col-span-2 bg-white border border-gray-200 rounded-2xl p-5 shadow-sm h-fit">
    <div class="flex items-center justify-between mb-5">
        <div class="flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-gray-700" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="9" cy="21" r="1"></circle>
                <circle cx="20" cy="21" r="1"></circle>
                <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
            </svg>
            <h2 class="text-sm font-semibold text-gray-900">Cart Items</h2>
            <span id="itemCountBadge" class="text-xs font-medium bg-blue-100 text-blue-700 px-2.5 py-0.5 rounded-full">{{ $cart->items->count() }} {{ $cart->items->count() === 1 ? 'item' : 'items' }}</span>
        </div>
        {{-- links to shop page --}}
        <a href="{{ route('products.index') }}" class="flex items-center gap-1 text-xs font-medium text-cyan-500 hover:text-cyan-600">
            Continue Shopping
            <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <line x1="5" y1="12" x2="19" y2="12"></line>
                <polyline points="12 5 19 12 12 19"></polyline>
            </svg>
        </a>
    </div>

    <div id="cartItemsList" class="divide-y divide-gray-100">
        @forelse ($cart->items as $item)
            <div class="cart-item flex items-start gap-4 py-4 {{ $loop->first ? 'pt-0' : '' }}" data-item-id="{{ $item->cart_item_id }}" data-price="{{ $item->unit_price }}" data-max-qty="{{ $item->product->stock ?? 99 }}">
                {{-- product image --}}
                <div class="w-20 h-20 rounded-lg bg-gray-100 border border-gray-200 flex items-center justify-center shrink-0 overflow-hidden">
                    @if($item->product->featured_image)
                        <img src="{{ $item->product->featured_image }}" alt="{{ $item->product->name }}" class="w-full h-full object-contain p-2">
                    @else
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-gray-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="3" y="3" width="18" height="18" rx="2"></rect>
                            <circle cx="8.5" cy="8.5" r="1.5"></circle>
                            <polyline points="21 15 16 10 5 21"></polyline>
                        </svg>
                    @endif
                </div>

                <div class="flex-1 min-w-0">
                    <div class="flex items-center gap-2 mb-1">
                        {{-- product brand tag --}}
                        <span class="text-[11px] font-medium bg-slate-100 text-slate-600 px-2 py-0.5 rounded-full">{{ $item->product->brand }}</span>
                        {{-- category tag --}}
                        <span class="text-[11px] font-medium bg-slate-100 text-slate-600 px-2 py-0.5 rounded-full">{{ $item->product->category->name ?? '' }}</span>
                    </div>
                    <p class="text-sm font-semibold text-gray-900">{{ $item->product->name }}</p>
                    <p class="text-xs text-gray-400 mt-0.5">SKU: {{ $item->product->sku }}</p>

                    <div class="mt-2">
                        {{-- stock status — uses product stock from [OTHER MODULE] Procurement/Product Master --}}
                        @php $stock = $item->product->stock ?? 0; @endphp
                        @if($stock > 5)
                            <span class="inline-flex items-center gap-1 text-[11px] font-medium bg-green-50 text-green-600 px-2 py-0.5 rounded-full">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                                    <polyline points="20 6 9 17 4 12"></polyline>
                                </svg>
                                In Stock
                            </span>
                        @elseif($stock > 0)
                            <span class="inline-flex items-center gap-1 text-[11px] font-medium bg-amber-50 text-amber-600 px-2 py-0.5 rounded-full">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path>
                                    <line x1="12" y1="9" x2="12" y2="13"></line>
                                    <line x1="12" y1="17" x2="12.01" y2="17"></line>
                                </svg>
                                Only {{ $stock }} left
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1 text-[11px] font-medium bg-red-50 text-red-600 px-2 py-0.5 rounded-full">
                                Out of Stock
                            </span>
                        @endif
                    </div>

                    <div class="flex items-center gap-4 mt-3">
                        {{-- quantity stepper with hidden form for PATCH --}}
                        <div class="flex items-center border border-gray-200 rounded-lg overflow-hidden">
                            <form method="POST" action="{{ route('cart.update', $item->cart_item_id) }}" class="flex items-center">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="quantity" class="qty-input" value="{{ $item->quantity }}">
                                <button type="button" onclick="changeQty({{ $item->cart_item_id }}, -1)" class="w-7 h-7 flex items-center justify-center text-gray-500 hover:bg-gray-50">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <line x1="5" y1="12" x2="19" y2="12"></line>
                                    </svg>
                                </button>
                                <span class="qty-value w-8 text-center text-sm font-medium text-gray-800">{{ $item->quantity }}</span>
                                <button type="button" onclick="changeQty({{ $item->cart_item_id }}, 1)" class="w-7 h-7 flex items-center justify-center text-gray-500 hover:bg-gray-50">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <line x1="12" y1="5" x2="12" y2="19"></line>
                                        <line x1="5" y1="12" x2="19" y2="12"></line>
                                    </svg>
                                </button>
                            </form>
                        </div>

                        {{-- remove form — submits DELETE --}}
                        <form method="POST" action="{{ route('cart.remove', $item->cart_item_id) }}" onsubmit="return confirm('Remove this item?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="flex items-center gap-1 text-xs font-medium text-gray-400 hover:text-red-500">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <polyline points="3 6 5 6 21 6"></polyline>
                                    <path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"></path>
                                    <path d="M10 11v6"></path>
                                    <path d="M14 11v6"></path>
                                    <path d="M9 6V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"></path>
                                </svg>
                                Remove
                            </button>
                        </form>
                    </div>
                </div>

                <div class="text-right shrink-0">
                    <p class="line-total text-sm font-bold text-gray-900">${{ number_format($item->subtotal, 2) }}</p>
                    <p class="text-xs text-gray-400 mt-0.5">${{ number_format($item->unit_price, 2) }} each</p>
                </div>
            </div>
        @empty
            <div class="py-12 text-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-12 h-12 text-gray-200 mx-auto mb-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                    <circle cx="9" cy="21" r="1"></circle>
                    <circle cx="20" cy="21" r="1"></circle>
                    <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
                </svg>
                <p class="text-sm text-gray-400">Your cart is empty.</p>
                <a href="{{ route('products.index') }}" class="inline-block mt-3 text-sm font-medium text-cyan-500 hover:text-cyan-600">Start Shopping</a>
            </div>
        @endforelse
    </div>
</div>
