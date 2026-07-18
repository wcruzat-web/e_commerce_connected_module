<div class="bg-white border border-gray-200 rounded-2xl p-5 shadow-sm">
    <h2 class="text-sm font-semibold text-gray-800 mb-3">Track Another Order</h2>

    <form method="POST" action="{{ route('orders.track') }}" class="flex items-center gap-3">
        @csrf
        <div class="relative flex-1">
            <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-gray-400">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="11" cy="11" r="7"></circle>
                    <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                </svg>
            </span>
            <input
                type="text"
                name="order_number"
                id="trackInput"
                placeholder="Order ID or Tracking Number"
                value="{{ old('order_number') }}"
                class="w-full pl-11 pr-4 py-3 text-sm rounded-xl border border-gray-200 text-gray-700 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-cyan-400 focus:border-transparent"
            >
        </div>
        <button
            type="submit"
            class="shrink-0 bg-cyan-500 hover:bg-cyan-600 transition-colors text-white text-sm font-semibold px-7 py-3 rounded-xl"
        >
            Track
        </button>
    </form>

    @error('order_number')
        <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
    @enderror
</div>
