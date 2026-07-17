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

<header class="bg-blue-900 relative z-50" style="font-family: 'Outfit', sans-serif;">
    {{-- ============================================================
         TOP PROMO STRIP
         <!-- Replace with: $announcementBar->message (CMS-managed) -->
    ============================================================= --}}
    <div class="border-b border-white/10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex items-center justify-between py-2 text-xs text-white/90">
            <p>Free on orders over ₱3,000 &nbsp;|&nbsp; Next-Day Delivery Available</p>

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
        <nav class="flex items-center justify-center gap-10 py-4 relative">

            {{-- ---------------------------------------------------
                 ALL HARDWARE — hover-triggered mega menu
                 <!-- Replace with: $categories grouped by parent_id -->
            --------------------------------------------------- --}}
            <div class="group relative">
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

            <a href="#" class="text-sm font-medium text-white hover:text-cyan-300 transition-colors">Processors</a>
            <a href="#" class="text-sm font-medium text-white hover:text-cyan-300 transition-colors">GPUs</a>
            <a href="#" class="text-sm font-medium text-white hover:text-cyan-300 transition-colors">Motherboards</a>
            <a href="#" class="text-sm font-medium text-white hover:text-cyan-300 transition-colors">Deals</a>

            {{-- icons --}}
            <div class="flex items-center gap-5 absolute right-0">
                <button type="button" aria-label="Search" class="text-white hover:text-cyan-300 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="11" cy="11" r="7"></circle>
                        <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                    </svg>
                </button>
                {{-- routes to cart page --}}
                <a href="{{ route('cart') }}" aria-label="Cart" class="text-white hover:text-cyan-300 transition-colors relative">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="9" cy="21" r="1"></circle>
                        <circle cx="20" cy="21" r="1"></circle>
                        <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
                    </svg>
                    {{-- CHANGES HERE: cart badge updated dynamically by shop AJAX --}}
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
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                    <circle cx="12" cy="7" r="4"></circle>
                                </svg>
                                My Profile
                            </a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="w-full text-left px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 flex items-center gap-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                                        <polyline points="16 17 21 12 16 7"></polyline>
                                        <line x1="21" y1="12" x2="9" y2="12"></line>
                                    </svg>
                                    Logout
                                </button>
                            </form>
                        </div>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="text-sm font-medium text-white hover:text-cyan-300 transition-colors">Sign In</a>
                    <a href="{{ route('register') }}" class="text-sm font-semibold text-white bg-cyan-500 hover:bg-cyan-600 px-4 py-2 rounded-lg transition-colors">Sign Up</a>
                @endauth

                @auth
                    <script>
                        function toggleProfileDropdown() {
                            const menu = document.getElementById('profileMenu');
                            menu.classList.toggle('hidden');
                        }
                        document.addEventListener('click', function(e) {
                            const dd = document.getElementById('profileDropdown');
                            const menu = document.getElementById('profileMenu');
                            if (!dd.contains(e.target) && !menu.classList.contains('hidden')) {
                                menu.classList.add('hidden');
                            }
                        });
                    </script>
                @endauth
            </div>
        </nav>
    </div>
</header>
