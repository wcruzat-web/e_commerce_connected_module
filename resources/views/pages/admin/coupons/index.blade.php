@extends('layouts.admin')

@push('styles')
<style>
    .modal-enter { animation: modalIn .25s ease-out; }
    @keyframes modalIn { from { opacity:0; transform:scale(.95) translateY(10px); } to { opacity:1; transform:scale(1) translateY(0); } }
    .toast { animation: toastIn .3s ease-out, toastOut .3s ease-in 2.7s forwards; }
    @keyframes toastIn { from { opacity:0; transform:translateY(-12px); } to { opacity:1; transform:translateY(0); } }
    @keyframes toastOut { to { opacity:0; transform:translateY(-12px); } }
</style>
@endpush

@section('content')
<div class="flex min-h-screen bg-[#F8F9FB]" style="font-family: 'Outfit', sans-serif;">
    @include('components.admin.sidebar')

    <div class="flex-1 min-w-0">
        @include('pages.admin.dashboard.components.topbar')

        <div class="p-4 lg:p-6 space-y-6">

            {{-- Header --}}
            <div class="flex items-center justify-between flex-wrap gap-3">
                <div>
                    <h1 class="text-xl font-bold text-gray-900">Vouchers</h1>
                    <p class="text-sm text-gray-400 mt-0.5">Manage discount codes and free shipping promotions</p>
                </div>
                <button onclick="openModal()"
                    class="flex items-center gap-2 bg-blue-600 text-white text-sm font-medium px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="12" y1="5" x2="12" y2="19"></line>
                        <line x1="5" y1="12" x2="19" y2="12"></line>
                    </svg>
                    New Voucher
                </button>
            </div>

            {{-- Stats cards --}}
            @php
                $allCoupons = \App\Models\Coupon::all();
                $total = $allCoupons->count();
                $active = $allCoupons->filter(fn($c) => $c->isValid())->count();
                $expired = $allCoupons->filter(fn($c) => !$c->isValid())->count();
                $freeShippingCount = $allCoupons->where('type', 'free_shipping')->count();
            @endphp
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="bg-white rounded-xl border border-gray-200 p-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-lg bg-blue-100 flex items-center justify-center shrink-0">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-blue-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"/><line x1="7" y1="7" x2="7.01" y2="7"/></svg>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400 font-medium">Total</p>
                            <p class="text-lg font-bold text-gray-900">{{ $total }}</p>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-xl border border-gray-200 p-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-lg bg-green-100 flex items-center justify-center shrink-0">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-green-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400 font-medium">Active</p>
                            <p class="text-lg font-bold text-gray-900">{{ $active }}</p>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-xl border border-gray-200 p-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-lg bg-red-100 flex items-center justify-center shrink-0">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-red-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400 font-medium">Expired</p>
                            <p class="text-lg font-bold text-gray-900">{{ $expired }}</p>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-xl border border-gray-200 p-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-lg bg-purple-100 flex items-center justify-center shrink-0">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-purple-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="M12 5l7 7-7 7"/></svg>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400 font-medium">Free Shipping</p>
                            <p class="text-lg font-bold text-gray-900">{{ $freeShippingCount }}</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Table --}}
            <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="bg-gray-50 text-gray-500 text-xs uppercase tracking-wider">
                                <th class="text-left px-4 py-3 font-medium">Code</th>
                                <th class="text-left px-4 py-3 font-medium">Type</th>
                                <th class="text-left px-4 py-3 font-medium">Discount</th>
                                <th class="text-left px-4 py-3 font-medium">Usage</th>
                                <th class="text-left px-4 py-3 font-medium">Expires</th>
                                <th class="text-left px-4 py-3 font-medium">Status</th>
                                <th class="text-right px-4 py-3 font-medium">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($coupons as $coupon)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-4 py-3">
                                    <div class="flex items-center gap-2">
                                        <span class="font-mono font-semibold text-gray-900">{{ $coupon->code }}</span>
                                        <button onclick="copyCode('{{ $coupon->code }}')" title="Copy code"
                                            class="text-gray-300 hover:text-gray-500 transition-colors">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="9" y="9" width="13" height="13" rx="2"/><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/></svg>
                                        </button>
                                    </div>
                                </td>
                                <td class="px-4 py-3">
                                    @if($coupon->type === 'free_shipping')
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-700">Free Shipping</span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-700">Discount</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3">
                                    @if($coupon->type === 'discount')
                                        <span class="font-medium text-gray-800">{{ $coupon->discount_percentage }}%</span>
                                    @else
                                        <span class="text-gray-300">&mdash;</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3">
                                    <div class="flex items-center gap-2">
                                        <div class="flex-1 max-w-[80px] h-1.5 bg-gray-100 rounded-full overflow-hidden">
                                            @php $usagePct = $coupon->max_uses ? min(100, round(($coupon->used_count / $coupon->max_uses) * 100)) : 0; @endphp
                                            <div class="h-full bg-blue-500 rounded-full transition-all" style="width: {{ $usagePct }}%"></div>
                                        </div>
                                        <span class="text-gray-600 text-xs whitespace-nowrap">{{ $coupon->used_count }}{{ $coupon->max_uses ? '/' . $coupon->max_uses : '' }}</span>
                                    </div>
                                </td>
                                <td class="px-4 py-3">
                                    <span class="text-gray-600">{{ \Carbon\Carbon::parse($coupon->expires_at)->format('M d, Y') }}</span>
                                </td>
                                <td class="px-4 py-3">
                                    @if($coupon->isValid())
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-700">
                                            <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span>
                                            Active
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-700">
                                            <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span>
                                            Expired
                                        </span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-right">
                                    <button type="button" data-id="{{ $coupon->id }}" data-code="{{ $coupon->code }}"
                                        class="delete-coupon inline-flex items-center gap-1 px-3 py-1.5 text-xs font-medium text-red-600 hover:bg-red-50 rounded-lg transition-colors">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                                        Delete
                                    </button>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="px-4 py-12 text-center">
                                    <div class="flex flex-col items-center gap-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10 text-gray-200" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"/><line x1="7" y1="7" x2="7.01" y2="7"/></svg>
                                        <p class="text-gray-400 text-sm">No vouchers yet</p>
                                        <button onclick="openModal()" class="text-blue-600 text-sm font-medium hover:underline">Create your first voucher</button>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($coupons->hasPages())
                    <div class="px-4 py-3 border-t border-gray-100">
                        {{ $coupons->links() }}
                    </div>
                @endif
            </div>

        </div>
    </div>
