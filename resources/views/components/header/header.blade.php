<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700&display=swap" rel="stylesheet">

<style>
@media (max-width: 1023px) {
    .nav-cat-link { display: none; }
    .nav-all-hw   { display: none; }
}
@media (min-width: 1024px) {
    .nav-cat-link { display: inline; }
    .nav-all-hw   { display: block;  }
}
</style>

<header class="bg-blue-900 relative z-50" style="font-family: 'Outfit', sans-serif;">
    <div class="border-b border-white/10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex items-center justify-between py-2 text-[10px] sm:text-xs text-white/90">
            <p>Free on orders over $99 &nbsp;|&nbsp; Next-Day Delivery Available</p>
            <div class="flex items-center gap-3">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-orange-400" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M20.01 15.38c-1.23 0-2.42-.2-3.53-.56a.977.977 0 0 0-1.01.24l-1.57 1.97c-2.83-1.35-5.48-3.9-6.89-6.83l1.95-1.66c.27-.28.35-.67.24-1.02-.37-1.11-.56-2.3-.56-3.53 0-.54-.44-1-.96-1H4.19C3.61 3 3 3.24 3 3.99 3 13.28 10.73 21 20.01 21c.71 0 .99-.6.99-1.18v-3.45c0-.53-.46-.99-1-.99z"></path>
                </svg>
                <span class="text-cyan-300 tracking-widest">09********</span>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <nav class="flex items-center justify-center gap-6 lg:gap-10 py-4 relative">

            @php
                use Illuminate\Support\Facades\DB;
                $mmCategories = \App\Models\Category::with(['products' => function ($q) {
                    $q->where('is_active', 1)->orderBy('created_at', 'desc')->limit(4);
                }])->get();
                $topProduct = \App\Models\Product::select('products.*', DB::raw('COALESCE(SUM(order_items.quantity), 0) as total_sold'))
                    ->leftJoin('order_items', 'products.id', '=', 'order_items.product_id')
                    ->groupBy('products.id')
                    ->orderByDesc('total_sold')
                    ->first();
            @endphp
            <div class="relative nav-all-hw">
                <button type="button" onclick="toggleMegaMenu()" class="flex items-center gap-1 text-sm font-medium text-white hover:text-cyan-300 transition-colors">
                    All Hardware
                    <svg id="mmArrow" xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 transition-transform" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="6 9 12 15 18 9"></polyline>
                    </svg>
                </button>
                <div id="megaMenu" class="hidden opacity-0 translate-y-2 transition-all duration-150 absolute left-1/2 -translate-x-1/2 top-full pt-3 w-[min(90vw,860px)]" style="display:none;">
                    <div class="bg-white border-2 border-blue-900 rounded-2xl shadow-xl p-6">
                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-x-6 gap-y-6">
                            @foreach ($mmCategories as $mmCat)
                                @if ($mmCat->products->count())
                                <div>
                                    <h4 class="text-sm font-bold text-blue-900 mb-2.5">{{ $mmCat->name }}</h4>
                                    <ul class="space-y-1.5">
                                        @foreach ($mmCat->products as $mmProd)
                                            <li><a href="{{ route('products.show', $mmProd->id) }}" class="text-xs text-gray-500 hover:text-cyan-500 transition-colors">{{ $mmProd->name }}</a></li>
                                        @endforeach
                                    </ul>
                                </div>
                                @endif
                            @endforeach
                        </div>
                        @if ($topProduct)
                        <div class="border-t border-blue-100 mt-5 pt-4 flex flex-wrap items-center gap-2">
                            <span class="text-xs font-semibold text-blue-900">Trending:</span>
                            <a href="{{ route('products.show', $topProduct->id) }}" class="text-[11px] font-medium text-blue-900 border border-blue-200 rounded-full px-3 py-1 hover:bg-blue-50 transition-colors">{{ $topProduct->name }} ({{ $topProduct->total_sold }} sold)</a>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            @php $cats = $headerCategories ?? \App\Models\Category::all(); @endphp
            @foreach ($cats as $cat)
                <a href="{{ route('products.index', ['category' => $cat->name]) }}" class="text-sm font-medium text-white hover:text-cyan-300 transition-colors nav-cat-link">{{ $cat->name }}</a>
            @endforeach

            <div class="flex items-center gap-5 absolute right-0">
                <div id="searchBar" class="hidden items-center">
                    <div class="relative">
                        <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        <input type="text" id="searchInput" placeholder="Search products..." value="{{ request('search') }}" class="w-32 sm:w-48 pl-9 pr-3 py-2 bg-blue-900/40 border border-blue-700/50 rounded-lg text-xs font-medium text-white placeholder-blue-300 outline-none focus:border-blue-400 transition">
                    </div>
                </div>
                <button type="button" onclick="toggleSearch()" aria-label="Search" class="text-white hover:text-cyan-300 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="11" cy="11" r="7"></circle>
                        <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                    </svg>
                </button>
                <a href="{{ route('cart') }}" aria-label="Cart" class="text-white hover:text-cyan-300 transition-colors relative">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="9" cy="21" r="1"></circle>
                        <circle cx="20" cy="21" r="1"></circle>
                        <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
                    </svg>
                    <span class="js-cart-badge hidden absolute -top-2 -right-2 bg-cyan-400 text-blue-950 text-[10px] font-bold rounded-full w-4 h-4 flex items-center justify-center"></span>
                </a>
                @auth
                    <div class="relative" id="profileDropdown">
                        <button onclick="toggleProfileDropdown()" aria-label="Account" class="text-white hover:text-cyan-300 transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                <circle cx="12" cy="7" r="4"></circle>
                            </svg>
                        </button>
                        <div id="profileMenu" class="hidden absolute right-0 mt-2 w-44 bg-white rounded-xl shadow-lg border border-gray-100 py-2 z-50">
                            <a href="{{ route('dashboard') }}" class="flex items-center gap-2 px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                                My Profile
                            </a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="w-full text-left px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 flex items-center gap-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path><polyline points="16 17 21 12 16 7"></polyline><line x1="21" y1="12" x2="9" y2="12"></line></svg>
                                    Logout
                                </button>
                            </form>
                        </div>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="text-sm font-medium text-white hover:text-cyan-300 transition-colors">Sign In</a>
                    <a href="{{ route('register') }}" class="text-sm font-semibold text-white bg-cyan-500 hover:bg-cyan-600 px-4 py-2 rounded-lg transition-colors">Sign Up</a>
                @endauth
                <button onclick="toggleMobileNav()" class="lg:hidden text-white hover:text-cyan-300 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                </button>
            </div>
        </nav>

        <div id="mobileNav" class="hidden lg:hidden border-t border-white/10 pb-4">
            <div class="space-y-3 pt-4 text-sm">
                @foreach ($cats as $cat)
                    <a href="{{ route('products.index', ['category' => $cat->name]) }}" class="block text-white hover:text-cyan-300 transition-colors font-medium" onclick="toggleMobileNav()">{{ $cat->name }}</a>
                @endforeach
            </div>
        </div>
    </div>
