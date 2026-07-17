@extends('layouts.customer')

@section('content')

<div>

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




<div class="bg-white rounded-xl shadow mt-8 p-5">

@php
$badges = [
'order' => ['bg' => 'bg-blue-100', 'text' => 'text-blue-600', 'label' => 'O'],
'promotion' => ['bg' => 'bg-green-100', 'text' => 'text-green-600', 'label' => 'P'],
'payment' => ['bg' => 'bg-purple-100', 'text' => 'text-purple-600', 'label' => '₱'],
'system' => ['bg' => 'bg-gray-100', 'text' => 'text-gray-600', 'label' => 'S'],
];
@endphp

<div class="flex flex-wrap gap-3 mb-6">

<button data-filter="all" class="js-filter px-5 py-2 rounded-full text-sm border bg-blue-900 text-white border-blue-900">
All
</button>
<button data-filter="order" class="js-filter px-5 py-2 rounded-full text-sm border text-gray-600 border-gray-300 hover:bg-gray-50">
Orders
</button>
<button data-filter="promotion" class="js-filter px-5 py-2 rounded-full text-sm border text-gray-600 border-gray-300 hover:bg-gray-50">
Promotions
</button>
<button data-filter="system" class="js-filter px-5 py-2 rounded-full text-sm border text-gray-600 border-gray-300 hover:bg-gray-50">
System
</button>

</div>




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


</div>


</div>

@endsection
