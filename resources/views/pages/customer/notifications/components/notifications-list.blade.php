<div class="space-y-4" id="notification-list">

@forelse($notifications as $notification)

@php
$key = $notification->icon ?? $notification->defaultIconKey;
$badge = $badges[$key] ?? $badges['system'];
@endphp

<div class="js-notif flex items-start justify-between border rounded-lg p-5 {{ $notification->is_read ? '' : 'bg-sky-50/40' }}" data-type="{{ $key }}">

<div class="flex items-start gap-4">

<span class="w-10 h-10 rounded-full flex items-center justify-center font-bold {{ $badge['bg'] }} {{ $badge['text'] }}">
{{ $badge['label'] }}
</span>

<div>

<p class="font-bold">
{{ $notification->title }}
</p>

<p class="text-sm text-gray-500 mt-1">
{{ $notification->message }}
</p>

</div>

</div>

<div class="flex items-center gap-3 shrink-0">

<span class="text-xs text-gray-400">
{{ $notification->created_at->diffForHumans() }}
</span>

@if(!$notification->is_read)
<span class="w-2.5 h-2.5 rounded-full bg-sky-500"></span>
@endif

</div>

</div>
@empty

<div class="border-2 border-dashed border-sky-300 rounded-lg p-10 text-center text-gray-400">
No notifications yet.
</div>

@endforelse

</div>
