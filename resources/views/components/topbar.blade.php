<header class="bg-white dark:bg-gray-800 shadow px-4 sm:px-8 py-5 flex items-center justify-between gap-4">

    <div class="flex items-center gap-3 min-w-0">
        <!-- Mobile hamburger -->
        <button onclick="toggleSidebar()" class="lg:hidden p-2 rounded-md hover:bg-gray-100 dark:hover:bg-gray-700 shrink-0" aria-label="Toggle sidebar">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/>
            </svg>
        </button>

        <h1 class="text-2xl sm:text-3xl font-bold truncate">
            @yield('page_title', 'DASHBOARD')
        </h1>
    </div>

    @auth
    <div class="flex items-center gap-4 shrink-0">
        <a href="{{ route('notifications') }}" class="relative p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition" aria-label="Notifications">
            @php $__notifCount = auth()->user()->notifications()->where('is_read', false)->count(); @endphp
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-gray-600 dark:text-gray-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/>
                <path d="M13.73 21a2 2 0 0 1-3.46 0"/>
            </svg>
            @if($__notifCount > 0)
                <span class="absolute -top-0.5 -right-0.5 bg-red-500 text-white text-[10px] font-bold px-1.5 py-0.5 rounded-full min-w-[18px] text-center leading-tight" id="notif-badge-topbar">{{ $__notifCount }}</span>
            @endif
        </a>
        <div class="text-right hidden sm:block">
            <p class="text-sm font-semibold leading-tight">
                {{ auth()->user()->first_name }} {{ auth()->user()->last_name }}
            </p>
            <p class="text-xs text-gray-500 dark:text-gray-400 leading-tight truncate max-w-[180px]">
                {{ auth()->user()->email }}
            </p>
        </div>
        @if(auth()->user()->profile_picture)
            <img src="{{ Str::startsWith(auth()->user()->profile_picture, ['http://', 'https://']) ? auth()->user()->profile_picture : asset(auth()->user()->profile_picture) }}" alt=""
                class="w-10 h-10 rounded-full object-cover border shrink-0">
        @else
            <div class="w-10 h-10 rounded-full bg-sky-200 flex items-center justify-center shrink-0 font-bold text-sky-600 text-sm">
                {{ strtoupper(substr(auth()->user()->first_name, 0, 1)) }}{{ strtoupper(substr(auth()->user()->last_name, 0, 1)) }}
            </div>
        @endif
    </div>
    @endauth

<script>
(function() {
    function updateNotifBadge() {
        fetch('/notifications/unread-count', { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
            .then(function(r) { return r.json(); })
            .then(function(data) {
                var badge = document.getElementById('notif-badge');
                var topbarBadge = document.getElementById('notif-badge-topbar');
                var count = data.count || 0;
                [badge, topbarBadge].forEach(function(el) {
                    if (!el) return;
                    if (count > 0) {
                        el.textContent = count;
                        el.style.display = '';
                    } else {
                        el.style.display = 'none';
                    }
                });
            }).catch(function() {});
    }
    setTimeout(function() { updateNotifBadge(); }, 5000);
    setInterval(updateNotifBadge, 30000);
})();
</script>
</header>
