@extends('layouts.customer')

@section('content')

<div class="px-6 py-5">

    <!-- Greeting -->
    <div class="mb-6">

        <div class="inline-block bg-yellow-200 rounded-full px-5 py-1">
            <h2 class="text-4xl font-bold">
                Hello, <span>{{ $customer->first_name }}</span>
            </h2>
        </div>

        <p class="mt-2 text-gray-700">
            Welcome back to your account dashboard.
        </p>

    </div>

    <!-- Statistics (all real, from your own data) -->
    <h3 class="font-bold text-xl mb-4" data-i18n="dash.quick">
        Quick Statistics
    </h3>

    <div class="grid grid-cols-4 gap-4 stagger">

        <x-stat-card
            title="Wishlist"
            :value="$stats['wishlist']"
            icon="https://cdn.jsdelivr.net/npm/lucide-static@0.460.0/icons/heart.svg"
            color="bg-red-100"
            href="{{ route('wishlist') }}" />

        <x-stat-card
            title="Cart"
            :value="$stats['cart']"
            icon="https://cdn.jsdelivr.net/npm/lucide-static@0.460.0/icons/shopping-cart.svg"
            color="bg-sky-100"
            href="{{ route('cart') }}" />

        <x-stat-card
            title="Pending Orders"
            :value="$stats['pending']"
            icon="https://cdn.jsdelivr.net/npm/lucide-static@0.460.0/icons/clock.svg"
            color="bg-yellow-100"
            href="{{ route('orders') }}" />

        <x-stat-card
            title="Completed Orders"
            :value="$stats['completed']"
            icon="https://cdn.jsdelivr.net/npm/lucide-static@0.460.0/icons/check-circle.svg"
            color="bg-green-100"
            href="{{ route('history') }}" />

    </div>

    <!-- Lower Section (real data from your module) -->

    <div class="grid grid-cols-2 gap-6 mt-8">

        <!-- Recent Notifications -->
        <div>
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
        </div>

        <!-- Saved Payment Methods -->
        <div>
            <div class="flex justify-between items-center mb-3">
                <h3 class="font-bold text-xl" data-i18n="dash.savedPay">
                    Saved Payment Methods
                </h3>
                <a href="{{ route('payment-methods') }}"
                   class="text-sky-500 text-sm font-semibold hover:underline" data-i18n="dash.manage">
                    Manage
                </a>
            </div>

            <div class="space-y-3">
                @forelse($paymentMethods as $method)
                    <div class="border rounded-lg p-4 flex items-center justify-between">
                        <div class="min-w-0">
                            <p class="font-bold text-sm">
                                {{ $method->provider ?? $method->payment_type }}
                            </p>
                            <p class="text-xs text-gray-500 mt-1">
                                {{ $method->masked_account_number }}
                            </p>
                            <p class="text-xs text-gray-400">
                                {{ $method->account_name }}
                            </p>
                        </div>
                        @if($method->is_default)
                            <span class="bg-sky-100 text-sky-600 text-xs font-semibold px-3 py-1 rounded-full shrink-0">
                                Default
                            </span>
                        @endif
                    </div>
                @empty
                    <div class="border-2 border-dashed border-sky-300 rounded-lg p-5 text-center text-gray-400">
                        No saved payment methods yet.
                    </div>
                @endforelse

                <a href="{{ route('add-payment') }}"
                   class="block w-full border-2 border-dashed border-sky-300 text-sky-500 rounded-lg p-4 mt-3 text-center text-sm font-semibold hover:bg-sky-50" data-i18n="dash.addPay">
                    + Add Payment Method
                </a>
            </div>
        </div>

    </div>

</div>

@endsection