</header>

<script>
function toggleSearch() {
    const bar = document.getElementById('searchBar');
    const input = document.getElementById('searchInput');
    const hidden = bar.classList.contains('hidden');
    bar.classList.toggle('hidden');
    if (hidden) { input.focus(); }
    else if (input.value.trim()) { doSearch(input.value.trim()); }
}
function doSearch(q) {
    window.location = '{{ route('products.index') }}?search=' + encodeURIComponent(q);
}
document.addEventListener('DOMContentLoaded', function() {
    var si = document.getElementById('searchInput');
    if (si) si.addEventListener('keydown', function(e) {
        if (e.key === 'Enter') { e.preventDefault(); doSearch(this.value.trim()); }
    });
});
function toggleProfileDropdown() {
    document.getElementById('profileMenu').classList.toggle('hidden');
}
function toggleMobileNav() {
    document.getElementById('mobileNav').classList.toggle('hidden');
}
function toggleMegaMenu() {
    const menu = document.getElementById('megaMenu');
    const arrow = document.getElementById('mmArrow');
    const hidden = menu.style.display === 'none';
    menu.style.display = hidden ? 'block' : 'none';
    if (hidden) {
        menu.classList.remove('hidden');
        setTimeout(() => menu.classList.remove('opacity-0', 'translate-y-2'), 10);
    } else {
        menu.classList.add('opacity-0', 'translate-y-2');
        setTimeout(() => menu.classList.add('hidden'), 150);
    }
    if (arrow) arrow.classList.toggle('rotate-180');
}
document.addEventListener('click', function(e) {
    const mm = document.getElementById('megaMenu');
    if (mm && !mm.parentElement.contains(e.target) && mm.style.display !== 'none') {
        mm.style.display = 'none';
        mm.classList.add('opacity-0', 'translate-y-2');
        const arrow = document.getElementById('mmArrow');
        if (arrow) arrow.classList.remove('rotate-180');
    }
    const dd = document.getElementById('profileDropdown');
    const menu = document.getElementById('profileMenu');
    if (dd && menu && !dd.contains(e.target) && !menu.classList.contains('hidden')) {
        menu.classList.add('hidden');
    }
});
</script>
