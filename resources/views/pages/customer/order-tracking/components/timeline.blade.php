<div class="bg-white border border-gray-200 rounded-2xl p-5 shadow-sm">
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-sm font-semibold text-gray-900">Shipment Timeline</h2>
        <button type="button" onclick="toggleTimelineDetails()" class="flex items-center gap-1 text-xs font-medium text-cyan-500 hover:text-cyan-600">
            <span id="timelineToggleLabel">Show more details</span>
            <svg id="timelineToggleIcon" xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 transition-transform" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <polyline points="6 9 12 15 18 9"></polyline>
            </svg>
        </button>
    </div>

    <div id="timelineCollapsed">
        <div class="flex items-start justify-between">
            @foreach ($timelineSteps as $index => $step)
                <div class="flex flex-col items-center flex-1 relative">
                    <div class="w-8 h-8 rounded-full flex items-center justify-center z-10
                        @if($step['state'] === 'done') bg-green-500 text-white
                        @elseif($step['state'] === 'current') bg-cyan-500 text-white
                        @else bg-gray-300 text-white
                        @endif">
                        @if($step['state'] === 'done')
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="20 6 9 17 4 12"></polyline>
                            </svg>
                        @elseif($step['state'] === 'current')
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"></path>
                            </svg>
                        @else
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="12" cy="12" r="10"></circle>
                                <polyline points="12 6 12 12 16 14"></polyline>
                            </svg>
                        @endif
                    </div>
                    <p class="text-xs mt-2 text-center leading-tight max-w-[80px]
                        @if($step['state'] === 'pending') text-gray-400 @else text-gray-700 font-medium @endif">
                        {{ $step['title'] }}
                    </p>

                    @if(!$loop->last)
                        <div class="absolute top-4 left-1/2 w-full h-0.5 -z-0
                            @if($step['state'] === 'done') bg-green-500 @else bg-gray-200 @endif">
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
    </div>

    <div id="timelineExpanded" class="hidden">
        <div class="space-y-0">
            @foreach ($timelineSteps as $step)
                <div class="flex gap-4 {{ !$loop->last ? 'pb-6' : '' }}">
                    <div class="flex flex-col items-center">
                        <div class="w-8 h-8 rounded-full flex items-center justify-center shrink-0
                            @if($step['state'] === 'done') bg-green-500 text-white
                            @elseif($step['state'] === 'current') bg-cyan-500 text-white
                            @else bg-gray-300 text-white
                            @endif">
                            @if($step['state'] === 'done')
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                    <polyline points="20 6 9 17 4 12"></polyline>
                                </svg>
                            @elseif($step['state'] === 'current')
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"></path>
                                </svg>
                            @else
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <circle cx="12" cy="12" r="10"></circle>
                                    <polyline points="12 6 12 12 16 14"></polyline>
                                </svg>
                            @endif
                        </div>
                        @if(!$loop->last)
                            <div class="w-0.5 flex-1 mt-1 {{ $step['state'] === 'done' ? 'bg-green-500' : 'bg-gray-200' }}"></div>
                        @endif
                    </div>

                    <div class="flex-1 {{ !$loop->last ? 'pb-1' : '' }}">
                        <div class="flex items-start justify-between gap-2">
                            <p class="text-sm font-semibold
                                @if($step['state'] === 'pending') text-gray-400
                                @elseif($step['state'] === 'current') text-cyan-500
                                @else text-gray-900
                                @endif">
                                {{ $step['title'] }}
                            </p>
                            <span class="text-sm font-semibold text-gray-900 whitespace-nowrap">{{ $step['date'] }}</span>
                        </div>
                        <p class="text-xs text-gray-500 mt-0.5">{{ $step['description'] }}</p>
                        <div class="flex items-start justify-between gap-2 mt-0.5">
                            <p class="text-xs text-gray-400">{{ $step['meta'] }}</p>
                            <span class="text-xs text-gray-400 whitespace-nowrap">{{ $step['time'] }}</span>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
