<div class="flex justify-between items-center">

<div>

<h1 class="text-3xl font-bold text-gray-900 dark:text-white">
Notifications
</h1>

<p class="text-gray-500 dark:text-gray-400 mt-2">
Stay updated with your orders, promotions, and important updates.
</p>

</div>

@if($unreadCount > 0)
<form method="POST" action="{{ route('notifications.read-all') }}" class="js-mark-all">
@csrf
<button type="submit" class="inline-flex items-center gap-1 text-sky-500 hover:text-sky-600 text-sm font-semibold hover:underline transition-colors">
    <img src="https://cdn.jsdelivr.net/npm/lucide-static@0.460.0/icons/check-circle.svg" class="w-4 h-4" alt=""> Mark all as read
</button>
</form>
@else
<span class="inline-flex items-center gap-1 text-sky-500/50 text-sm font-semibold">
    <img src="https://cdn.jsdelivr.net/npm/lucide-static@0.460.0/icons/check-circle.svg" class="w-4 h-4" alt=""> Mark all as read
</span>
@endif

</div>
