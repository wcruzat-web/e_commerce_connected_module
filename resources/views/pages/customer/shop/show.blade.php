@extends('layouts.app')

@section('content')

<div class="max-w-7xl mx-auto px-6 py-10 space-y-12">

    <nav class="text-xs font-semibold text-gray-400 flex items-center space-x-2">
        <a href="{{ route('products.index') }}" class="hover:text-blue-600">Shop</a>
        <span>/</span>
        <span class="text-slate-900">{{ $product['name'] }}</span>
    </nav>

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

    <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden shadow-sm">
        <div class="flex border-b border-gray-200 bg-gray-50/50">
            <button type="button" onclick="switchTab('specs')" id="tab-specs-btn" class="px-6 py-4 border-b-2 border-blue-600 text-blue-600 bg-white font-bold text-xs tracking-wide uppercase">Full Specifications</button>
            <button type="button" onclick="switchTab('compatibility')" id="tab-compatibility-btn" class="px-6 py-4 border-b-2 border-transparent text-gray-500 hover:text-slate-800 font-semibold text-xs tracking-wide uppercase">Compatibility</button>
            <button type="button" onclick="switchTab('reviews')" id="tab-reviews-btn" class="px-6 py-4 border-b-2 border-transparent text-gray-500 hover:text-slate-800 font-semibold text-xs tracking-wide uppercase">Reviews</button>
        </div>

        <div class="p-6">
            <div id="tab-specs" class="tab-content">
                @if(!empty($product['detailSpecs']))
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-12 gap-y-8 pt-4 pb-6">
                        @foreach ($product['detailSpecs'] as $section)
                            <div class="space-y-3">
                                <h4 class="text-xs font-bold text-blue-600 uppercase tracking-wider pb-1">{{ $section['section'] }}</h4>
                                <div class="space-y-1 text-[11px]">
                                    @foreach ($section['items'] as $i => $item)
                                        <div class="flex justify-between items-center px-3 py-2 {{ $i % 2 === 0 ? 'bg-blue-50/50 rounded' : 'rounded' }}">
                                            <span class="text-gray-400 font-medium">{{ $item['label'] }}</span>
                                            <span class="font-semibold text-slate-800">{{ $item['value'] }}</span>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <div id="tab-compatibility" class="tab-content hidden">
                @if(!empty($product['compatGroups']))
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 pt-4 pb-6">
                        @foreach ($product['compatGroups'] as $group)
                            <div class="bg-white rounded-xl p-5 border border-gray-300 shadow-[0_4px_12px_rgba(0,0,0,0.04)]">
                                <h4 class="text-xs font-bold text-blue-800 tracking-wide mb-4">{{ $group['category'] }}</h4>
                                <ul class="space-y-3 text-[11px] font-semibold text-slate-700">
                                    @foreach ($group['items'] as $item)
                                        <li class="flex items-center space-x-2">
                                            <svg class="w-4 h-4 text-emerald-500 shrink-0" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"></path></svg>
                                            <span class="text-gray-400 font-normal">-</span>
                                            <span>{{ $item }}</span>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-xs text-gray-400 text-center py-8">No compatibility information available.</p>
                @endif
            </div>

            <div id="tab-reviews" class="tab-content hidden">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8 items-center pt-4 pb-6">
                    <div class="flex flex-col items-center justify-center text-center md:border-r border-gray-100 py-4">
                        <h3 class="text-5xl font-black text-slate-800 tracking-tight">{{ $product['rating'] }}</h3>
                        <p class="text-[10px] font-bold text-gray-400 mt-1 tracking-wide">{{ $product['reviewCount'] }} verified reviews</p>
                    </div>
                    <div class="md:col-span-2 space-y-2.5 px-2 md:px-6">
                        @foreach ($product['reviewDistribution'] as $starIdx => $pct)
                            <div class="flex items-center space-x-3 text-[11px] font-bold text-gray-400">
                                <span class="w-3 select-none">{{ 5 - $starIdx }}</span>
                                <div class="flex-1 h-2 bg-gray-100 rounded-full overflow-hidden">
                                    <div class="h-full bg-cyan-400 rounded-full" style="width: {{ $pct }}%"></div>
                                </div>
                                <span class="w-7 text-right">{{ $pct }}%</span>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="border-t border-gray-100 pt-8 mt-8">
                    <h4 class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-5">Comments</h4>

                    @forelse ($product['userReviews'] as $review)
                        <div class="border-b border-gray-100 pb-5 mb-5">
                            <div class="flex items-center space-x-3 mb-2.5">
                                @if ($review['profile_picture'])
                                    <img src="{{ $review['profile_picture'] }}" class="w-8 h-8 rounded-full object-cover">
                                @else
                                    <div class="w-8 h-8 bg-blue-600 rounded-full flex items-center justify-center text-white font-bold text-xs">{{ $review['initials'] }}</div>
                                @endif
                                <div>
                                    <p class="text-xs font-bold text-slate-800">{{ $review['name'] }}</p>
                                    <p class="text-[9px] text-gray-400">{{ $review['createdAt'] }}</p>
                                </div>
                            </div>
                            <p class="text-[10px] text-gray-500 leading-relaxed">{{ $review['comment'] }}</p>
                        </div>
                    @empty
                        <p class="text-xs text-gray-400 text-center py-4">No reviews yet. Be the first to review!</p>
                    @endforelse

                    @auth
                        <form method="POST" action="{{ route('shop.review') }}" class="flex items-start space-x-3 mt-6">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $product['id'] }}">
                            <input type="hidden" name="rating" id="review-rating" value="5">
                            <div class="w-8 h-8 bg-blue-600 rounded-full flex items-center justify-center text-white font-bold text-xs shrink-0">
                                {{ strtoupper(substr(auth()->user()->first_name ?? 'A', 0, 1) . substr(auth()->user()->last_name ?? 'U', 0, 1)) }}
                            </div>
                            <div class="flex-1">
                                <div class="flex items-center gap-1 mb-2" id="star-rating">
                                    @for ($i = 1; $i <= 5; $i++)
                                        <button type="button" data-star="{{ $i }}" onclick="setRating({{ $i }})" class="star-btn text-lg">
                                            <svg class="w-5 h-5 fill-current {{ $i <= 5 ? 'text-yellow-400' : 'text-gray-300' }}" viewBox="0 0 24 24"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                                        </button>
                                    @endfor
                                </div>
                                <textarea name="comment" placeholder="Share your thoughts about this product..." class="w-full text-xs p-3 bg-gray-50 border border-gray-200 rounded-xl resize-none outline-none focus:border-blue-400 focus:bg-white transition" rows="2" required></textarea>
                                <div class="flex justify-between items-center mt-2">
                                    <span class="text-[9px] text-gray-400">Your review will be posted publicly</span>
                                    <button type="submit" class="text-xs font-bold px-4 py-2 rounded-lg transition bg-blue-600 hover:bg-blue-700 text-white">Submit</button>
                                </div>
                            </div>
                        </form>
                    @else
                        <p class="text-xs text-gray-400 text-center py-6"><a href="{{ route('login') }}" class="text-blue-600 hover:underline">Log in</a> to leave a review.</p>
                    @endauth
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function switchTab(tab) {
    document.querySelectorAll('.tab-content').forEach(el => el.classList.add('hidden'));
    document.getElementById('tab-' + tab).classList.remove('hidden');
    document.querySelectorAll('[id$="-btn"]').forEach(el => {
        el.classList.remove('border-blue-600', 'text-blue-600', 'bg-white', 'font-bold');
        el.classList.add('border-transparent', 'text-gray-500', 'font-semibold');
    });
    var btn = document.getElementById('tab-' + tab + '-btn');
    btn.classList.remove('border-transparent', 'text-gray-500', 'font-semibold');
    btn.classList.add('border-blue-600', 'text-blue-600', 'bg-white', 'font-bold');
}
function setRating(val) {
    document.getElementById('review-rating').value = val;
    document.querySelectorAll('.star-btn').forEach(function(btn) {
        var star = parseInt(btn.getAttribute('data-star'));
        var svg = btn.querySelector('svg');
        if (star <= val) {
            svg.classList.remove('text-gray-300');
            svg.classList.add('text-yellow-400');
        } else {
            svg.classList.remove('text-yellow-400');
            svg.classList.add('text-gray-300');
        }
    });
}
</script>
@endsection
