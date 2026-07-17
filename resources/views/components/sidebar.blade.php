<aside id="sidebar" class="w-64 min-h-screen bg-[#233B8E] text-white flex flex-col shrink-0 fixed lg:relative z-40 -translate-x-full lg:translate-x-0 transition-transform duration-200">

    <!-- Logo -->
    <div class="px-8 pt-8 pb-10 flex items-center justify-between">
        <h1 class="text-4xl font-bold">
            Shop<span class="text-sky-400">Ease</span>
        </h1>
        <button id="sidebar-close" class="lg:hidden text-white/70 hover:text-white p-1" aria-label="Close sidebar">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
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
            <button type="submit"
                class="block w-full text-left rounded-full px-5 py-2 hover:bg-red-500 transition" data-i18n="nav.logout">
                Logout
            </button>
        </form>

    </div>

</aside>

<!-- Overlay for mobile -->
<div id="sidebar-overlay" class="hidden fixed inset-0 bg-black/40 z-30 lg:hidden"></div>

<script>
(function() {
    var sidebar = document.getElementById('sidebar');
    var overlay = document.getElementById('sidebar-overlay');
    if (!sidebar) return;

    window.toggleSidebar = function() {
        sidebar.classList.toggle('-translate-x-full');
        sidebar.classList.toggle('translate-x-0');
        if (overlay) overlay.classList.toggle('hidden');
    };

    document.getElementById('sidebar-close')?.addEventListener('click', window.toggleSidebar);
    overlay?.addEventListener('click', window.toggleSidebar);
})();
</script>
