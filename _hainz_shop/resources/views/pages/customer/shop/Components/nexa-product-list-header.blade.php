<div class="flex justify-between items-center mb-6">
    <p class="text-sm font-semibold text-slate-600">
        <span class="text-slate-900 font-extrabold text-base" x-text="filteredProducts.length"></span> Product
    </p>
    <div class="flex items-center space-x-2">
        <span class="text-sm font-medium text-slate-800">Sort by:</span>
        <select x-model="sortBy" class="text-xs font-semibold bg-white border border-gray-300 rounded-lg px-3 py-2 text-slate-700 outline-none shadow-sm w-32 sm:w-auto">
            <option>Featured</option>
            <option>Price: Low to High</option>
            <option>Price: High to Low</option>
        </select>
    </div>
</div>
