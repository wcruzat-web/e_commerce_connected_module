{{--
    ERP MODULE: Admin Components
    COMPONENT: Notifications Panel
    DESCRIPTION: Slide-over panel showing all notifications (new + earlier).
                 Triggered by "View all notifications" in the topbar dropdown.
    TODO (Backend): Replace static $allNotifications with $notifications
                    from the backend. Same structure as the topbar stub.
--}}

@php
    $allNotifications = [
        ['icon' => 'alert-triangle', 'icon_color' => 'text-red-500', 'title' => 'Low stock: RTX 4090 (2 units left)', 'time' => '2m ago', 'unread' => true],
        ['icon' => 'shopping-cart', 'icon_color' => 'text-amber-500', 'title' => 'New order: Sarah Chen — ₱42,639 (Processing)', 'time' => '5m ago', 'unread' => true],
        ['icon' => 'refresh-cw', 'icon_color' => 'text-green-500', 'title' => 'ERP sync completed successfully', 'time' => '15m ago', 'unread' => true],
        ['icon' => 'trending-up', 'icon_color' => 'text-green-500', 'title' => 'Revenue milestone: Monthly revenue exceeded ₱247k', 'time' => '1h ago', 'unread' => false],
        ['icon' => 'alert-triangle', 'icon_color' => 'text-amber-500', 'title' => 'Low stock alert: Intel Core i9-14900K (7 units left)', 'time' => '2h ago', 'unread' => false],
        ['icon' => 'check-circle', 'icon_color' => 'text-green-500', 'title' => 'Order delivered: Elena Rodriguez — ✓ Delivered', 'time' => '3h ago', 'unread' => false],
        ['icon' => 'refresh-cw', 'icon_color' => 'text-cyan-500', 'title' => 'Sync completed: All channels synced successfully', 'time' => '5h ago', 'unread' => false],
        ['icon' => 'package', 'icon_color' => 'text-blue-500', 'title' => 'Order shipped: Alex Morgan — Shipped', 'time' => '1d ago', 'unread' => false],
        ['icon' => 'archive', 'icon_color' => 'text-purple-500', 'title' => 'Stock replenished: RTX 4080 Super back in stock', 'time' => '2d ago', 'unread' => false],
        ['icon' => 'file-text', 'icon_color' => 'text-gray-500', 'title' => 'Monthly report ready for review', 'time' => '3d ago', 'unread' => false],
    ];
    $newCount = count(array_filter($allNotifications, fn($n) => $n['unread']));
@endphp

<div id="notificationsPanel" class="hidden fixed inset-0 z-50">
    <div class="absolute inset-0 bg-black/30" onclick="closeNotificationsPanel()"></div>
    <div class="absolute right-0 top-0 h-full w-full max-w-md bg-white shadow-2xl overflow-y-auto max-lg:max-w-full">
        <div class="p-6">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h2 class="text-lg font-bold text-gray-900">Notifications</h2>
                    <p class="text-xs text-gray-400">{{ $newCount }} new</p>
                </div>
                <button type="button" onclick="closeNotificationsPanel()" class="w-8 h-8 rounded-lg border border-gray-200 flex items-center justify-center text-gray-500 hover:bg-gray-50 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="18" y1="6" x2="6" y2="18"></line>
                        <line x1="6" y1="6" x2="18" y2="18"></line>
                    </svg>
                </button>
            </div>

            {{-- New notifications --}}
            @php $new = array_filter($allNotifications, fn($n) => $n['unread']); @endphp
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

            {{-- Earlier notifications --}}
            @php $earlier = array_filter($allNotifications, fn($n) => !$n['unread']); @endphp
            @if(count($earlier))
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Earlier</p>
                <div class="divide-y divide-gray-100">
                    @foreach ($earlier as $n)
                        <div class="flex items-start gap-3 py-3 opacity-70">
                            @include('pages.admin.dashboard.components.notification-icon', ['icon' => $n['icon'], 'class' => $n['icon_color']])
                            <div class="flex-1 min-w-0">
                                <p class="text-sm text-gray-700">{{ $n['title'] }}</p>
                                <p class="text-xs text-gray-400 mt-`0.5">{{ $n['time'] }}</p>
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
