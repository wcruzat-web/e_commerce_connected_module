<div id="tab-compatibility" class="tab-content hidden">
    @if(!empty($product['compatGroups']))
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 pt-4 pb-6">
            @foreach ($product['compatGroups'] as $group)
                <div class="bg-white rounded-xl p-5 border border-gray-300 shadow-[0_4px_12px_rgba(0,0,0,0.04)]">
                    <h4 class="text-xs font-bold text-blue-800 tracking-wide mb-4">{{ $group['category'] }}</h4>
                    <ul class="space-y-3 text-[11px] font-semibold text-slate-700">
                        @foreach ($group['items'] as $item)
                            <li class="flex items-center space-x-2">
                                <svg class="w-4 h-4 text-emerald-500 shrink-0" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"></path></svg>
                                <span class="text-gray-400 font-normal">-</span>
                                <span>{{ $item }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endforeach
        </div>
    @else
        <p class="text-xs text-gray-400 text-center py-8">No compatibility information available.</p>
    @endif
</div>
