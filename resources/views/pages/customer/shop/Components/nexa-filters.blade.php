<aside class="space-y-6">
    <div class="bg-white rounded-[20px] border border-gray-200/80 p-6 shadow-[0_4px_20px_rgba(0,0,0,0.03)]">
        <!-- Header with layout toggle icon style -->
        <div class="flex justify-between items-center border-b border-gray-100 pb-4 mb-5">
            <h2 class="font-bold text-base tracking-tight text-slate-800">Filters</h2>
            <svg class="w-4 h-4 text-slate-600 cursor-pointer" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 6h9.75M10.5 6a1.5 1.5 0 11-3 0m3 0a1.5 1.5 0 10-3 0M3.75 6H7.5m3 12h9.75m-9.75 0a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m-3.75 0H7.5m9-6h3.75m-3.75 0a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m-9.75 0h9.75"></path>
            </svg>
        </div>

        <!-- Filter Section: Brand -->
        <div class="mb-6">
            <h3 class="text-sm font-bold text-slate-800 mb-3">Brand</h3>
            <div class="space-y-3">
                <template x-for="b in ['NVIDIA', 'AMD', 'Intel', 'ASUS', 'MSI', 'Gigabyte', 'Corsair', 'Samsung']">
                    <label class="flex items-center space-x-3 text-xs font-semibold text-slate-600 cursor-pointer hover:text-slate-900 select-none">
                        <input type="checkbox" :value="b" x-model="selectedBrands" class="sr-only">
                        <div class="w-[18px] h-[18px] rounded-full border-2 flex items-center justify-center shrink-0 transition"
                             :class="selectedBrands.includes(b) ? 'bg-blue-600 border-blue-600' : 'border-gray-300'">
                            <svg x-show="selectedBrands.includes(b)" class="w-3 h-3 text-white" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                        </div>
                        <span x-text="b"></span>
                    </label>
                </template>
            </div>
        </div>

        <!-- Filter Section: Chipset -->
        <div class="border-t border-gray-100 pt-5 mb-6">
            <h3 class="text-sm font-bold text-slate-800 mb-3">Chipset</h3>
            <div class="space-y-3">
                <template x-for="c in ['Z790', 'Z690', 'X670E', 'X670', 'B760', 'B650']">
                    <label class="flex items-center space-x-3 text-xs font-semibold text-slate-600 cursor-pointer hover:text-slate-900 select-none">
                        <input type="checkbox" :value="c" x-model="selectedChipsets" class="sr-only">
                        <div class="w-[18px] h-[18px] rounded-full border-2 flex items-center justify-center shrink-0 transition"
                             :class="selectedChipsets.includes(c) ? 'bg-blue-600 border-blue-600' : 'border-gray-300'">
                            <svg x-show="selectedChipsets.includes(c)" class="w-3 h-3 text-white" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                        </div>
                        <span x-text="c"></span>
                    </label>
                </template>
            </div>
        </div>

        <!-- Filter Section: CPU Socket -->
        <div class="border-t border-gray-100 pt-5 mb-6">
            <h3 class="text-sm font-bold text-slate-800 mb-3">CPU Socket</h3>
            <div class="space-y-3">
                <template x-for="s in ['LGA 1700', 'AM5', 'AM4', 'LGA4577']">
                    <label class="flex items-center space-x-3 text-xs font-semibold text-slate-600 cursor-pointer hover:text-slate-900 select-none">
                        <input type="checkbox" :value="s" x-model="selectedSockets" class="sr-only">
                        <div class="w-[18px] h-[18px] rounded-full border-2 flex items-center justify-center shrink-0 transition"
                             :class="selectedSockets.includes(s) ? 'bg-blue-600 border-blue-600' : 'border-gray-300'">
                            <svg x-show="selectedSockets.includes(s)" class="w-3 h-3 text-white" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                        </div>
                        <span x-text="s"></span>
                    </label>
                </template>
            </div>
        </div>

        <!-- Filter Section: VRAM Capacity Pill Buttons -->
        <div class="border-t border-gray-100 pt-5">
            <h3 class="text-sm font-bold text-slate-800 mb-3">VRAM Capacity</h3>
            <div class="flex flex-wrap gap-2">
                <template x-for="v in ['4GB', '8GB', '12GB', '16GB', '24GB']">
                    <button @click="toggleVram(v)" 
                            :class="selectedVram === v ? 'bg-blue-600 text-white border-blue-600 shadow-sm' : 'bg-white border-gray-300 text-slate-600 hover:border-gray-400'"
                            class="text-xs font-bold px-3.5 py-1 rounded-full border tracking-wide transition-all duration-150" 
                            x-text="v">
                    </button>
                </template>
            </div>
        </div>
    </div>
</aside>
