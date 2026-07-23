{{-- CRUZAT — notifications-panel: slide-over panel with all notifications --}}

<div id="notificationsPanel" class="hidden fixed inset-0 z-50">
    <div class="absolute inset-0 bg-black/30" onclick="closeNotificationsPanel()"></div>
    <div class="absolute right-0 top-0 h-full w-full max-w-md bg-white shadow-2xl overflow-y-auto max-lg:max-w-full">
        <div class="p-6">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h2 class="text-lg font-bold text-gray-900">Notifications</h2>
                    @php $unreadCount = count(array_filter($notifications ?? [], fn($n) => $n['unread'])); @endphp
                    <p class="text-xs text-gray-400">{{ $unreadCount }} new</p>
                </div>
                <button type="button" onclick="closeNotificationsPanel()" class="w-8 h-8 rounded-lg border border-gray-200 flex items-center justify-center text-gray-500 hover:bg-gray-50 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="18" y1="6" x2="6" y2="18"></line>
                        <line x1="6" y1="6" x2="18" y2="18"></line>
                    </svg>
                </button>
            </div>

            @php
                $all = $notifications ?? [];
                $new = array_filter($all, fn($n) => $n['unread']);
                $earlier = array_filter($all, fn($n) => !$n['unread']);
            @endphp

            @if(count($new))
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">New</p>
                <div class="divide-y divide-gray-100 mb-6">
                    @foreach ($new as $n)
                        <div class="flex items-start gap-3 py-3">
                            @include('pages.admin.dashboard.components.notification-icon', ['icon' => $n['icon'], 'class' => $n['icon_color']])
                            <div class="flex-1 min-w-0">
                                <p class="text-sm text-gray-900">{{ $n['title'] }}</p>
                                <p class="text-xs text-gray-400 mt-0.5">{{ $n['time'] }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif

            @if(count($earlier))
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Earlier</p>
                <div class="divide-y divide-gray-100">
                    @foreach ($earlier as $n)
                        <div class="flex items-start gap-3 py-3 opacity-70">
                            @include('pages.admin.dashboard.components.notification-icon', ['icon' => $n['icon'], 'class' => $n['icon_color']])
                            <div class="flex-1 min-w-0">
                                <p class="text-sm text-gray-700">{{ $n['title'] }}</p>
                                <p class="text-xs text-gray-400 mt-0.5">{{ $n['time'] }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>

<script>
    function openNotificationsPanel() {
        document.getElementById('notificationsPanel').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closeNotificationsPanel() {
        document.getElementById('notificationsPanel').classList.add('hidden');
        document.body.style.overflow = '';
    }
</script>
