<div class="flex items-center justify-between flex-wrap gap-4 mb-6 animate-fade-up">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Order History</h1>
            <p class="text-gray-500 mt-1">Everything you've received and completed.</p>
        </div>

        <div class="relative">
            <img src="https://cdn.jsdelivr.net/npm/lucide-static@0.460.0/icons/search.svg"
                 class="w-4 h-4 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2" alt="">
            <input type="text" id="search-input"
                   placeholder="Search by order # or product..."
                   value="{{ request('search') }}"
                   class="border border-gray-200 rounded-lg pl-9 pr-4 py-2.5 w-72 text-sm focus:outline-none focus:ring-2 focus:ring-sky-300 transition">
        </div>//this is comment
    </div>