</div>

{{-- Add Voucher Modal --}}
<div id="voucherModal" class="fixed inset-0 z-50 flex items-center justify-center hidden">
    <div class="absolute inset-0 bg-black/40" onclick="closeModal()"></div>
    <div class="modal-enter relative bg-white rounded-2xl shadow-xl w-full max-w-lg mx-4 p-6">
        <div class="flex items-center justify-between mb-5">
            <h2 class="text-lg font-bold text-gray-900">New Voucher</h2>
            <button onclick="closeModal()" class="text-gray-300 hover:text-gray-500 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            </button>
        </div>
        <form id="addCouponForm" class="space-y-4">
            @csrf
            <div class="grid grid-cols-2 gap-4">
                <div class="col-span-2">
                    <label class="block text-sm font-medium text-gray-600 mb-1">Coupon Code</label>
                    <div class="relative">
                        <input type="text" name="code" id="inputCode" required placeholder="e.g. SUMMER20"
                            class="w-full px-3 py-2.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 uppercase placeholder:normal-case">
                        <button type="button" onclick="generateCode()" title="Generate random code"
                            class="absolute right-2 top-1/2 -translate-y-1/2 text-gray-300 hover:text-blue-500 transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="16 3 21 3 21 8"/><line x1="4" y1="20" x2="21" y2="3"/><polyline points="21 16 21 21 16 21"/><line x1="15" y1="15" x2="21" y2="21"/><line x1="4" y1="4" x2="9" y2="9"/></svg>
                        </button>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-600 mb-1">Type</label>
                    <select name="type" id="inputType" required
                        class="w-full px-3 py-2.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="discount">Discount (%)</option>
                        <option value="free_shipping">Free Shipping</option>
                    </select>
                </div>
                <div id="percentageWrapper">
                    <label class="block text-sm font-medium text-gray-600 mb-1">Discount %</label>
                    <input type="number" name="discount_percentage" id="inputPercentage" min="1" max="100" placeholder="10"
                        class="w-full px-3 py-2.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-600 mb-1">Max Uses</label>
                    <input type="number" name="max_uses" id="inputMaxUses" min="1" placeholder="Unlimited"
                        class="w-full px-3 py-2.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-600 mb-1">Expires At</label>
                    <input type="date" name="expires_at" id="inputExpires" required
                        class="w-full px-3 py-2.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>
            </div>
            <div class="flex items-center justify-end gap-3 pt-2">
                <button type="button" onclick="closeModal()"
                    class="px-4 py-2 text-sm font-medium text-gray-600 hover:text-gray-800 transition-colors">Cancel</button>
                <button type="submit"
                    class="px-6 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors">Create Voucher</button>
            </div>
        </form>
    </div>
