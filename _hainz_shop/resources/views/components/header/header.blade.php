{{--
    ==================================================================
    ERP MODULE: Site Header / Navigation (Layout Partial)
    ------------------------------------------------------------------
    FRONTEND-ONLY IMPLEMENTATION — NO BACKEND LOGIC INCLUDED.
    Intended location: resources/views/layouts/partials/header.blade.php
    Include from your main layout like so:

        @include('layouts.partials.header')
        <main>@yield('content')</main>

    <!-- TODO (Header) -->
    <!-- Controller: N/A (static nav) — categories may eventually come -->
    <!-- from a CategoryController@megaMenu() cached response. -->
    <!-- Replace with: Auth::user()->name / notifications count badge -->
    <!-- Replace with: $categories (Category model, grouped by parent) -->
    <!-- Replace with: $trendingSearches (from a SearchLog/analytics table) -->
    <!-- Future: swap hover-only dropdown for click-to-toggle on touch devices -->
    ==================================================================
--}}

{{-- Outfit font (per Figma) — move to layout <head> once integrated --}}
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700&display=swap" rel="stylesheet">

<header class="bg-blue-900 relative z-50" style="font-family: 'Outfit', sans-serif;" x-data="{ mobileNavOpen: false }">
    {{-- ============================================================
         TOP PROMO STRIP
         <!-- Replace with: $announcementBar->message (CMS-managed) -->
    ============================================================= --}}
    <div class="border-b border-white/10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex items-center justify-between py-2 text-[10px] sm:text-xs text-white/90">
            <p>Free on orders over $99 &nbsp;|&nbsp; Next-Day Delivery Available</p>

            <div class="flex items-center gap-3">
                {{-- notification / support icon --}}
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-orange-400" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M20.01 15.38c-1.23 0-2.42-.2-3.53-.56a.977.977 0 0 0-1.01.24l-1.57 1.97c-2.83-1.35-5.48-3.9-6.89-6.83l1.95-1.66c.27-.28.35-.67.24-1.02-.37-1.11-.56-2.3-.56-3.53 0-.54-.44-1-.96-1H4.19C3.61 3 3 3.24 3 3.99 3 13.28 10.73 21 20.01 21c.71 0 .99-.6.99-1.18v-3.45c0-.53-.46-.99-1-.99z"></path>
                </svg>
                <span class="text-cyan-300 tracking-widest">09********</span>
            </div>
        </div>
    </div>

    {{-- ============================================================
         MAIN NAV
         <!-- Replace with: @foreach($mainNavLinks as $link) -->
    ============================================================= --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <nav class="flex items-center justify-center gap-6 lg:gap-10 py-4 relative">

            {{-- ---------------------------------------------------
                 ALL HARDWARE — hover-triggered mega menu
                 <!-- Replace with: $categories grouped by parent_id -->
            --------------------------------------------------- --}}
            <div class="group relative hidden lg:block">
                <button type="button" class="flex items-center gap-1 text-sm font-medium text-white hover:text-cyan-300 transition-colors">
                    All Hardware
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 transition-transform group-hover:rotate-180" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="6 9 12 15 18 9"></polyline>
                    </svg>
                </button>

                {{-- MEGA MENU PANEL --}}
                <div class="invisible opacity-0 translate-y-2 group-hover:visible group-hover:opacity-100 group-hover:translate-y-0 transition-all duration-150 absolute left-1/2 -translate-x-1/2 top-full pt-3 w-[min(90vw,860px)]">
                    <div class="bg-white border-2 border-blue-900 rounded-2xl shadow-xl p-6">

                        @php
                            // TODO (Header): replace with $categories from CategoryController
                            $megaMenu = [
                                [
                                    'title' => 'Processors',
                                    'items' => ['Intel Core i9 Series', 'Intel Core i7 Series', 'AMD Ryzen 9', 'AMD Ryzen 7', 'Workstation CPUs'],
                                ],
                                [
                                    'title' => 'Graphic Cards',
                                    'items' => ['NVIDIA RTX 4090', 'NVIDIA RTX 4080', 'AMD RX 7900 XTX', 'Professional GPUs', 'Budget GPUs'],
                                ],
                                [
                                    'title' => 'Memory',
                                    'items' => ['DDR5 Kits', 'DDR4 Kits', 'ECC RAM', 'SO-DIMM', 'Server Memory'],
                                ],
                                [
                                    'title' => 'Storage',
                                    'items' => ['NVMe PCIe 5.0', 'NVMe PCIe 4.0', 'SATA SSDs', 'HDDs', 'Enterprise NAS'],
                                ],
                                [
                                    'title' => 'Motherboards',
                                    'items' => ['Z790 Platform', 'X670E Platform', 'B760 Platform', 'B650 Platform', 'Server Boards'],
                                ],
                                [
                                    'title' => 'Power Supplies',
                                    'items' => ['1000w+ Titanium', '850W Gold', '750W Gold', 'Modular PSUs', 'SFX Form Factor'],
                                ],
                                [
                                    'title' => 'Cooling',
                                    'items' => ['360mm AIOs', '240mm AIOs', 'Tower Coolers', 'Case Fans', 'Thermal Paster'],
                                ],
                                [
                                    'title' => 'PC Cases',
                                    'items' => ['Full Tower', 'Mid Tower', 'Mini-ITX', 'Tempered Glass', 'Server Chassis'],
                                ],
                            ];

                            // TODO (Header): replace with $trendingSearches from analytics
                            $trending = ['RTX 4090', 'i9-14900K', 'DDR5 7200MHz', 'RTX 4090'];
                        @endphp

                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-x-6 gap-y-6">
                            @foreach ($megaMenu as $column)
                                <div>
                                    <h4 class="text-sm font-bold text-blue-900 mb-2.5">{{ $column['title'] }}</h4>
                                    <ul class="space-y-1.5">
                                        @foreach ($column['items'] as $item)
                                            <li>
                                                <a href="#" class="text-xs text-gray-500 hover:text-cyan-500 transition-colors">{{ $item }}</a>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endforeach
                        </div>

                        <div class="border-t border-blue-100 mt-5 pt-4 flex flex-wrap items-center gap-2">
                            <span class="text-xs font-semibold text-blue-900">Trendings:</span>
                            @foreach ($trending as $tag)
                                <a href="#" class="text-[11px] font-medium text-blue-900 border border-blue-200 rounded-full px-3 py-1 hover:bg-blue-50 transition-colors">
                                    {{ $tag }}
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <a href="/shop" @click.prevent="filterAndScroll && filterAndScroll('Processors')" class="text-sm font-medium text-white hover:text-cyan-300 transition-colors hidden lg:inline">Processors</a>
            <a href="/shop" @click.prevent="filterAndScroll && filterAndScroll('Graphics Cards')" class="text-sm font-medium text-white hover:text-cyan-300 transition-colors hidden lg:inline">GPUs</a>
            <a href="/shop" @click.prevent="filterAndScroll && filterAndScroll('Motherboards')" class="text-sm font-medium text-white hover:text-cyan-300 transition-colors hidden lg:inline">Motherboards</a>
            <a href="/shop" class="text-sm font-medium text-white hover:text-cyan-300 transition-colors hidden lg:inline">Deals</a>

            {{-- icons --}}
            <div class="flex items-center gap-5 absolute right-0">
                <div x-show="searchOpen" class="flex items-center">
                    <div class="relative">
                        <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        <input type="text" x-model="searchQuery" placeholder="Search products..." class="w-32 sm:w-48 pl-9 pr-3 py-2 bg-blue-900/40 border border-blue-700/50 rounded-lg text-xs font-medium text-white placeholder-blue-300 outline-none focus:border-blue-400 transition">
                    </div>
                </div>
                <button type="button" @click="searchOpen = !searchOpen; if(!searchOpen) searchQuery = ''" aria-label="Search" class="text-white hover:text-cyan-300 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="11" cy="11" r="7"></circle>
                        <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                    </svg>
                </button>
                {{-- routes to cart page --}}
                <a href="/dummy/cart" @click.prevent="isLoggedIn !== undefined ? (isLoggedIn ? window.location.href='/dummy/cart' : window.location.href='/login') : true" aria-label="Cart" class="text-white hover:text-cyan-300 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="9" cy="21" r="1"></circle>
                        <circle cx="20" cy="21" r="1"></circle>
                        <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
                    </svg>
                </a>
                <a href="{{ route('account') }}" aria-label="Account" class="text-white hover:text-cyan-300 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                        <circle cx="12" cy="7" r="4"></circle>
                    </svg>
                </a>
                {{-- Mobile Hamburger --}}
                <button @click="mobileNavOpen = !mobileNavOpen" class="lg:hidden text-white hover:text-cyan-300 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                </button>
            </div>
        </nav>

        {{-- Mobile Nav Drawer --}}
        <div x-show="mobileNavOpen" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 -translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 -translate-y-4" class="lg:hidden border-t border-white/10 pb-4">
            <div class="space-y-3 pt-4 text-sm">
                <a href="/shop" @click="mobileNavOpen = false" class="block text-white hover:text-cyan-300 transition-colors font-medium">Processors</a>
                <a href="/shop" @click="mobileNavOpen = false" class="block text-white hover:text-cyan-300 transition-colors font-medium">GPUs</a>
                <a href="/shop" @click="mobileNavOpen = false" class="block text-white hover:text-cyan-300 transition-colors font-medium">Motherboards</a>
                <a href="/shop" @click="mobileNavOpen = false; resetToHome && resetToHome()" class="block text-white hover:text-cyan-300 transition-colors font-medium">All Hardware</a>
                <a href="/shop" @click="mobileNavOpen = false" class="block text-white hover:text-cyan-300 transition-colors font-medium">Deals</a>
            </div>
        </div>
    </div>
</header>
