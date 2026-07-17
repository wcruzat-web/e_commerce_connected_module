<div class="bg-white rounded-2xl border border-gray-200/80 p-5 shadow-sm flex flex-col justify-between transition hover:shadow-md relative"
     :class="!product.inStock && 'opacity-70 bg-gray-50/50'">
    <div>
        <!-- Badges row -->
        <div class="flex justify-between items-center mb-3">
            <span :class="product.badgeClass" class="text-[9px] font-black uppercase px-2.5 py-0.5 rounded tracking-wide text-white" x-text="product.badge"></span>
        </div>

        <!-- Img asset -->
        <div class="h-36 w-full flex items-center justify-center mb-4 bg-transparent cursor-pointer relative" @click="openProductDetail(product)">
            <div x-show="!product.inStock" class="absolute inset-0 bg-slate-900/5 backdrop-blur-[1px] flex flex-col items-center justify-center rounded-xl z-10">
                <span class="bg-white/95 px-4 py-2 rounded-lg font-black text-xs tracking-widest text-slate-800 shadow">OUT OF STOCK</span>
            </div>
            <img :src="product.image" :alt="product.name" class="h-full object-contain max-w-[85%]">
        </div>

        <!-- Metadata info specs descriptors -->
        <p class="text-[9px] uppercase font-bold text-gray-400 tracking-wider" x-text="product.categoryMeta"></p>
        <h3 class="font-bold text-xs mt-0.5 tracking-tight text-slate-900 hover:text-blue-600 transition cursor-pointer" 
            @click="openProductDetail(product)"
            x-text="product.name"></h3>

        <div class="flex flex-wrap gap-1 mt-2">
            <template x-for="spec in product.specs">
                <span class="bg-gray-100 text-[8.5px] text-gray-500 px-1.5 py-0.5 rounded font-medium" x-text="spec"></span>
            </template>
        </div>
    </div>

    <!-- Bottom CTA & pricing interface tier -->
    <div class="mt-4 pt-3 border-t border-gray-100 flex items-center justify-between">
        <div>
            <span class="text-sm font-black text-slate-900" x-text="'₱' + product.price.toLocaleString()"></span>
        </div>
        <button :disabled="!product.inStock" 
                :class="product.inStock ? 'bg-sky-400 hover:bg-sky-500 text-blue-950 shadow-sm' : 'bg-gray-200 text-gray-400 cursor-not-allowed'"
                class="font-extrabold text-[10px] px-3 py-2 rounded-lg transition tracking-wide"
                @click="addToCart(product, 1)">
            <span x-text="cartMsg && cartMsg === 'Added ' + product.id ? 'Added!' : 'Add to Cart'"></span>
        </button>
    </div>
</div>
