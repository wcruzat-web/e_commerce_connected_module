<div x-transition class="space-y-12">
    <!-- Breadcrumbs component segment -->
    <nav class="text-xs font-semibold text-gray-400 flex items-center space-x-2">
        <span class="hover:text-blue-600 cursor-pointer" @click="view = 'catalog'">Shop</span>
        <span>/</span>
        <span class="hover:text-blue-600 cursor-pointer" @click="filterByBrand(selectedProduct.brand)">GPUs</span>
        <span>/</span>
        <span class="text-slate-900" x-text="selectedProduct.name"></span>
    </nav>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 bg-white rounded-2xl border border-gray-200 p-8 shadow-sm">
        <!-- Media Gallery Viewer -->
        <div class="relative flex flex-col justify-center items-center border border-gray-100 rounded-xl p-6 bg-gray-50/50">
            <span x-show="!selectedProduct.inStock" class="absolute top-4 left-4 bg-red-600 text-white text-[10px] font-black px-2.5 py-1 rounded-full uppercase tracking-wider">Out of Stock</span>
            <img :src="selectedProduct.image" :alt="selectedProduct.name" class="max-h-[340px] object-contain drop-shadow-xl transform hover:scale-105 transition duration-300">
        </div>

        <!-- Info panel config parameters -->
        <div class="space-y-6">
            <div>
                <span class="bg-blue-900 text-white text-[10px] font-black tracking-widest uppercase px-2.5 py-1 rounded" x-text="selectedProduct.brand"></span>
                <p class="text-xs font-mono text-gray-400 mt-2" x-text="'SKU: ' + selectedProduct.sku"></p>
                <h1 class="text-2xl font-black text-slate-900 tracking-tight mt-1" x-text="selectedProduct.name"></h1>
            </div>

            <div class="flex items-baseline space-x-3 border-y border-gray-100 py-4">
                <span class="text-3xl font-black text-slate-900" x-text="'₱' + selectedProduct.price.toLocaleString()"></span>
            </div>

            <!-- Specs Quick Grid Feature Blocks -->
            <div x-show="selectedProduct.atAGlance && selectedProduct.atAGlance.length">
                <h4 class="text-xs font-bold uppercase text-gray-400 tracking-wider mb-3">At A Glance</h4>
                <div class="grid grid-cols-3 gap-3">
                    <template x-for="item in selectedProduct.atAGlance" :key="item.label">
                        <div class="border border-gray-200 rounded-xl p-3 text-center bg-gray-50/50">
                            <span class="block text-[10px] text-gray-400 font-bold uppercase" x-text="item.label"></span>
                            <span class="text-xs font-black text-slate-800" x-text="item.value"></span>
                        </div>
                    </template>
                </div>
            </div>

            <!-- Cart Interface Action Deck -->
            <div class="flex items-center space-x-4 pt-2">
                <div class="flex items-center border border-gray-300 rounded-lg overflow-hidden bg-white shadow-sm">
                    <button @mousedown="clearInterval(holdTimer); if (qty > 1) qty--; holdTimer = setInterval(() => { if (qty > 1) qty-- }, 100)" @mouseup="clearInterval(holdTimer)" @mouseleave="clearInterval(holdTimer)" class="px-3 py-2 bg-gray-50 text-slate-700 font-extrabold text-sm hover:bg-gray-100 transition select-none">-</button>
                    <span class="px-5 py-2 font-black text-sm w-12 text-center" x-text="qty"></span>
                    <button @mousedown="clearInterval(holdTimer); if (qty < 99) qty++; holdTimer = setInterval(() => { if (qty < 99) qty++ }, 100)" @mouseup="clearInterval(holdTimer)" @mouseleave="clearInterval(holdTimer)" class="px-3 py-2 bg-gray-50 text-slate-700 font-extrabold text-sm hover:bg-gray-100 transition select-none">+</button>
                </div>
                <button :disabled="!selectedProduct.inStock" 
                        :class="selectedProduct.inStock ? 'bg-sky-400 hover:bg-sky-500 text-blue-950 shadow-sm' : 'bg-gray-200 text-gray-400 cursor-not-allowed'"
                        class="flex-1 font-black text-xs py-3.5 rounded-xl shadow-md transition flex items-center justify-center space-x-2 tracking-wide uppercase"
                        @click="addToCart(selectedProduct, qty)">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 0a2 2 0 100 4 2 2 0 000-4z"></path></svg>
                    <span x-text="cartMsg === 'Added ' + selectedProduct.id ? 'Added x' + qty : 'Add to Cart'"></span>
                </button>
            </div>
        </div>
    </div>

    <!-- TABS TECHNICAL SPECS MODULE -->
    <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden shadow-sm">
        <!-- Navigation Tab Switcher Header Row -->
        <div class="flex border-b border-gray-200 bg-gray-50/50">
            <button @click="activeTab = 'specs'" :class="activeTab === 'specs' ? 'border-blue-600 text-blue-600 bg-white font-bold' : 'border-transparent text-gray-500 hover:text-slate-800'" class="px-6 py-4 border-b-2 font-semibold text-xs tracking-wide transition uppercase">Full Specifications</button>
            <button @click="activeTab = 'compatibility'" :class="activeTab === 'compatibility' ? 'border-blue-600 text-blue-600 bg-white font-bold' : 'border-transparent text-gray-500 hover:text-slate-800'" class="px-6 py-4 border-b-2 font-semibold text-xs tracking-wide transition uppercase">Compatibility</button>
            <button @click="activeTab = 'reviews'" :class="activeTab === 'reviews' ? 'border-blue-600 text-blue-600 bg-white font-bold' : 'border-transparent text-gray-500 hover:text-slate-800'" class="px-6 py-4 border-b-2 font-semibold text-xs tracking-wide transition uppercase">Reviews</button>
        </div>

        <!-- Tab Panel Body Blocks -->
        <div class="p-6">
            <!-- SUB-TAB 1: FULL SPECIFICATIONS -->
            <div x-show="activeTab === 'specs'" class="grid grid-cols-1 md:grid-cols-2 gap-x-12 gap-y-8 pt-4 pb-6">
                <template x-for="section in selectedProduct.detailSpecs" :key="section.section">
                    <div class="space-y-3">
                        <h4 class="text-xs font-bold text-blue-600 uppercase tracking-wider pb-1" x-text="section.section"></h4>
                        <div class="space-y-1 text-[11px]">
                            <template x-for="(item, i) in section.items" :key="item.label">
                                <div class="flex justify-between items-center px-3 py-2" :class="i % 2 === 0 ? 'bg-blue-50/50 rounded' : 'rounded'">
                                    <span class="text-gray-400 font-medium" x-text="item.label"></span>
                                    <span class="font-semibold text-slate-800" x-text="item.value"></span>
                                </div>
                            </template>
                        </div>
                    </div>
                </template>
            </div>

            <!-- SUB-TAB 2: COMPATIBILITY -->
            <div x-show="activeTab === 'compatibility'" class="grid grid-cols-1 md:grid-cols-3 gap-6 pt-4 pb-6">
                <!-- RECOMMENDED PSU CARD -->
                <div class="bg-white rounded-xl p-5 border border-gray-300 shadow-[0_4px_12px_rgba(0,0,0,0.04)]">
                    <h4 class="text-xs font-bold text-blue-800 tracking-wide mb-4">Recommended PSU</h4>
                    <ul class="space-y-3 text-[11px] font-semibold text-slate-700">
                        <template x-for="psu in selectedProduct.compatiblePsu" :key="psu">
                            <li class="flex items-center space-x-2">
                                <svg class="w-4 h-4 text-emerald-500 shrink-0" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"></path></svg>
                                <span class="text-gray-400 font-normal">-</span>
                                <span x-text="psu"></span>
                            </li>
                        </template>
                        <li x-show="!selectedProduct.compatiblePsu.length" class="text-xs text-gray-400 italic">N/A</li>
                    </ul>
                </div>

                <!-- COMPATIBLE CASES CARD -->
                <div class="bg-white rounded-xl p-5 border border-gray-300 shadow-[0_4px_12px_rgba(0,0,0,0.04)]">
                    <h4 class="text-xs font-bold text-blue-800 tracking-wide mb-4">Compatible Cases</h4>
                    <ul class="space-y-3 text-[11px] font-semibold text-slate-700">
                        <template x-for="c in selectedProduct.compatibleCases" :key="c">
                            <li class="flex items-center space-x-2">
                                <svg class="w-4 h-4 text-emerald-500 shrink-0" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"></path></svg>
                                <span class="text-gray-400 font-normal">-</span>
                                <span x-text="c"></span>
                            </li>
                        </template>
                        <li x-show="!selectedProduct.compatibleCases.length" class="text-xs text-gray-400 italic">N/A</li>
                    </ul>
                </div>

                <!-- PLATFORM SUPPORT CARD -->
                <div class="bg-white rounded-xl p-5 border border-gray-300 shadow-[0_4px_12px_rgba(0,0,0,0.04)]">
                    <h4 class="text-xs font-bold text-blue-800 tracking-wide mb-4">Platform Support</h4>
                    <ul class="space-y-3 text-[11px] font-semibold text-slate-700">
                        <template x-for="plt in selectedProduct.platformSupport" :key="plt">
                            <li class="flex items-center space-x-2">
                                <svg class="w-4 h-4 text-emerald-500 shrink-0" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"></path></svg>
                                <span class="text-gray-400 font-normal">-</span>
                                <span x-text="plt"></span>
                            </li>
                        </template>
                    </ul>
                </div>
            </div>

            <!-- SUB-TAB 3: REVIEWS -->
            <div x-show="activeTab === 'reviews'" class="space-y-8 pt-6 pb-6">
                <!-- Rating Summary & Progress Bars -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8 items-center">
                    <div class="flex flex-col items-center justify-center text-center md:border-r border-gray-100 py-4">
                        <h3 class="text-5xl font-black text-slate-800 tracking-tight" x-text="selectedProduct.rating"></h3>
                        <p class="text-[10px] font-bold text-gray-400 mt-1 tracking-wide" x-text="selectedProduct.reviewCount + ' verified reviews'"></p>
                    </div>
                    <div class="md:col-span-2 space-y-2.5 px-2 md:px-6">
                        <template x-for="(pct, starIdx) in selectedProduct.reviewDistribution" :key="starIdx">
                            <div class="flex items-center space-x-3 text-[11px] font-bold text-gray-400">
                                <span class="w-3 select-none" x-text="5 - starIdx"></span>
                                <div class="flex-1 h-2 bg-gray-100 rounded-full overflow-hidden">
                                    <div class="h-full bg-cyan-400 rounded-full" :style="'width: ' + pct + '%'"></div>
                                </div>
                                <span class="w-7 text-right" x-text="pct + '%'"></span>
                            </div>
                        </template>
                    </div>
                </div>

                <!-- User Reviews -->
                <div class="border-t border-gray-100 pt-8">
                    <h4 class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-5">Comments</h4>

                    <template x-for="(review, idx) in userReviews" :key="idx">
                        <div class="bg-blue-50 rounded-xl p-5 border border-blue-200 mb-4">
                            <div class="flex items-center space-x-3 mb-2">
                                <div class="w-8 h-8 bg-blue-600 rounded-full flex items-center justify-center text-white font-bold text-xs" x-text="review.initials"></div>
                                <div>
                                    <p class="text-xs font-bold text-slate-800" x-text="review.name"></p>
                                    <p class="text-[9px] text-gray-400" x-text="review.createdAt"></p>
                                </div>
                            </div>
                            <p class="text-[10px] text-gray-500 leading-relaxed" x-text="review.comment"></p>
                        </div>
                    </template>

                    <div class="flex items-start space-x-3">
                        <div class="w-8 h-8 bg-blue-600 rounded-full flex items-center justify-center text-white font-bold text-xs shrink-0">You</div>
                        <div class="flex-1">
                            <textarea x-model="newReview" placeholder="Share your thoughts about this product..." class="w-full text-xs p-3 bg-gray-50 border border-gray-200 rounded-xl resize-none outline-none focus:border-blue-400 focus:bg-white transition" rows="2"></textarea>
                            <div class="flex justify-between items-center mt-2">
                                <span class="text-[9px] text-gray-400">Your review will be posted publicly</span>
                                <button @click="submitReview()" :disabled="!newReview.trim()" :class="newReview.trim() ? 'bg-blue-600 hover:bg-blue-700 text-white' : 'bg-gray-200 text-gray-400 cursor-not-allowed'" class="text-xs font-bold px-4 py-2 rounded-lg transition">Submit</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
