<!-- TOP ANNOUNCEMENT BAR -->
<div class="bg-[#1e40af] text-white text-xs py-2 px-4 md:px-12 flex justify-between items-center border-b border-blue-700/50">
    <div class="flex items-center space-x-4">
        <span>Free on orders over ₱3,000</span>
        <span class="hidden md:inline text-blue-200">|</span>
        <span>Next-Day Delivery Available</span>
    </div>
    <div class="flex items-center space-x-6">
        <a href="#" class="hover:text-blue-200 transition">Admin Portal</a>
        <span class="text-blue-200">|</span>
        <div class="flex items-center space-x-1 text-amber-400 font-semibold">
            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z"></path></svg>
            <span>09*********</span>
        </div>
    </div>
</div>

<!-- NAVIGATION HEADER WITH MEGA-MENU HOVER -->
<header class="bg-[#1e3a8a] text-white relative z-50" x-data="{ megaMenuOpen: false }">
    <div class="max-w-7xl mx-auto px-6 py-5 flex justify-between items-center">
        <!-- Brand Logo -->
        <div class="text-xl font-extrabold tracking-tight cursor-pointer" @click="resetToHome()">
            NexaStore <span class="text-sky-400">PC</span>
        </div>

        <!-- Navbar Elements -->
        <nav class="hidden md:flex items-center space-x-8 text-sm font-medium">
            <!-- Mega Menu Trigger -->
            <div class="relative py-2" @mouseenter="megaMenuOpen = true" @mouseleave="megaMenuOpen = false">
                <a href="#" class="hover:text-sky-400 transition flex items-center space-x-1 py-2">
                    <span>All Hardware</span>
                    <svg class="w-4 h-4 transition-transform duration-200" :class="megaMenuOpen ? 'rotate-180' : ''" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"></path></svg>
                </a>

                <!-- MEGA MENU POPUP -->
                <div x-show="megaMenuOpen" 
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 translate-y-2"
                     x-transition:enter-end="opacity-100 translate-y-0"
                     x-transition:leave="transition ease-in duration-150"
                     x-transition:leave-start="opacity-100 translate-y-0"
                     x-transition:leave-end="opacity-0 translate-y-2"
                     class="absolute left-1/2 -translate-x-1/4 mt-2 w-[820px] bg-white text-slate-800 rounded-xl shadow-2xl p-6 grid grid-cols-5 gap-6 border border-gray-100">
                    
                    <div>
                        <h4 class="text-blue-700 font-bold text-xs uppercase tracking-wider mb-2">Processors</h4>
                        <ul class="space-y-1.5 text-xs text-gray-600">
                            <li><a href="#" @click="filterByCategory('processor'); selectedBrands=['Intel']" class="hover:text-blue-600">Intel Core i9 Series</a></li>
                            <li><a href="#" @click="filterByCategory('processor'); selectedBrands=['Intel']" class="hover:text-blue-600">Intel Core i7 Series</a></li>
                            <li><a href="#" @click="filterByCategory('processor'); selectedBrands=['AMD']" class="hover:text-blue-600">AMD Ryzen 9</a></li>
                            <li><a href="#" @click="filterByCategory('processor'); selectedBrands=['AMD']" class="hover:text-blue-600">AMD Ryzen 7</a></li>
                            <li><a href="#" @click="filterByCategory('processor')" class="hover:text-blue-600">Workstation CPUs</a></li>
                        </ul>
                    </div>

                    <div>
                        <h4 class="text-blue-700 font-bold text-xs uppercase tracking-wider mb-2">Graphic Cards</h4>
                        <ul class="space-y-1.5 text-xs text-gray-600">
                            <li><a href="#" @click="filterByCategory('gpu'); selectedBrands=['NVIDIA']" class="hover:text-blue-600">NVIDIA RTX 4090</a></li>
                            <li><a href="#" @click="filterByCategory('gpu'); selectedBrands=['NVIDIA']" class="hover:text-blue-600">NVIDIA RTX 4080</a></li>
                            <li><a href="#" @click="filterByCategory('gpu'); selectedBrands=['AMD']" class="hover:text-blue-600">AMD RX 7900 XTX</a></li>
                            <li><a href="#" @click="filterByCategory('gpu')" class="hover:text-blue-600">Professional GPUs</a></li>
                            <li><a href="#" @click="filterByCategory('gpu')" class="hover:text-blue-600">Budget GPUs</a></li>
                        </ul>
                    </div>

                    <div>
                        <h4 class="text-blue-700 font-bold text-xs uppercase tracking-wider mb-2">Memory</h4>
                        <ul class="space-y-1.5 text-xs text-gray-600">
                            <li><a href="#" @click="filterByCategory('memory')" class="hover:text-blue-600">DDR5 Kits</a></li>
                            <li><a href="#" @click="filterByCategory('memory')" class="hover:text-blue-600">DDR4 Kits</a></li>
                            <li><a href="#" @click="filterByCategory('memory')" class="hover:text-blue-600">ECC RAM</a></li>
                            <li><a href="#" @click="filterByCategory('memory')" class="hover:text-blue-600">SO-DIMM</a></li>
                            <li><a href="#" @click="filterByCategory('memory')" class="hover:text-blue-600">Server Memory</a></li>
                        </ul>
                    </div>

                    <div>
                        <h4 class="text-blue-700 font-bold text-xs uppercase tracking-wider mb-2">Storage</h4>
                        <ul class="space-y-1.5 text-xs text-gray-600">
                            <li><a href="#" @click="filterByCategory('storage')" class="hover:text-blue-600">NVMe PCIe 5.0</a></li>
                            <li><a href="#" @click="filterByCategory('storage')" class="hover:text-blue-600">NVMe PCIe 4.0</a></li>
                            <li><a href="#" @click="filterByCategory('storage')" class="hover:text-blue-600">SATA SSDs</a></li>
                            <li><a href="#" @click="filterByCategory('storage')" class="hover:text-blue-600">HDDs</a></li>
                            <li><a href="#" @click="filterByCategory('storage')" class="hover:text-blue-600">Enterprise NAS</a></li>
                        </ul>
                    </div>

                    <div class="col-span-5 border-t border-gray-100 pt-4 grid grid-cols-4 gap-6">
                        <div>
                            <h4 class="text-blue-700 font-bold text-xs uppercase tracking-wider mb-2">Motherboards</h4>
                            <ul class="space-y-1.5 text-xs text-gray-600">
                                <li><a href="#" @click="filterByCategory('motherboard')" class="hover:text-blue-600">Z790 Platform</a></li>
                                <li><a href="#" @click="filterByCategory('motherboard')" class="hover:text-blue-600">X670E Platform</a></li>
                                <li><a href="#" @click="filterByCategory('motherboard')" class="hover:text-blue-600">B760 Platform</a></li>
                                <li><a href="#" @click="filterByCategory('motherboard')" class="hover:text-blue-600">B650 Platform</a></li>
                                <li><a href="#" @click="filterByCategory('motherboard')" class="hover:text-blue-600">Server Boards</a></li>
                            </ul>
                        </div>
                        <div>
                            <h4 class="text-blue-700 font-bold text-xs uppercase tracking-wider mb-2">Power Supplies</h4>
                            <ul class="space-y-1.5 text-xs text-gray-600">
                                <li><a href="#" class="hover:text-blue-600">1000W+ Titanium</a></li>
                                <li><a href="#" class="hover:text-blue-600">850W Platinum</a></li>
                                <li><a href="#" class="hover:text-blue-600">750W Gold</a></li>
                                <li><a href="#" class="hover:text-blue-600">Modular PSUs</a></li>
                                <li><a href="#" class="hover:text-blue-600">SFX Form Factor</a></li>
                            </ul>
                        </div>
                        <div>
                            <h4 class="text-blue-700 font-bold text-xs uppercase tracking-wider mb-2">Cooling</h4>
                            <ul class="space-y-1.5 text-xs text-gray-600">
                                <li><a href="#" class="hover:text-blue-600">360mm AIOs</a></li>
                                <li><a href="#" class="hover:text-blue-600">240mm AIOs</a></li>
                                <li><a href="#" class="hover:text-blue-600">Tower Coolers</a></li>
                                <li><a href="#" class="hover:text-blue-600">Case Fans</a></li>
                                <li><a href="#" class="hover:text-blue-600">Thermal Paste</a></li>
                            </ul>
                        </div>
                        <div>
                            <h4 class="text-blue-700 font-bold text-xs uppercase tracking-wider mb-2">PC Cases</h4>
                            <ul class="space-y-1.5 text-xs text-gray-600">
                                <li><a href="#" class="hover:text-blue-600">Full Tower</a></li>
                                <li><a href="#" class="hover:text-blue-600">Mid Tower</a></li>
                                <li><a href="#" class="hover:text-blue-600">Mini-ITX</a></li>
                                <li><a href="#" class="hover:text-blue-600">Tempered Glass</a></li>
                                <li><a href="#" class="hover:text-blue-600">Server Chassis</a></li>
                            </ul>
                        </div>
                    </div>

                    <!-- Trending Row -->
                    <div class="col-span-5 border-t border-gray-100 pt-4">
                        <h4 class="text-blue-700 font-bold text-xs uppercase tracking-wider mb-3">Trending</h4>
                        <div class="flex gap-2">
                            <a href="#" @click="filterByCategory('gpu'); selectedBrands=['NVIDIA']" class="px-3 py-1.5 border border-blue-300 bg-blue-50 hover:bg-blue-100 rounded-lg text-xs font-bold text-blue-700 transition">RTX 4090</a>
                            <a href="#" @click="filterByCategory('processor')" class="px-3 py-1.5 border border-gray-300 bg-gray-50 hover:bg-gray-100 rounded-lg text-xs font-bold text-slate-700 transition">i9-14900K</a>
                            <a href="#" @click="filterByCategory('memory')" class="px-3 py-1.5 border border-sky-300 bg-sky-50 hover:bg-sky-100 rounded-lg text-xs font-bold text-sky-700 transition">DDR5 7200MHz</a>
                            <a href="#" @click="filterByCategory('gpu'); selectedBrands=['NVIDIA']" class="px-3 py-1.5 border border-emerald-300 bg-emerald-50 hover:bg-emerald-100 rounded-lg text-xs font-bold text-emerald-700 transition">RTX 4090</a>
                        </div>
                    </div>
                </div>
            </div>

                <a href="#" @click="filterAndScroll('processor')" class="hover:text-sky-400 transition">Processors</a>
                <a href="#" @click="filterBrandAndScroll('NVIDIA')" class="hover:text-sky-400 transition">GPUs</a>
                <a href="#" @click="filterAndScroll('motherboard')" class="hover:text-sky-400 transition">Motherboards</a>
            <a href="#" class="hover:text-sky-400 transition">Deals</a>
        </nav>

        <!-- Action Controls -->
        <div class="flex items-center space-x-3">
            <div x-show="searchOpen" x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" class="flex items-center">
                <div class="relative">
                    <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    <input type="text" x-model="searchQuery" placeholder="Search..." class="w-48 pl-9 pr-3 py-2 bg-blue-900/40 border border-blue-700/50 rounded-lg text-xs font-medium text-white placeholder-blue-300 outline-none focus:border-blue-400 transition">
                    <button x-show="searchQuery" @click="searchQuery = ''" class="absolute right-2 top-1/2 -translate-y-1/2 text-blue-300 hover:text-white">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>
            </div>
            <button @click="searchOpen = !searchOpen; if(!searchOpen) searchQuery = ''" class="hover:text-sky-400 transition relative shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </button>
            <button class="hover:text-sky-400 transition relative">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                <span class="absolute -top-1.5 -right-1.5 bg-sky-400 text-blue-900 font-bold text-[10px] w-4 h-4 rounded-full flex items-center justify-center">0</span>
            </button>
            <button class="hover:text-sky-400 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
            </button>
        </div>
    </div>
</header>
