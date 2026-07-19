{{-- HAINZ — toolbar: sort dropdown with URLSearchParams (ERPV1.4) --}}
<div class="flex justify-between items-center mb-6">
    <p class="text-sm font-semibold text-slate-600">
        <span class="text-slate-900 font-extrabold text-base">{{ count($products) }}</span> Products
    </p>
    <div class="flex items-center space-x-2">
        <span class="text-sm font-medium text-slate-800">Sort by:</span>
        <select name="sort" onchange="var p=new URLSearchParams(location.search);if(this.value)p.set('sort',this.value);else p.delete('sort');var q=p.toString();location=location.pathname+(q?'?'+q:'');" class="text-xs font-semibold bg-white border border-gray-300 rounded-lg px-3 py-2 text-slate-700 outline-none w-36 shadow-sm">
            <option value="" {{ in_array($sort ?? '', ['', 'featured']) ? 'selected' : '' }}>Featured</option>
            <option value="price_asc" {{ ($sort ?? '') === 'price_asc' ? 'selected' : '' }}>Price: Low to High</option>
            <option value="price_desc" {{ ($sort ?? '') === 'price_desc' ? 'selected' : '' }}>Price: High to Low</option>
        </select>
    </div>
</div>
