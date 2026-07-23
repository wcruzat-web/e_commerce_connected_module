{{-- HAINZ — reviews-section: customer reviews, rating distribution, review form (ERPV1.1) --}}
<div id="tab-reviews" class="tab-content hidden">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8 items-center pt-4 pb-6">
        <div class="flex flex-col items-center justify-center text-center md:border-r border-gray-100 py-4">
            <h3 class="text-5xl font-black text-slate-800 tracking-tight">{{ $product['rating'] }}</h3>
            <p class="text-[10px] font-bold text-gray-400 mt-1 tracking-wide">{{ $product['reviewCount'] }} verified reviews</p>
        </div>
        <div class="md:col-span-2 space-y-2.5 px-2 md:px-6">
            @foreach ($product['reviewDistribution'] as $starIdx => $pct)
                <div class="flex items-center space-x-3 text-[11px] font-bold text-gray-400">
                    <span class="w-3 select-none">{{ 5 - $starIdx }}</span>
                    <div class="flex-1 h-2 bg-gray-100 rounded-full overflow-hidden">
                        <div class="h-full bg-cyan-400 rounded-full" style="width: {{ $pct }}%"></div>
                    </div>
                    <span class="w-7 text-right">{{ $pct }}%</span>
                </div>
            @endforeach
        </div>
    </div>

    <div class="border-t border-gray-100 pt-8 mt-8">
        <h4 class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-5">Comments</h4>

        @forelse ($product['userReviews'] as $review)
            <div class="border-b border-gray-100 pb-5 mb-5">
                <div class="flex items-center space-x-3 mb-2.5">
                    @if ($review['profile_picture'])
                        <img src="{{ $review['profile_picture'] }}" class="w-8 h-8 rounded-full object-cover">
                    @else
                        <div class="w-8 h-8 bg-blue-600 rounded-full flex items-center justify-center text-white font-bold text-xs">{{ $review['initials'] }}</div>
                    @endif
                    <div>
                        <p class="text-xs font-bold text-slate-800">{{ $review['name'] }}</p>
                        <div class="flex items-center gap-0.5 mt-0.5">
                            @for ($s = 1; $s <= 5; $s++)
                                <svg class="w-3 h-3 {{ $s <= $review['rating'] ? 'text-yellow-400' : 'text-gray-300' }}" viewBox="0 0 24 24" fill="currentColor"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                            @endfor
                        </div>
                        <p class="text-[9px] text-gray-400">{{ $review['createdAt'] }}</p>
                    </div>
                </div>
                <p class="text-[10px] text-gray-500 leading-relaxed">{{ $review['comment'] }}</p>
            </div>
        @empty
            <p class="text-xs text-gray-400 text-center py-4">No reviews yet. Be the first to review!</p>
        @endforelse


    </div>
</div>
