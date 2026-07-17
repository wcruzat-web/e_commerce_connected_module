<div class="flex justify-between items-center mb-3">
                <h3 class="font-bold text-xl" data-i18n="dash.recentNotif">
                    Recent Notifications
                </h3>
                <a href="{{ route('notifications') }}"
                   class="text-sky-500 text-sm font-semibold hover:underline" data-i18n="dash.viewAll">
                    View all
                </a>
            </div>

            <div class="space-y-3">
                @forelse($notifications as $notification)
                    <div class="border rounded-lg p-4 flex items-start gap-3 {{ $notification->is_read ? 'bg-white' : 'bg-sky-50' }}">
                        <div class="w-9 h-9 rounded-full bg-sky-100 flex items-center justify-center text-sm font-bold text-sky-600 shrink-0">
                            {{ strtoupper(substr($notification->notification_type, 0, 1)) }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="font-semibold text-sm">
                                {{ $notification->title }}
                            </p>
                            <p class="text-xs text-gray-500 mt-1">
                                {{ $notification->message }}
                            </p>
                        </div>
                        @unless($notification->is_read)
                            <span class="w-2 h-2 rounded-full bg-sky-500 mt-2 shrink-0" title="Unread"></span>
                        @endunless
                    </div>
                @empty
                    <div class="border-2 border-dashed border-sky-300 rounded-lg p-5 text-center text-gray-400">
                        No notifications yet.
                    </div>
                @endforelse
            </div>
