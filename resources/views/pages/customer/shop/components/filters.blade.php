<aside class="space-y-6">
    <form method="GET" action="{{ route('products.index') }}">
    <div class="bg-white rounded-[20px] border border-gray-200/80 p-6 shadow-[0_4px_20px_rgba(0,0,0,0.03)]">
        <div class="flex justify-between items-center border-b border-gray-100 pb-4 mb-5">
            <h2 class="font-bold text-base tracking-tight text-slate-800">Filters</h2>
            <a href="{{ route('products.index') }}" class="text-xs font-semibold text-blue-600 hover:text-blue-800 transition-colors">Reset</a>
        </div>

        <div class="mb-6">
            <h3 class="text-sm font-bold text-slate-800 mb-3">Brand</h3>
            <div class="space-y-3">
                @php $brands = ['NVIDIA', 'AMD', 'Intel', 'ASUS', 'MSI', 'Gigabyte', 'Corsair', 'Samsung']; @endphp
                @foreach ($brands as $b)
                    <label class="flex items-center space-x-3 text-xs font-semibold text-slate-600 cursor-pointer hover:text-slate-900 select-none">
                        <input type="checkbox" name="brands[]" value="{{ $b }}" class="sr-only" onchange="this.form.submit()" {{ in_array($b, request('brands', [])) ? 'checked' : '' }}>
                        <div class="w-[18px] h-[18px] rounded-full border-2 flex items-center justify-center shrink-0 transition {{ in_array($b, request('brands', [])) ? 'bg-blue-600 border-blue-600' : 'border-gray-300' }}">
                            @if(in_array($b, request('brands', [])))
                                <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                            @endif
                        </div>
                        <span>{{ $b }}</span>
                    </label>
                @endforeach
            </div>
        </div>

        <div class="border-t border-gray-100 pt-5 mb-6">
            <h3 class="text-sm font-bold text-slate-800 mb-3">Chipset</h3>
            <div class="space-y-3">
                @php $chipsets = ['Z790', 'Z690', 'X670E', 'X670', 'B760', 'B650']; @endphp
                @foreach ($chipsets as $c)
                    <label class="flex items-center space-x-3 text-xs font-semibold text-slate-600 cursor-pointer hover:text-slate-900 select-none">
                        <input type="checkbox" name="chipsets[]" value="{{ $c }}" class="sr-only" onchange="this.form.submit()" {{ in_array($c, request('chipsets', [])) ? 'checked' : '' }}>
                        <div class="w-[18px] h-[18px] rounded-full border-2 flex items-center justify-center shrink-0 transition {{ in_array($c, request('chipsets', [])) ? 'bg-blue-600 border-blue-600' : 'border-gray-300' }}">
                            @if(in_array($c, request('chipsets', [])))
                                <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                            @endif
                        </div>
                        <span>{{ $c }}</span>
                    </label>
                @endforeach
            </div>
        </div>

        <div class="border-t border-gray-100 pt-5 mb-6">
            <h3 class="text-sm font-bold text-slate-800 mb-3">CPU Socket</h3>
            <div class="space-y-3">
                @php $sockets = ['LGA 1700', 'AM5', 'AM4', 'LGA4577']; @endphp
                @foreach ($sockets as $s)
                    <label class="flex items-center space-x-3 text-xs font-semibold text-slate-600 cursor-pointer hover:text-slate-900 select-none">
                        <input type="checkbox" name="sockets[]" value="{{ $s }}" class="sr-only" onchange="this.form.submit()" {{ in_array($s, request('sockets', [])) ? 'checked' : '' }}>
                        <div class="w-[18px] h-[18px] rounded-full border-2 flex items-center justify-center shrink-0 transition {{ in_array($s, request('sockets', [])) ? 'bg-blue-600 border-blue-600' : 'border-gray-300' }}">
                            @if(in_array($s, request('sockets', [])))
                                <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                            @endif
                        </div>
                        <span>{{ $s }}</span>
                    </label>
                @endforeach
            </div>
        </div>

        <div class="border-t border-gray-100 pt-5">
            <h3 class="text-sm font-bold text-slate-800 mb-3">VRAM Capacity</h3>
            <div class="flex flex-wrap gap-2">
                @php $vrams = ['4GB', '8GB', '12GB', '16GB', '24GB']; @endphp
                @foreach ($vrams as $v)
                    <button type="submit" name="vram" value="{{ $v }}"
                        class="text-xs font-bold px-3.5 py-1 rounded-full border tracking-wide transition-all duration-150 {{ request('vram') === $v ? 'bg-blue-600 text-white border-blue-600 shadow-sm' : 'bg-white border-gray-300 text-slate-600 hover:border-gray-400' }}">
                        {{ $v }}
                    </button>
                @endforeach
            </div>
        </div>
    </div>
    </form>
</aside>
