{{-- CRUZAT — dashboard topbar: ERP sync status, notifications, user info --}}

<div class="bg-white border-b border-gray-200 px-4 lg:px-6 py-3 flex items-center justify-between relative">
    {{-- Mobile hamburger --}}
    <button type="button" class="lg:hidden mr-3 text-gray-500 hover:text-gray-700" onclick="toggleMobileSidebar()">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <line x1="3" y1="6" x2="21" y2="6"></line>
            <line x1="3" y1="12" x2="21" y2="12"></line>
            <line x1="3" y1="18" x2="21" y2="18"></line>
        </svg>
    </button>
    <div class="flex items-center gap-2 text-sm text-gray-600">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-cyan-500 animate-spin" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <polyline points="23 4 23 10 17 10"></polyline>
            <polyline points="1 20 1 14 7 14"></polyline>
            <path d="M3.51 9a9 9 0 0 1 14.85-3.36L23 10M1 14l4.64 4.36A9 9 0 0 0 20.49 15"></path>
        </svg>
        ERP Sync Active
        <span class="w-2 h-2 rounded-full bg-green-500"></span>
    </div>

    <div class="flex items-center gap-3 lg:gap-5">
        {{-- Notification bell --}}
        <div class="relative">
            <button type="button" onclick="toggleNotifications()" aria-label="Notifications" class="relative text-gray-500 hover:text-gray-700">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M18 8a6 6 0 0 0-12 0c0 7-3 9-3 9h18s-3-2-3-9"></path>
                    <path d="M13.73 21a2 2 0 0 1-3.46 0"></path>
                </svg>
                @php $unread = array_filter($notifications ?? [], fn($n) => $n['unread']); @endphp
                @if(count($unread) > 0)
                <span class="absolute -top-0.5 -right-0.5 w-2 h-2 rounded-full bg-red-500"></span>
                @endif
            </button>

            {{-- New notifications dropdown --}}
            <div id="notificationsDropdown" class="hidden absolute right-0 top-full mt-3 w-80 bg-white border border-gray-200 rounded-xl shadow-xl z-50">
                <div class="flex items-center justify-between px-4 py-3 border-b border-gray-100">
                    <p class="text-sm font-semibold text-gray-900">Notifications</p>
                    <span class="text-[11px] font-medium bg-blue-100 text-blue-700 px-2 py-0.5 rounded-full">{{ count($unread) }} new</span>
                </div>
                <div class="max-h-64 overflow-y-auto">
                    @foreach (array_slice($unread, 0, 5) as $n)
                        <div class="flex items-start gap-3 px-4 py-3 hover:bg-gray-50 transition-colors">
                            @include('pages.admin.dashboard.components.notification-icon', ['icon' => $n['icon'], 'class' => $n['icon_color']])
                            <div class="flex-1 min-w-0">
                                <p class="text-xs text-gray-700">{{ $n['title'] }}</p>
                                <p class="text-[11px] text-gray-400 mt-0.5">{{ $n['time'] }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="border-t border-gray-100 px-4 py-2.5">
                    <a href="#" onclick="event.preventDefault(); openNotificationsPanel();" class="text-xs font-medium text-cyan-500 hover:text-cyan-600">View all notifications</a>
                </div>
            </div>
        </div>

        {{-- User --}}
        <div class="flex items-center gap-2.5">
            <div class="w-8 h-8 rounded-full bg-gray-200 flex items-center justify-center text-xs font-semibold text-gray-600 uppercase">
                {{ substr(Auth::user()->first_name, 0, 1) }}{{ substr(Auth::user()->last_name, 0, 1) }}
            </div>
            <div class="leading-tight">
                <p class="text-xs font-semibold text-gray-900">{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}</p>
                <p class="text-[11px] text-gray-400">{{ str_replace('_', ' ', Auth::user()->role) }}</p>
            </div>
        </div>
    </div>
</div>

@include('pages.admin.dashboard.components.notifications-panel')

<script>
    function toggleNotifications() {
        document.getElementById('notificationsDropdown').classList.toggle('hidden');
    }

    document.addEventListener('click', function (event) {
        const dropdown = document.getElementById('notificationsDropdown');
        const isBellClick = event.target.closest('button[onclick="toggleNotifications()"]');
        if (!dropdown.contains(event.target) && !isBellClick) {
            dropdown.classList.add('hidden');
        }
    });
</script>
