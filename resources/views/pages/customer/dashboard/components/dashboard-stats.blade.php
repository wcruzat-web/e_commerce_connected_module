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
            icon="https://cdn.jsdelivr.net/npm/lucide-static@0.460.0/icons/check-circle.svg"
            color="bg-green-100"
            href="{{ route('history') }}" />

    </div>
