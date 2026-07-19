{{-- HAINZ — hero-banner: two-column layout with featured product on right (ERPV1.6) --}}
<div class="bg-[#1e3a8a] text-white pb-16 pt-6 px-6 md:px-12 relative overflow-hidden">
    <div class="max-w-7xl mx-auto">
        @if ($featured)
        <div class="flex flex-col lg:flex-row items-center min-h-[300px] gap-12 lg:gap-20">
            <div class="flex-1">
                <span class="bg-sky-500/20 text-sky-300 text-xs font-semibold px-3 py-1 rounded-full border border-sky-500/30">
                    Premium PC Hardware Store
                </span>
                <h1 class="text-4xl md:text-5xl font-extrabold tracking-tight mt-4 leading-tight">
                    Elite PC Hardware <br> For Serious Builders
                </h1>
                <p class="text-blue-100/80 mt-4 max-w-md text-sm leading-relaxed">
                    Current-gen processors, GPUs, and components. Enterprise pricing, boutique service.
                </p>
            </div>
            <div class="flex-1 lg:border-l lg:border-white/10 lg:pl-12">
                <span class="inline-flex items-center gap-1.5 bg-amber-400/20 text-amber-300 text-[10px] font-black uppercase px-3 py-1.5 rounded-full border border-amber-400/30 tracking-widest w-fit">
                    <svg class="w-3 h-3" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87L18.18 22 12 18.56 5.82 22 7 14.14l-5-4.87 6.91-1.01L12 2z"/></svg>
                    Featured Product
                </span>
                <h2 class="text-2xl md:text-3xl font-black mt-3 leading-tight text-white">
                    {{ $featured['name'] }}
                </h2>
                <div class="mt-4">
                    @if($featured['original_price'])
                        <span class="text-lg text-blue-200/60 line-through mr-2">₱{{ number_format($featured['original_price']) }}</span>
                    @endif
                    <span class="text-3xl font-black text-amber-400">₱{{ number_format($featured['price']) }}</span>
                </div>
                <p class="text-blue-200/60 mt-3 text-sm">{{ $featured['brand'] }} &bull; {{ $featured['sku'] }}</p>
                <div class="mt-6">
                    <a href="{{ route('products.show', $featured['id']) }}" class="inline-flex items-center gap-1.5 text-sm font-semibold text-white hover:text-amber-300 transition border-b border-white/40 hover:border-amber-300 pb-0.5">
                        View Details
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M5 12h14M13 5l7 7-7 7"/></svg>
                    </a>
                </div>
            </div>
        </div>
        @else
        <div>
            <span class="bg-sky-500/20 text-sky-300 text-xs font-semibold px-3 py-1 rounded-full border border-sky-500/30">
                Premium PC Hardware Store
            </span>
            <h1 class="text-4xl md:text-5xl font-extrabold tracking-tight mt-4 leading-tight">
                Elite PC Hardware <br> For Serious Builders
            </h1>
            <p class="text-blue-100/80 mt-4 max-w-md text-sm leading-relaxed">
                Current-gen processors, GPUs, and components. Enterprise pricing, boutique service.
            </p>
        </div>
        @endif
    </div>
</div>