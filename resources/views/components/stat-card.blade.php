@props([
    'title' => '',
    'value' => '',
    'icon' => 'https://cdn.jsdelivr.net/npm/lucide-static@0.460.0/icons/package.svg',
    'color' => 'bg-sky-100',
    'href' => null,
])

@php
    $tag = $href ? 'a' : 'div';
@endphp

<{{ $tag }}
    @if($href) href="{{ $href }}" @endif
    class="border-2 border-sky-400 rounded-xl px-4 py-3 flex items-center justify-between bg-white hover-lift transition {{ $href ? 'hover:shadow-md hover:border-sky-500 cursor-pointer' : '' }}">

    <div class="flex items-center gap-3">

        <div class="w-12 h-12 rounded-full {{ $color }} flex items-center justify-center overflow-hidden">

            @if(str_starts_with($icon, '<svg'))
                {!! $icon !!}
            @else
                <img src="{{ $icon }}" alt="" class="w-6 h-6">
            @endif

        </div>

        <div>

            <p class="text-xs font-semibold">
                {{ $title }}
            </p>

            <h3 class="text-3xl font-bold">
                {{ $value }}
            </h3>

        </div>

    </div>

    @if($href)
        <span class="text-sky-500 text-xl leading-none">&rarr;</span>
    @endif

</{{ $tag }}>
