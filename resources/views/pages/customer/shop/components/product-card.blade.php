<div class="bg-white rounded-2xl border border-gray-200/80 p-5 shadow-sm flex flex-col justify-between transition hover:shadow-md relative {{ !$product['inStock'] ? 'opacity-70 bg-gray-50/50' : '' }}">
    <div>
        <div class="flex justify-between items-center mb-3">
            @if($product['badge'])
                <span class="{{ $product['badgeClass'] }} text-[9px] font-black uppercase px-2.5 py-0.5 rounded tracking-wide text-white">{{ $product['badge'] }}</span>
            @endif
        </div>

        <a href="{{ route('products.show', $product['id']) }}" class="h-36 w-full flex items-center justify-center mb-4 bg-transparent relative">
            @unless($product['inStock'])
                <div class="absolute inset-0 bg-slate-900/5 backdrop-blur-[1px] flex flex-col items-center justify-center rounded-xl z-10">
                    <span class="bg-white/95 px-4 py-2 rounded-lg font-black text-xs tracking-widest text-slate-800 shadow">OUT OF STOCK</span>
                </div>
            @endunless
            <img src="{{ $product['image'] }}" alt="{{ $product['name'] }}" class="h-full object-contain max-w-[85%]">
        </a>

        <p class="text-[9px] uppercase font-bold text-gray-400 tracking-wider">{{ $product['categoryMeta'] }}</p>
        <h3 class="font-bold text-xs mt-0.5 tracking-tight text-slate-900 hover:text-blue-600 transition cursor-pointer">
            <a href="{{ route('products.show', $product['id']) }}">{{ $product['name'] }}</a>
        </h3>

        @if(!empty($product['specs']))
            <div class="flex flex-wrap gap-1 mt-2">
                @foreach ($product['specs'] as $spec)
                    <span class="bg-gray-100 text-[8.5px] text-gray-500 px-1.5 py-0.5 rounded font-medium">{{ $spec }}</span>
                @endforeach
            </div>
        @endif
    </div>

    <div class="mt-4 pt-3 border-t border-gray-100 flex items-center justify-between">
        <div>
            @if($product['original_price'])
                <span class="text-xs text-gray-400 line-through mr-1.5">₱{{ number_format($product['original_price']) }}</span>
            @endif
            <span class="text-sm font-black text-slate-900">₱{{ number_format($product['price']) }}</span>
        </div>
        <div class="flex items-center gap-2">
            <form method="POST" action="{{ route('wishlist.toggle') }}" class="js-wish-form">
                @csrf
                <input type="hidden" name="product_id" value="{{ $product['id'] }}">
                <button type="submit" class="p-2 rounded-lg transition hover:bg-red-50 js-wish-btn {{ $product['in_wishlist'] ? 'text-red-500' : 'text-gray-400 hover:text-red-500' }}" title="{{ $product['in_wishlist'] ? 'Remove from Wishlist' : 'Add to Wishlist' }}">
                    <svg class="w-4 h-4 js-wish-svg" fill="{{ $product['in_wishlist'] ? 'currentColor' : 'none' }}" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                </button>
            </form>
            <form method="POST" action="{{ route('cart.add') }}" class="js-cart-form">
                @csrf
                <input type="hidden" name="product_id" value="{{ $product['id'] }}">
                <input type="hidden" name="quantity" value="1">
                <button type="submit"
                    {{ $product['inStock'] ? '' : 'disabled' }}
                    class="font-extrabold text-[10px] px-3 py-2 rounded-lg transition tracking-wide
                    {{ $product['inStock'] ? 'bg-sky-400 hover:bg-sky-500 text-blue-950 shadow-sm' : 'bg-gray-200 text-gray-400 cursor-not-allowed' }}">
                    Add to Cart
                </button>
            </form>
        </div>
    </div>
</div>
