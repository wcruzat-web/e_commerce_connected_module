{{--
    ERP MODULE: Admin Coupons
    DESCRIPTION: Manage coupons — discount % and free shipping codes with expiry dates.
    CONTROLLER: Admin\CouponController
    ROUTES: admin.coupons.index, admin.coupons.store, admin.coupons.destroy
--}}
@extends('layouts.admin')

@section('content')
<div class="flex min-h-screen bg-slate-50" style="font-family: 'Outfit', sans-serif;">
    @include('components.admin.sidebar')

    <div class="flex-1 min-w-0">
        @include('pages.admin.dashboard.components.topbar')

        <div class="p-6 space-y-6">
            {{-- Header --}}
            <div class="flex items-center justify-between">
                <h1 class="text-2xl font-bold text-gray-800">Vouchers</h1>
            </div>

            {{-- Add form --}}
            <div class="bg-white rounded-xl shadow-sm border p-5">
                <h2 class="text-lg font-semibold text-gray-700 mb-4">Add New Voucher</h2>
                <form id="addCouponForm" class="grid grid-cols-1 md:grid-cols-5 gap-4 items-end">
                    @csrf
                    <div>
                        <label class="block text-sm font-medium text-gray-600 mb-1">Code</label>
                        <input type="text" name="code" id="inputCode" required
                            class="w-full px-3 py-2 border rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-600 mb-1">Type</label>
                        <select name="type" id="inputType" required
                            class="w-full px-3 py-2 border rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="discount">Discount (%)</option>
                            <option value="free_shipping">Free Shipping</option>
                        </select>
                    </div>
                    <div id="percentageWrapper">
                        <label class="block text-sm font-medium text-gray-600 mb-1">Discount %</label>
                        <input type="number" name="discount_percentage" id="inputPercentage" min="1" max="100"
                            class="w-full px-3 py-2 border rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-600 mb-1">Max Uses</label>
                        <input type="number" name="max_uses" id="inputMaxUses" min="1" placeholder="Unlimited"
                            class="w-full px-3 py-2 border rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-600 mb-1">Expires At</label>
                        <input type="date" name="expires_at" id="inputExpires" required
                            class="w-full px-3 py-2 border rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div class="md:col-span-5 flex justify-end">
                        <button type="submit"
                            class="px-6 py-2 bg-blue-600 text-white rounded-lg text-sm font-medium hover:bg-blue-700 transition-colors">
                            Add Voucher
                        </button>
                    </div>
                </form>
            </div>

            {{-- Table --}}
            <div class="bg-white rounded-xl shadow-sm border overflow-hidden">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 text-gray-600 uppercase text-xs">
                        <tr>
                            <th class="text-left px-4 py-3 font-semibold">Code</th>
                            <th class="text-left px-4 py-3 font-semibold">Type</th>
                            <th class="text-left px-4 py-3 font-semibold">Discount</th>
                            <th class="text-left px-4 py-3 font-semibold">Uses</th>
                            <th class="text-left px-4 py-3 font-semibold">Expires</th>
                            <th class="text-left px-4 py-3 font-semibold">Status</th>
                            <th class="text-right px-4 py-3 font-semibold">Action</th>
                        </tr>
                    </thead>
                    <tbody id="couponTableBody" class="divide-y">
                        @forelse($coupons as $coupon)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-4 py-3 font-medium text-gray-800">{{ $coupon->code }}</td>
                            <td class="px-4 py-3">
                                @if($coupon->type === 'free_shipping')
                                    <span class="px-2 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-700">Free Shipping</span>
                                @else
                                    <span class="px-2 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-700">Discount</span>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                @if($coupon->type === 'discount')
                                    {{ $coupon->discount_percentage }}%
                                @else
                                    <span class="text-gray-400">—</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-gray-600">
                                {{ $coupon->used_count }}{{ $coupon->max_uses ? '/' . $coupon->max_uses : '' }}
                            </td>
                            <td class="px-4 py-3 text-gray-600">{{ $coupon->expires_at->format('M d, Y') }}</td>
                            <td class="px-4 py-3">
                                @if($coupon->isValid())
                                    <span class="px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-700">Active</span>
                                @else
                                    <span class="px-2 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-700">Expired</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-right">
                                <button type="button" data-id="{{ $coupon->id }}" data-code="{{ $coupon->code }}"
                                    class="delete-coupon px-3 py-1 text-xs font-medium text-red-600 hover:bg-red-50 rounded-lg transition-colors">
                                    Delete
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="px-4 py-8 text-center text-gray-400">No vouchers yet.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
                {{ $coupons->links() }}
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
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
        submitBtn.textContent = 'Saving...';

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
                location.reload();
            } else {
                alert('Error: ' + (res.message || 'Unknown error'));
            }
        })
        .catch(() => alert('Network error'))
        .finally(() => {
            submitBtn.disabled = false;
            submitBtn.textContent = 'Add Voucher';
        });
    });

    document.querySelectorAll('.delete-coupon').forEach(btn => {
        btn.addEventListener('click', function () {
            if (!confirm('Delete voucher "' + this.dataset.code + '"?')) return;
            const id = this.dataset.id;
            const row = this.closest('tr');

            fetch('{{ url("admin/coupons") }}/' + id, {
                method: 'DELETE',
                headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
            })
            .then(r => r.json())
            .then(res => {
                if (res.success) {
                    row.remove();
                } else {
                    alert('Delete failed');
                }
            })
            .catch(() => alert('Network error'));
        });
    });
});
</script>
@endpush