</div>

{{-- Toast container --}}
<div id="toastContainer" class="fixed top-4 right-4 z-[100] flex flex-col gap-2 pointer-events-none"></div>
@endsection

@push('scripts')
<script>
function toast(type, message) {
    const container = document.getElementById('toastContainer');
    const colors = { success: 'bg-green-600', error: 'bg-red-600' };
    const el = document.createElement('div');
    el.className = 'toast pointer-events-auto ' + (colors[type] || 'bg-gray-700') + ' text-white text-sm px-4 py-2.5 rounded-lg shadow-lg flex items-center gap-2';
    el.innerHTML = message;
    container.appendChild(el);
    setTimeout(() => el.remove(), 3000);
}

function openModal() {
    document.getElementById('voucherModal').classList.remove('hidden');
    document.getElementById('inputCode').focus();
}

function closeModal() {
    document.getElementById('voucherModal').classList.add('hidden');
}

function generateCode() {
    const p = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    let code = '';
    for (let i = 0; i < 8; i++) code += p[Math.floor(Math.random() * p.length)];
    document.getElementById('inputCode').value = code;
}

function copyCode(code) {
    navigator.clipboard.writeText(code).then(() => toast('success', 'Copied: ' + code)).catch(() => {});
}

document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('addCouponForm');
    const typeSelect = document.getElementById('inputType');
    const percentageWrapper = document.getElementById('percentageWrapper');
    const percentageInput = document.getElementById('inputPercentage');

    function togglePercentage() {
        if (typeSelect.value === 'free_shipping') {
            percentageWrapper.style.display = 'none';
            percentageInput.removeAttribute('required');
            percentageInput.value = '';
        } else {
            percentageWrapper.style.display = 'block';
            percentageInput.setAttribute('required', 'required');
        }
    }
    typeSelect.addEventListener('change', togglePercentage);
    togglePercentage();

    form.addEventListener('submit', function (e) {
        e.preventDefault();
        const submitBtn = form.querySelector('button[type="submit"]');
        submitBtn.disabled = true;
        submitBtn.textContent = 'Creating...';

        const data = {
            _token: document.querySelector('meta[name="csrf-token"]').content,
            code: document.getElementById('inputCode').value,
            type: typeSelect.value,
            discount_percentage: percentageInput.value || null,
            max_uses: document.getElementById('inputMaxUses').value || null,
            expires_at: document.getElementById('inputExpires').value,
        };

        fetch('{{ route("admin.coupons.store") }}', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': data._token },
            body: JSON.stringify(data),
        })
        .then(r => r.json())
        .then(res => {
            if (res.success) {
                toast('success', 'Voucher <strong>' + data.code + '</strong> created');
                closeModal();
                setTimeout(() => location.reload(), 800);
            } else {
                toast('error', res.message || 'Failed to create voucher');
            }
        })
        .catch(() => toast('error', 'Network error'))
        .finally(() => {
            submitBtn.disabled = false;
            submitBtn.textContent = 'Create Voucher';
        });
    });

    document.querySelectorAll('.delete-coupon').forEach(btn => {
        btn.addEventListener('click', function () {
            const code = this.dataset.code;
            const id = this.dataset.id;
            const row = this.closest('tr');

            if (!confirm('Delete voucher "' + code + '"?')) return;

            fetch('{{ url("admin/coupons") }}/' + id, {
                method: 'DELETE',
                headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
            })
            .then(r => r.json())
            .then(res => {
                if (res.success) {
                    row.remove();
                    toast('success', 'Voucher <strong>' + code + '</strong> deleted');
                } else {
                    toast('error', 'Delete failed');
                }
            })
            .catch(() => toast('error', 'Network error'));
        });
    });
});
</script>
@endpush
