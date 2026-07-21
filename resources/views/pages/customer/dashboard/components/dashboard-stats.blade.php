<h3 class="font-bold text-xl mb-4" data-i18n="dash.quick">
        Quick Statistics
    </h3>

    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 stagger">

        <x-stat-card
            title="Wishlist"
            :value="$stats['wishlist']"
            icon="https://cdn.jsdelivr.net/npm/lucide-static@0.460.0/icons/heart.svg"
            color="bg-red-100"
            href="{{ route('wishlist') }}" />

        <x-stat-card
            title="Cart"
            :value="$stats['cart']"
            icon="https://cdn.jsdelivr.net/npm/lucide-static@0.460.0/icons/shopping-cart.svg"
            color="bg-sky-100"
            href="{{ route('cart') }}" />

        <x-stat-card
            title="Pending Orders"
            :value="$stats['pending']"
            icon="https://cdn.jsdelivr.net/npm/lucide-static@0.460.0/icons/clock.svg"
            color="bg-yellow-100"
            href="{{ route('orders') }}" />

        <x-stat-card
            title="Completed Orders"
            :value="$stats['completed']"
            icon='<svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>'
            color="bg-green-100"
            href="{{ route('history') }}" />

    </div>
