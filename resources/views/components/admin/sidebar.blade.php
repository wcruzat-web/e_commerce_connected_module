{{--
    ERP MODULE: Admin Components
    COMPONENT: Sidebar
    DESCRIPTION: Responsive sidebar with Dashboard, Product, and Inventory links.
                 On desktop (lg+): static with collapse toggle.
                 On mobile: off-canvas overlay with backdrop.
                 Include on any admin page via:
                     @include('components.admin.sidebar')
    TODO: Replace with Auth::user()->business_name, wire nav links to routes
--}}

{{-- Mobile backdrop --}}
<div id="sidebarBackdrop" class="fixed inset-0 bg-black/30 z-30 hidden lg:hidden" onclick="toggleMobileSidebar()"></div>

<aside id="adminSidebar"
    class="fixed inset-y-0 left-0 z-40 w-60 -translate-x-full lg:translate-x-0 lg:static lg:z-auto bg-blue-900 flex flex-col justify-between transition-all duration-300">

    {{-- Collapse toggle (desktop only) --}}
    <button type="button" id="sidebarToggle"
        class="hidden lg:flex absolute -right-3 top-1/2 w-6 h-6 rounded-full border-2 border-blue-900 bg-white text-gray-500 items-center justify-center shadow-md hover:bg-gray-50 transition-colors z-10"
        onclick="toggleSidebar()">
        <svg id="sidebarChevron" xmlns="http://www.w3.org/2000/svg" class="w-3 h-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
            <polyline points="15 18 9 12 15 6"></polyline>
        </svg>
    </button>

    {{-- Mobile close button --}}
    <button type="button" class="lg:hidden absolute top-4 right-4 text-white/60 hover:text-white" onclick="toggleMobileSidebar()">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <line x1="18" y1="6" x2="6" y2="18"></line>
            <line x1="6" y1="6" x2="18" y2="18"></line>
        </svg>
    </button>

    <div>
        <div class="flex items-center gap-3 px-5 py-6 border-b border-white/10">
            <div class="w-9 h-9 rounded-full bg-gray-200 shrink-0 flex items-center justify-center text-sm font-bold text-gray-600 uppercase">
                {{ substr(Auth::user()->first_name, 0, 1) }}{{ substr(Auth::user()->last_name, 0, 1) }}
            </div>
            <p class="sidebar-label text-sm text-white leading-tight truncate">
                <span class="font-semibold">{{ Auth::user()->first_name }}'s</span>
                <span class="text-cyan-300 font-semibold">{{ str_replace('_', ' ', Auth::user()->role) }}</span>
            </p>
        </div>

        <nav class="px-3 py-5 space-y-1">
            @php $route = request()->route()->getName(); @endphp
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors {{ $route === 'admin.dashboard' ? 'bg-white/10 text-white' : 'text-blue-200 hover:bg-white/10 hover:text-white' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4.5 h-4.5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="3" y="3" width="7" height="7" rx="1"></rect>
                    <rect x="14" y="3" width="7" height="7" rx="1"></rect>
                    <rect x="14" y="14" width="7" height="7" rx="1"></rect>
                    <rect x="3" y="14" width="7" height="7" rx="1"></rect>
                </svg>
                <span class="sidebar-label">Dashboard</span>
            </a>
            <a href="{{ route('admin.products') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors {{ $route === 'admin.products' ? 'bg-white/10 text-white' : 'text-blue-200 hover:bg-white/10 hover:text-white' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4.5 h-4.5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M20 7L12 3 4 7v10l8 4 8-4V7z"></path>
                    <path d="M4 7l8 4 8-4"></path>
                    <line x1="12" y1="11" x2="12" y2="21"></line>
                </svg>
                <span class="sidebar-label">Product</span>
            </a>
            <a href="{{ route('admin.inventory') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors {{ $route === 'admin.inventory' ? 'bg-white/10 text-white' : 'text-blue-200 hover:bg-white/10 hover:text-white' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4.5 h-4.5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M9 5H7a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-2"></path>
                    <rect x="9" y="3" width="6" height="4" rx="1"></rect>
                </svg>
                <span class="sidebar-label">Inventory</span>
            </a>
            <a href="{{ route('admin.coupons.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors {{ str_starts_with($route, 'admin.coupon') ? 'bg-white/10 text-white' : 'text-blue-200 hover:bg-white/10 hover:text-white' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4.5 h-4.5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M20 12V6a2 2 0 0 0-2-2H6a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h4"></path>
                    <path d="M12 12h.01"></path>
                    <path d="M16 12h.01"></path>
                    <path d="M8 16h.01"></path>
                    <path d="M16 20l4-4"></path>
                    <path d="M20 16l-4 4"></path>
                </svg>
                <span class="sidebar-label">Vouchers</span>
            </a>
            @if(auth()->user()?->role === 'super_admin')
            <a href="{{ route('admin.users') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors {{ $route === 'admin.users' ? 'bg-white/10 text-white' : 'text-blue-200 hover:bg-white/10 hover:text-white' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4.5 h-4.5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                    <circle cx="9" cy="7" r="4"></circle>
                    <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                    <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                </svg>
                <span class="sidebar-label">Users</span>
            </a>
            <a href="{{ route('admin.external.simulator') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors {{ $route === 'admin.external.simulator' ? 'bg-white/10 text-white' : 'text-blue-200 hover:bg-white/10 hover:text-white' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4.5 h-4.5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="2" y="3" width="20" height="14" rx="2" ry="2"></rect>
                    <line x1="8" y1="21" x2="16" y2="21"></line>
                    <line x1="12" y1="17" x2="12" y2="21"></line>
                </svg>
                <span class="sidebar-label">Integration</span>
            </a>
            @endif
        </nav>
    </div>

    <div class="px-3 py-5 border-t border-white/10">
        <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-blue-200 hover:bg-white/10 hover:text-white text-sm font-medium transition-colors">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4.5 h-4.5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                <polyline points="16 17 21 12 16 7"></polyline>
                <line x1="21" y1="12" x2="9" y2="12"></line>
            </svg>
            <span class="sidebar-label">Sign Out</span>
        </a>
    </div>
</aside>

<form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">@csrf</form>

<script>
    function toggleSidebar() {
        const sidebar = document.getElementById('adminSidebar');
        const labels = document.querySelectorAll('.sidebar-label');
        const chevron = document.getElementById('sidebarChevron');
        const isCollapsed = sidebar.style.width === '4rem' || sidebar.classList.contains('collapsed');

        if (isCollapsed) {
            sidebar.style.width = '';
            sidebar.classList.remove('collapsed');
            labels.forEach(el => el.style.display = '');
            if (chevron) chevron.innerHTML = '<polyline points="15 18 9 12 15 6"></polyline>';
        } else {
            sidebar.style.width = '4rem';
            sidebar.classList.add('collapsed');
            labels.forEach(el => el.style.display = 'none');
            if (chevron) chevron.innerHTML = '<polyline points="9 18 15 12 9 6"></polyline>';
        }

        localStorage.setItem('sidebarCollapsed', !isCollapsed);
    }

    function toggleMobileSidebar() {
        const sidebar = document.getElementById('adminSidebar');
        const backdrop = document.getElementById('sidebarBackdrop');
        const isOpen = !sidebar.classList.contains('-translate-x-full');

        if (isOpen) {
            sidebar.classList.add('-translate-x-full');
            backdrop.classList.add('hidden');
            document.body.style.overflow = '';
        } else {
            sidebar.classList.remove('-translate-x-full');
            backdrop.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }
    }

    (function initSidebar() {
        const sidebar = document.getElementById('adminSidebar');
        const labels = document.querySelectorAll('.sidebar-label');
        const chevron = document.getElementById('sidebarChevron');
        const collapsed = localStorage.getItem('sidebarCollapsed') === 'true';

        if (collapsed && window.innerWidth >= 1024) {
            sidebar.classList.add('collapsed');
            sidebar.style.width = '4rem';
            labels.forEach(el => el.style.display = 'none');
            if (chevron) chevron.innerHTML = '<polyline points="9 18 15 12 9 6"></polyline>';
        }
    })();
</script>
