<div class="flex gap-8 mt-2 font-semibold text-gray-400 border-b border-gray-200 sticky top-0 bg-gray-100 z-10 py-1" id="tabs">
        <a href="?status=all"
           class="tab-btn pb-3 border-b-2 {{ ($status ?? 'all') === 'all' ? 'border-sky-500 text-sky-600' : 'border-transparent' }}">
            All
        </a>
        <a href="?status=processing"
           class="tab-btn pb-3 border-b-2 {{ ($status ?? 'all') === 'processing' ? 'border-sky-500 text-sky-600' : 'border-transparent' }}">
            Processing
        </a>
        <a href="?status=delivered"
           class="tab-btn pb-3 border-b-2 {{ ($status ?? 'all') === 'delivered' ? 'border-sky-500 text-sky-600' : 'border-transparent' }}">
            Delivered
        </a>
    </div>
