@props(['id' => 'page-product', 'class' => 'grid grid-cols-1 xl:grid-cols-[1fr_300px] gap-5'])

<div id="{{ $id }}" class="{{ $class }}">
  <div>
    <div class="text-xs text-slate-400 mb-2">
      <span class="hover:text-slate-600 cursor-pointer">Admin</span> &nbsp;>&nbsp; <span class="text-slate-500">Product Display Management</span>
    </div>

    <div class="flex items-start justify-between mb-5">
      <div>
        <h1 class="text-2xl font-bold text-slate-900">Product Display Management</h1>
        <p class="text-sm text-slate-400 mt-1">List product . Last updated <span id="lastUpdated">{{ now()->format('M d, Y') }}</span></p>
      </div>
      <div class="flex items-center gap-2">
        <button id="exportBtn" class="px-4 py-2 text-sm font-medium border border-gray-300 rounded-lg text-slate-600 hover:bg-gray-50 transition">Export</button>
        <button id="addProductBtn" class="px-4 py-2 text-sm font-medium bg-sky-500 hover:bg-sky-600 text-white rounded-lg transition shadow-sm">+ Add Product</button>
      </div>
    </div>

    <div class="bg-white rounded-xl border border-gray-200 p-4">
      <div class="flex flex-wrap items-center gap-3 mb-4">
        <div class="relative flex-1 min-w-[220px]">
          <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="7"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
          <input id="searchInput" type="text" placeholder="search product, SKUs..." class="w-full pl-9 pr-3 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-sky-300">
        </div>
        <select id="categoryFilter" class="text-sm border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-sky-300">
          <option value="All">All</option>
          <option value="GPU">GPU</option>
          <option value="CPU">CPU</option>
          <option value="Motherboard">Motherboard</option>
          <option value="Memory">Memory</option>
          <option value="Cooling">Cooling</option>
        </select>
        <select id="brandFilter" class="text-sm border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-sky-300">
          <option value="All Brands">All Brands</option>
          <option value="NVIDIA">NVIDIA</option>
          <option value="Intel">Intel</option>
          <option value="ASUS">ASUS</option>
          <option value="Corsair">Corsair</option>
          <option value="NZXT">NZXT</option>
        </select>
        <select id="stockFilter" class="text-sm border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-sky-300">
          <option value="All">All Stock</option>
          <option value="In Stock">In Stock</option>
          <option value="Low Stock">Low Stock</option>
          <option value="Out of Stock">Out of Stock</option>
        </select>
      </div>

      <div class="overflow-x-auto">
        <table class="w-full text-sm">
          <thead>
            <tr class="text-left text-slate-400 border-b border-gray-100">
              <th class="py-2 pr-2 font-medium w-8"><input type="checkbox" id="selectAll" class="rounded border-gray-300"></th>
              <th class="py-2 pr-4 font-medium">PRODUCT</th>
              <th class="py-2 pr-4 font-medium">SKU</th>
              <th class="py-2 pr-4 font-medium">CATEGORY</th>
              <th class="py-2 pr-4 font-medium">PRICE</th>
              <th class="py-2 pr-4 font-medium">STOCK</th>
              <th class="py-2 pr-4 font-medium">STATUS</th>
              <th class="py-2 pr-2 font-medium">ACTION</th>
            </tr>
          </thead>
          <tbody id="productTableBody" class="divide-y divide-gray-100"></tbody>
        </table>
      </div>

      <div class="flex items-center justify-between mt-4 text-sm">
        <div class="text-slate-400" id="showingText">Showing 1-5 of 8 product.</div>
        <div class="flex items-center gap-1" id="paginationControls"></div>
      </div>
    </div>
  </div>

  <div class="flex flex-col gap-4">
    <div class="bg-white rounded-xl border border-gray-200 p-4">
      <div class="flex items-center gap-2 text-sm font-semibold text-slate-700 mb-3">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-sky-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 16v-4"/><circle cx="12" cy="8" r=".5" fill="currentColor"/></svg>
        Product Preview
      </div>
      <div id="previewPane" class="border border-dashed border-gray-200 rounded-lg h-40 flex flex-col items-center justify-center text-center px-4">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-slate-300 mb-2" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><path d="M21 15l-5-5L5 21"/></svg>
        <p class="text-xs text-slate-400">click the eye icon on a product row to<br>preview it here</p>
      </div>
    </div>

    <div class="bg-white rounded-xl border border-gray-200 p-4">
      <div class="flex items-center gap-2 text-sm font-semibold text-slate-700 mb-3">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-amber-400" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87L18.18 22 12 18.56 5.82 22 7 14.14l-5-4.87 6.91-1.01L12 2z"/></svg>
        Featured Products
      </div>
      <div id="featuredList" class="flex flex-col gap-2"></div>
    </div>

    <div class="bg-white rounded-xl border border-gray-200 p-4">
      <div class="flex items-center justify-between mb-3">
        <div class="text-sm font-semibold text-slate-700">Promo Banners</div>
        <button id="addPromoBtn" class="text-sky-500 hover:text-sky-600">
          <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 20l9-9-9-9-9 9 9 9z" opacity="0"/><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
        </button>
      </div>
      <div id="promoList" class="flex flex-col gap-2"></div>
    </div>
  </div>
</div>
