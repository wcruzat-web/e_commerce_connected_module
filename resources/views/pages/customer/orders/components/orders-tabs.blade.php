<div class="flex gap-8 mt-2 font-semibold text-gray-400 border-b border-gray-200 sticky top-0 bg-gray-100 z-10 py-1" id="tabs">
        <button type="button" onclick="filterOrders('all', this)"
                class="tab-btn pb-3 border-b-2 border-sky-500 text-sky-600">
            All
        </button>
        <button type="button" onclick="filterOrders('processing', this)"
                class="tab-btn pb-3 border-b-2 border-transparent">
            Processing
        </button>
        <button type="button" onclick="filterOrders('delivered', this)"
                class="tab-btn pb-3 border-b-2 border-transparent">
            Delivered
        </button>
    </div>
