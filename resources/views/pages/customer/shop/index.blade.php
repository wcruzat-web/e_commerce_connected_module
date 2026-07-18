@extends('layouts.app')

@section('content')

<div class="bg-[#1e3a8a] text-white pb-16 pt-6 px-6 md:px-12 relative overflow-hidden">
    <div class="max-w-7xl mx-auto">
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
    </div>
</div>

<main class="max-w-7xl mx-auto px-6 py-10">
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">

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

        <div class="lg:col-span-3">
            <div class="flex justify-between items-center mb-6">
                <p class="text-sm font-semibold text-slate-600">
                    <span class="text-slate-900 font-extrabold text-base">{{ count($products) }}</span> Products
                </p>
                <div class="flex items-center space-x-2">
                    <span class="text-sm font-medium text-slate-800">Sort by:</span>
                    <select class="text-xs font-semibold bg-white border border-gray-300 rounded-lg px-3 py-2 text-slate-700 outline-none w-32 shadow-sm">
                        <option>Featured</option>
                        <option>Price: Low to High</option>
                        <option>Price: High to Low</option>
                    </select>
                </div>
            </div>

            <div class="grid sm:grid-cols-2 xl:grid-cols-3 gap-6">
                @foreach ($products as $product)
                    <div class="bg-white rounded-2xl border border-gray-200/80 p-5 shadow-sm flex flex-col justify-between transition hover:shadow-md relative {{ !$product['inStock'] ? 'opacity-70 bg-gray-50/50' : '' }}">
                        <div>
                            <div class="flex justify-between items-center mb-3">
                                @if($product['badge'])
                                    <span class="{{ $product['badgeClass'] }} text-[9px] font-black uppercase px-2.5 py-0.5 rounded tracking-wide text-white">{{ $product['badge'] }}</span>
                                @endif
                            </div>

                            <a href="{{ route('products.show', $product['id']) }}" class="h-36 w-full flex items-center justify-center mb-4 bg-transparent relative">
                                @unless($product['inStock'])
                                    <div class="absolute inset-0 bg-slate-900/5 backdrop-blur-[1px] flex flex-col items-center justify-center rounded-xl z-10">
                                        <span class="bg-white/95 px-4 py-2 rounded-lg font-black text-xs tracking-widest text-slate-800 shadow">OUT OF STOCK</span>
                                    </div>
                                @endunless
                                <img src="{{ $product['image'] }}" alt="{{ $product['name'] }}" class="h-full object-contain max-w-[85%]">
                            </a>

                            <p class="text-[9px] uppercase font-bold text-gray-400 tracking-wider">{{ $product['categoryMeta'] }}</p>
                            <h3 class="font-bold text-xs mt-0.5 tracking-tight text-slate-900 hover:text-blue-600 transition cursor-pointer">
                                <a href="{{ route('products.show', $product['id']) }}">{{ $product['name'] }}</a>
                            </h3>

                            @if(!empty($product['specs']))
                                <div class="flex flex-wrap gap-1 mt-2">
                                    @foreach ($product['specs'] as $spec)
                                        <span class="bg-gray-100 text-[8.5px] text-gray-500 px-1.5 py-0.5 rounded font-medium">{{ $spec }}</span>
                                    @endforeach
                                </div>
                            @endif
                        </div>

                        <div class="mt-4 pt-3 border-t border-gray-100 flex items-center justify-between">
                            <div>
                                <span class="text-sm font-black text-slate-900">₱{{ number_format($product['price']) }}</span>
                            </div>
                            <form method="POST" action="{{ route('cart.add') }}">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $product['id'] }}">
                                <input type="hidden" name="quantity" value="1">
                                <button type="submit"
                                    {{ $product['inStock'] ? '' : 'disabled' }}
                                    class="font-extrabold text-[10px] px-3 py-2 rounded-lg transition tracking-wide
                                    {{ $product['inStock'] ? 'bg-sky-400 hover:bg-sky-500 text-blue-950 shadow-sm' : 'bg-gray-200 text-gray-400 cursor-not-allowed' }}">
                                    Add to Cart
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</main>

<footer class="bg-white border-t border-gray-200 mt-20 text-xs text-gray-500">
    <div class="max-w-7xl mx-auto px-6 py-12 grid grid-cols-2 md:grid-cols-4 gap-8">
        <div>
            <h4 class="font-extrabold text-slate-900 mb-3 text-xs uppercase tracking-wider">Processors</h4>
            <ul class="space-y-2 font-medium">
                <li><a href="#" class="hover:text-blue-600">Intel Core i9</a></li>
                <li><a href="#" class="hover:text-blue-600">Intel Core i7</a></li>
                <li><a href="#" class="hover:text-blue-600">AMD Ryzen 9</a></li>
                <li><a href="#" class="hover:text-blue-600">AMD Ryzen 7</a></li>
            </ul>
        </div>
        <div>
            <h4 class="font-extrabold text-slate-900 mb-3 text-xs uppercase tracking-wider">Graphics</h4>
            <ul class="space-y-2 font-medium">
                <li><a href="#" class="hover:text-blue-600">RTX 4090</a></li>
                <li><a href="#" class="hover:text-blue-600">RTX 4080</a></li>
                <li><a href="#" class="hover:text-blue-600">RX 7900 XTX</a></li>
                <li><a href="#" class="hover:text-blue-600">Pro GPUs</a></li>
            </ul>
        </div>
        <div>
            <h4 class="font-extrabold text-slate-900 mb-3 text-xs uppercase tracking-wider">Platform</h4>
            <ul class="space-y-2 font-medium">
                <li><a href="#" class="hover:text-blue-600">Z790 Builds</a></li>
                <li><a href="#" class="hover:text-blue-600">X670E Builds</a></li>
                <li><a href="#" class="hover:text-blue-600">AM5 Bundles</a></li>
                <li><a href="#" class="hover:text-blue-600">LGA1700</a></li>
            </ul>
        </div>
        <div>
            <h4 class="font-extrabold text-slate-900 mb-3 text-xs uppercase tracking-wider">Support</h4>
            <ul class="space-y-2 font-medium">
                <li><a href="#" class="hover:text-blue-600">Warranty</a></li>
                <li><a href="#" class="hover:text-blue-600">Returns</a></li>
                <li><a href="#" class="hover:text-blue-600">Contact Us</a></li>
                <li><a href="#" class="hover:text-blue-600">Build Guide</a></li>
            </ul>
        </div>
    </div>
    <div class="max-w-7xl mx-auto px-6 py-6 border-t border-gray-100 flex justify-between items-center font-semibold">
        <span class="text-slate-800 text-sm font-extrabold">NexaStore PC</span>
        <span>&copy; 2026 NexaStore PC. All rights reserved.</span>
    </div>
</footer>

@endsection
