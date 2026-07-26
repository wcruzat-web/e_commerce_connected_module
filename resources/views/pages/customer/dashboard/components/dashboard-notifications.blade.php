<div class="flex justify-between items-center mb-3">
                <h3 class="font-bold text-xl dark:text-white" data-i18n="dash.recentNotif">
                    Recent Notifications
                </h3>
                <a href="{{ route('notifications') }}"
                   class="text-sky-500 dark:text-sky-400 text-sm font-semibold hover:underline" data-i18n="dash.viewAll">
                    View all
                </a>
            </div>

            <div class="space-y-3">
                @forelse($notifications as $notification)
                    <div class="border dark:border-gray-700 rounded-lg p-4 flex items-start gap-3 {{ $notification->is_read ? 'bg-white dark:bg-gray-800' : 'bg-sky-50 dark:bg-sky-900/20' }}">
                        <div class="w-9 h-9 rounded-full bg-sky-100 dark:bg-sky-800 flex items-center justify-center text-sm font-bold text-sky-600 dark:text-sky-300 shrink-0">
                            {{ strtoupper(substr($notification->notification_type, 0, 1)) }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="font-semibold text-sm dark:text-gray-100">
                                {{ $notification->title }}
                            </p>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                {{ $notification->message }}
                            </p>
                        </div>
                        @unless($notification->is_read)
                            <span class="w-2 h-2 rounded-full bg-sky-500 mt-2 shrink-0" title="Unread"></span>
                        @endunless
                    </div>
                @empty
                    <div class="border-2 border-dashed border-sky-300 dark:border-sky-700 rounded-lg p-5 text-center text-gray-400 dark:text-gray-500">
                        No notifications yet.
                    </div>
                @endforelse
            </div>
