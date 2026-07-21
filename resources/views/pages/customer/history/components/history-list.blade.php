<div class="stagger space-y-5 mt-4" id="order-list">

        @forelse ($orders as $order)
        <div class="order-card bg-white rounded-2xl shadow-sm border border-gray-200 p-6 hover-lift"
             data-search="{{ strtolower('#'.str_pad($order->order_id,5,'0',STR_PAD_LEFT).' '.$order->items->pluck('product_name')->implode(' ')) }}">

            <div class="flex items-center justify-between mb-5">
                <div class="flex items-center gap-3">
                    <span class="text-sm font-semibold text-gray-900">{{ $order->order_number ?? '#'.str_pad($order->order_id, 5, '0', STR_PAD_LEFT) }}</span>
                    <span class="text-sm text-gray-400">Placed {{ $order->created_at->format('M d, Y') }}</span>
                </div>
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-700">
                    <img src="https://cdn.jsdelivr.net/npm/lucide-static@0.460.0/icons/check-circle.svg" class="w-3.5 h-3.5" alt="">
                    Delivered
                </span>
            </div>

            <div class="space-y-4">
                @foreach ($order->items as $item)
                <div class="flex items-center gap-4">
                    @php $prodImg = $item->product?->image_url ?: $item->product_image; @endphp
                    <img src="{{ $prodImg ?: 'https://picsum.photos/seed/order'.$item->order_item_id.'/200/200' }}"
                         alt="{{ $item->product_name }}"
                         class="w-16 h-16 object-cover rounded-xl border border-gray-100">
                    <div class="flex-1 min-w-0">
                        <h3 class="font-semibold text-gray-900 truncate">{{ $item->product_name }}</h3>
                        <p class="text-sm text-gray-500">Qty: {{ $item->quantity }} &nbsp;·&nbsp; ₱{{ number_format($item->unit_price, 2) }}</p>
                    </div>
                    <div class="flex items-center gap-3 shrink-0">
                        <p class="font-bold text-gray-900">₱{{ number_format($item->unit_price * $item->quantity, 2) }}</p>
                        @if(in_array($item->product_id, $reviewedProductIds))
                            <span class="text-xs font-medium text-yellow-500">Rated</span>
                        @else
                            <button type="button" onclick="openRateModal({{ $item->product_id }}, '{{ $item->product_name }}')" class="text-xs font-semibold text-sky-600 hover:text-sky-700 underline">Rate</button>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>

            <div class="flex items-center gap-2 text-xs mt-5 pt-5 border-t border-gray-100 text-emerald-600 font-medium">
                <img src="https://cdn.jsdelivr.net/npm/lucide-static@0.460.0/icons/check-circle.svg" class="w-4 h-4" alt="">
                Order Placed
                <span class="w-10 h-px bg-emerald-200"></span>
                <img src="https://cdn.jsdelivr.net/npm/lucide-static@0.460.0/icons/truck.svg" class="w-4 h-4" alt="">
                Shipped
                <span class="w-10 h-px bg-emerald-200"></span>
                <img src="https://cdn.jsdelivr.net/npm/lucide-static@0.460.0/icons/package-check.svg" class="w-4 h-4" alt="">
                Delivered
            </div>
        </div>
        @empty
        <div class="flex flex-col items-center justify-center text-center py-20 animate-fade-up">
            <div class="w-28 h-28 rounded-full bg-emerald-50 flex items-center justify-center mb-6">
                <img src="https://cdn.jsdelivr.net/npm/lucide-static@0.460.0/icons/archive.svg" class="w-14 h-14" alt="">
            </div>
            <h3 class="text-xl font-bold text-gray-900">No order history yet</h3>
            <p class="text-gray-500 mt-2 max-w-sm">Delivered orders will be archived here so you can look back on what you've bought.</p>
            <a href="{{ route('products.index') }}"
               class="mt-6 inline-flex items-center gap-2 bg-sky-500 hover:bg-sky-600 text-white px-6 py-3 rounded-lg font-semibold transition hover:-translate-y-0.5">
                Browse products
            </a>
        </div>
        @endforelse

    </div>

    <div class="mt-6">
        {{ $orders->withQueryString()->links() }}
    </div>

    <p id="no-orders" class="hidden text-center text-gray-400 py-16">No orders match your search.</p>

    {{-- Rating Modal --}}
    <div id="rateModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/40" onclick="if(event.target===this)closeRateModal()">
        <div class="bg-white rounded-2xl shadow-xl p-6 w-full max-w-md mx-4">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-bold text-gray-900">Rate Product</h3>
                <button type="button" onclick="closeRateModal()" class="text-gray-400 hover:text-gray-600">&times;</button>
            </div>
            <p id="rateProductName" class="text-sm text-gray-500 mb-4"></p>
            <form id="rateForm" method="POST" action="{{ route('shop.review') }}">
                @csrf
                <input type="hidden" name="product_id" id="rateProductId">
                <input type="hidden" name="rating" id="rateRating" value="0">
                <div class="flex items-center gap-1 mb-4">
                    @for ($i = 1; $i <= 5; $i++)
                    <button type="button" onclick="setRate({{ $i }})" class="rate-star text-3xl text-gray-300 hover:text-yellow-400 transition" data-star="{{ $i }}">&#9733;</button>
                    @endfor
                </div>
                <textarea name="comment" rows="3" placeholder="Share your experience (optional)" class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm resize-none focus:outline-none focus:ring-2 focus:ring-sky-400 mb-4"></textarea>
                <button type="submit" class="w-full bg-sky-500 hover:bg-sky-600 text-white font-semibold py-3 rounded-xl transition">Submit Review</button>
            </form>
        </div>
    </div>

    <script>
    function openRateModal(productId, productName) {
        document.getElementById('rateProductId').value = productId;
        document.getElementById('rateProductName').textContent = productName;
        document.getElementById('rateRating').value = 0;
        document.querySelectorAll('.rate-star').forEach(function(s) { s.classList.remove('text-yellow-400'); s.classList.add('text-gray-300'); });
        document.getElementById('rateModal').classList.remove('hidden');
        document.getElementById('rateModal').classList.add('flex');
    }
    function closeRateModal() {
        document.getElementById('rateModal').classList.add('hidden');
        document.getElementById('rateModal').classList.remove('flex');
    }
    function setRate(val) {
        document.getElementById('rateRating').value = val;
        document.querySelectorAll('.rate-star').forEach(function(s) {
            var star = parseInt(s.getAttribute('data-star'));
            if (star <= val) {
                s.classList.remove('text-gray-300');
                s.classList.add('text-yellow-400');
            } else {
                s.classList.remove('text-yellow-400');
                s.classList.add('text-gray-300');
            }
        });
    }
    document.getElementById('rateForm').addEventListener('submit', function(e) {
        var rating = document.getElementById('rateRating').value;
        if (rating === '0') { e.preventDefault(); alert('Please select a rating.'); }
    });
    </script>
