{{-- CRUZAT — notification-icon: bell icon with unread count badge --}}{{--
    ERP MODULE: Admin Components
    COMPONENT: Notification Icon
    DESCRIPTION: Renders an SVG icon by name with the given Tailwind class.
    USAGE: @include('...notification-icon', ['icon' => 'alert-triangle', 'class' => 'text-red-500'])
--}}

@php
    $icons = [
        'alert-triangle' => '<path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path><line x1="12" y1="9" x2="12" y2="13"></line><line x1="12" y1="17" x2="12.01" y2="17"></line>',
        'shopping-cart' => '<circle cx="9" cy="21" r="1"></circle><circle cx="20" cy="21" r="1"></circle><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>',
        'refresh-cw' => '<polyline points="23 4 23 10 17 10"></polyline><polyline points="1 20 1 14 7 14"></polyline><path d="M3.51 9a9 9 0 0 1 14.85-3.36L23 10M1 14l4.64 4.36A9 9 0 0 0 20.49 15"></path>',
        'trending-up' => '<polyline points="23 6 13.5 15.5 8.5 10.5 1 18"></polyline><polyline points="17 6 23 6 23 12"></polyline>',
        'check-circle' => '<circle cx="12" cy="12" r="10"></circle><polyline points="9 12 11 14 15 10"></polyline>',
        'package' => '<path d="M20 7L12 3 4 7v10l8 4 8-4V7z"></path><path d="M4 7l8 4 8-4"></path><line x1="12" y1="11" x2="12" y2="21"></line>',
        'archive' => '<polyline points="21 8 21 21 3 21 3 8"></polyline><rect x="1" y="3" width="22" height="5"></rect><line x1="10" y1="12" x2="14" y2="12"></line>',
        'file-text' => '<path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline>',
    ];
@endphp

<svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mt-0.5 shrink-0 {{ $class ?? 'text-gray-500' }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
    {!! $icons[$icon] ?? $icons['alert-triangle'] !!}
</svg>
