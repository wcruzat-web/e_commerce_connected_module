<aside class="w-64 min-h-screen bg-[#233B8E] text-white flex flex-col">

    <!-- Logo -->
    <div class="px-8 pt-8 pb-10">
        <h1 class="text-4xl font-bold">
            Shop<span class="text-sky-400">Ease</span>
        </h1>
    </div>

    <!-- Main Menu -->
    <nav class="flex-1 px-5 space-y-1 text-[15px]">

        <a href="{{ route('dashboard') }}"
            class="block rounded-full px-5 py-2 hover:bg-sky-500 transition" data-i18n="nav.dashboard">
            Dashboard
        </a>

        <a href="{{ route('home') }}"
            class="block rounded-full px-5 py-2 hover:bg-sky-500 transition" data-i18n="nav.home">
            Home
        </a>

        <a href="{{ route('products') }}"
            class="block rounded-full px-5 py-2 hover:bg-sky-500 transition" data-i18n="nav.products">
            Products
        </a>

        <a href="{{ route('wishlist') }}"
            class="block rounded-full px-5 py-2 hover:bg-sky-500 transition" data-i18n="nav.wishlist">
            Wishlist
        </a>

        <a href="{{ route('cart') }}"
            class="block rounded-full px-5 py-2 hover:bg-sky-500 transition" data-i18n="nav.cart">
            Cart
        </a>

        <a href="{{ route('orders') }}"
            class="block rounded-full px-5 py-2 hover:bg-sky-500 transition" data-i18n="nav.orders">
            My Orders
        </a>

        <a href="{{ route('history') }}"
            class="block rounded-full px-5 py-2 hover:bg-sky-500 transition" data-i18n="nav.history">
            Order History
        </a>

        <a href="{{ route('profile') }}"
            class="block rounded-full px-5 py-2 hover:bg-sky-500 transition" data-i18n="nav.profile">
            Profile
        </a>

        <a href="{{ route('addresses') }}"
            class="block rounded-full px-5 py-2 hover:bg-sky-500 transition" data-i18n="nav.addresses">
            Addresses
        </a>

        <a href="{{ route('payment-methods') }}"
            class="block rounded-full px-5 py-2 hover:bg-sky-500 transition" data-i18n="nav.payments">
            Payment Methods
        </a>

        <a href="{{ route('notifications') }}"
            class="block rounded-full px-5 py-2 hover:bg-sky-500 transition" data-i18n="nav.notifications">
            Notifications
        </a>

        <a href="{{ route('settings') }}"
            class="block rounded-full px-5 py-2 hover:bg-sky-500 transition" data-i18n="nav.settings">
            Settings
        </a>

    </nav>

    <!-- Bottom Menu -->
    <div class="px-5 pb-8 space-y-2 text-[15px] border-t border-blue-700 pt-6">

        <a href="#"
            class="block rounded-full px-5 py-2 hover:bg-sky-500 transition" data-i18n="nav.help">
            Help
        </a>

        <a href="{{ route('home') }}"
            class="block rounded-full px-5 py-2 hover:bg-sky-500 transition" data-i18n="nav.backhome">
            Back to Home
        </a>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" type="submit"
                class="block w-full text-left rounded-full px-5 py-2 hover:bg-red-500 transition" data-i18n="nav.logout">
                Logout
            </button>
        </form>

    </div>

</aside>
