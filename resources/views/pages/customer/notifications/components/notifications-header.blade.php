<div class="flex justify-between items-center">

<div>

<h1 class="text-3xl font-bold">
Notification
</h1>

<p class="text-gray-500 mt-2">
Stay updated with your rentals, promotions, and important updates.
</p>

</div>

@if($unreadCount > 0)
<form method="POST" action="{{ route('notifications.read-all') }}" class="js-mark-all">
@csrf
<button type="submit" class="inline-flex items-center gap-1 text-sky-500 text-sm font-semibold hover:underline">
    <img src="https://cdn.jsdelivr.net/npm/lucide-static@0.460.0/icons/check-circle.svg" class="w-4 h-4" alt=""> Mark all as read
</button>
</form>
@else
<span class="inline-flex items-center gap-1 text-sky-500 text-sm font-semibold">
    <img src="https://cdn.jsdelivr.net/npm/lucide-static@0.460.0/icons/check-circle.svg" class="w-4 h-4" alt=""> Mark all as read
</span>
@endif

</div>
