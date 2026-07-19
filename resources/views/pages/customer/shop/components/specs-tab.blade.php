{{-- HAINZ — specs-tab: grouped specifications display for product detail (ERPV1.1) --}}
<div id="tab-specs" class="tab-content">
    @if(!empty($product['detailSpecs']))
        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-12 gap-y-8 pt-4 pb-6">
            @foreach ($product['detailSpecs'] as $section)
                <div class="space-y-3">
                    <h4 class="text-xs font-bold text-blue-600 uppercase tracking-wider pb-1">{{ $section['section'] }}</h4>
                    <div class="space-y-1 text-[11px]">
                        @foreach ($section['items'] as $i => $item)
                            <div class="flex justify-between items-center px-3 py-2 {{ $i % 2 === 0 ? 'bg-blue-50/50 rounded' : 'rounded' }}">
                                <span class="text-gray-400 font-medium">{{ $item['label'] }}</span>
                                <span class="font-semibold text-slate-800">{{ $item['value'] }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
