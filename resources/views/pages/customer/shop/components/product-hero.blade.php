<div class="grid grid-cols-1 lg:grid-cols-2 gap-12 bg-white rounded-2xl border border-gray-200 p-8 shadow-sm">
    <div class="relative flex flex-col justify-center items-center border border-gray-100 rounded-xl p-6 bg-gray-50/50">
        @unless($product['inStock'])
            <span class="absolute top-4 left-4 bg-red-600 text-white text-[10px] font-black px-2.5 py-1 rounded-full uppercase tracking-wider">Out of Stock</span>
        @endunless
        <img src="{{ $product['image'] }}" alt="{{ $product['name'] }}" class="max-h-[340px] object-contain drop-shadow-xl">
    </div>

    <div class="space-y-6">
        <div>
            <span class="bg-blue-900 text-white text-[10px] font-black tracking-widest uppercase px-2.5 py-1 rounded">{{ $product['brand'] }}</span>
            <p class="text-xs font-mono text-gray-400 mt-2">SKU: {{ $product['sku'] }}</p>
            <h1 class="text-2xl font-black text-slate-900 tracking-tight mt-1">{{ $product['name'] }}</h1>
        </div>

        <div class="flex items-baseline space-x-3 border-y border-gray-100 py-4">
            <span class="text-3xl font-black text-slate-900">₱{{ number_format($product['price']) }}</span>
        </div>

        @if(!empty($product['atAGlance']))
            <div>
                <h4 class="text-xs font-bold uppercase text-gray-400 tracking-wider mb-3">At A Glance</h4>
                <div class="grid grid-cols-3 gap-3">
                    @foreach ($product['atAGlance'] as $item)
                        <div class="border border-gray-200 rounded-xl p-3 text-center bg-gray-50/50">
                            <span class="block text-[10px] text-gray-400 font-bold uppercase">{{ $item['label'] }}</span>
                            <span class="text-xs font-black text-slate-800">{{ $item['value'] }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <form method="POST" action="{{ route('cart.add') }}" class="flex items-center space-x-4 pt-2">
            @csrf
            <input type="hidden" name="product_id" value="{{ $product['id'] }}">
            <input type="hidden" name="quantity" id="qty-input" value="1">
            <div class="flex items-center border border-gray-300 rounded-lg overflow-hidden bg-white shadow-sm">
                <button type="button" onclick="var e=document.getElementById('qty-input');var s=document.getElementById('qty-span');var v=parseInt(e.value)-1;if(v<1)v=1;e.value=v;s.textContent=v" class="px-3 py-2 bg-gray-50 text-slate-700 font-extrabold text-sm hover:bg-gray-100 transition select-none">-</button>
                <span id="qty-span" class="px-5 py-2 font-black text-sm w-12 text-center">1</span>
                <button type="button" onclick="var e=document.getElementById('qty-input');var s=document.getElementById('qty-span');var v=parseInt(e.value)+1;if(v>99)v=99;e.value=v;s.textContent=v" class="px-3 py-2 bg-gray-50 text-slate-700 font-extrabold text-sm hover:bg-gray-100 transition select-none">+</button>
            </div>
            <button type="submit"
                {{ $product['inStock'] ? '' : 'disabled' }}
                class="flex-1 font-black text-xs py-3.5 rounded-xl shadow-md transition flex items-center justify-center space-x-2 tracking-wide uppercase
                {{ $product['inStock'] ? 'bg-sky-400 hover:bg-sky-500 text-blue-950 shadow-sm' : 'bg-gray-200 text-gray-400 cursor-not-allowed' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 0a2 2 0 100 4 2 2 0 000-4z"></path></svg>
                <span>Add to Cart</span>
            </button>
        </form>
    </div>
</div>
