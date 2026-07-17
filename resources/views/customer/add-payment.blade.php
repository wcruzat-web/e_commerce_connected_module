@extends('layouts.blanks')

@php
    $isEdit = isset($method);
    $formAction = $isEdit
        ? route('payment-methods.update', $method->payment_method_id)
        : route('payment-methods.store');
    $selectedType = old('payment_type', $method->payment_type ?? 'Visa');
@endphp

@section('title', $isEdit ? 'Edit Payment Method' : 'Add Payment Method')

@section('content')

<div class="max-w-4xl mx-auto">

    <a href="{{ route('payment-methods') }}" class="inline-flex items-center gap-2 text-sky-500 text-sm font-medium hover:underline">
        <span>&larr;</span> Back to Payment Methods
    </a>

    <h1 class="text-3xl font-bold mt-4">{{ $isEdit ? 'Edit Payment Method' : 'Add Payment Method' }}</h1>
    <p class="text-gray-500 mt-2">Add a new payment method to make your checkout faster and more secure.</p>

    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 text-sm rounded-xl p-4 mt-4">{{ session('success') }}</div>
    @endif

    @if($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-700 text-sm rounded-xl p-4 mt-4">
            <ul class="list-disc pl-5">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        <div id="serverErrors" data-errors='@json($errors->all())' class="hidden"></div>
    @endif

    <form id="paymentForm" class="bg-white rounded-xl shadow mt-8 p-8" method="POST" action="{{ $formAction }}">
        @csrf
        @if($isEdit)
            @method('PUT')
        @endif

        {{-- 1. Choose Payment Method --}}
        <h2 class="font-bold text-lg mb-4">1. Choose Payment Method</h2>

        <div class="grid grid-cols-3 gap-4 mb-8" id="payment-method-options">
            @foreach(['Visa', 'Mastercard', 'GCash'] as $type)
                <label class="payment-option relative border-2 rounded-xl p-5 flex flex-col justify-center cursor-pointer hover:border-sky-300
                    {{ $selectedType === $type ? 'border-sky-500 bg-sky-50' : 'border-gray-200' }}">
                    <input type="radio" name="payment_type" value="{{ $type }}"
                        class="accent-sky-500 w-4 h-4 absolute top-4 left-4"
                        {{ $selectedType === $type ? 'checked' : '' }}>
                    <span class="font-semibold text-gray-800 text-center mt-2">{{ $type }}</span>
                </label>
            @endforeach
            @error('payment_type') <p class="text-xs text-red-500 mt-1 col-span-3">{{ $message }}</p> @enderror
        </div>

        {{-- 2. Account Information --}}
        <h2 class="font-bold text-lg mb-4">2. Account Information</h2>

        <div id="cardFields" class="space-y-4 mb-8">
            <div>
                <label class="text-sm text-gray-600">Cardholder Name</label>
                <input type="text" name="account_name"
                    value="{{ old('account_name', $method->account_name ?? '') }}"
                    placeholder="Alex Morgan"
                    class="border rounded-lg p-3 w-full mt-2 {{ $errors->has('account_name') ? 'border-red-400' : '' }}">
            </div>
            <div>
                <label class="text-sm text-gray-600">Card Number</label>
                <input type="text" name="masked_account_number"
                    value="{{ old('masked_account_number', $method->masked_account_number ?? '') }}"
                    placeholder="0123 4567 8901 2345" maxlength="19"
                    class="border rounded-lg p-3 w-full mt-2 {{ $errors->has('masked_account_number') ? 'border-red-400' : '' }}">
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="text-sm text-gray-600">Expiry Date</label>
                    <input type="text" name="expiry_date"
                        value="{{ old('expiry_date', isset($method) && $method->expiry_date ? $method->expiry_date->format('m/y') : '') }}"
                        placeholder="MM/YY" maxlength="5"
                        class="border rounded-lg p-3 w-full mt-2 {{ $errors->has('expiry_date') ? 'border-red-400' : '' }}">
                </div>
                <div>
                    <label class="text-sm text-gray-600">CVV</label>
                    <input type="password" name="cvv" placeholder="•••" maxlength="4"
                        class="border rounded-lg p-3 w-full mt-2 {{ $errors->has('cvv') ? 'border-red-400' : '' }}">
                </div>
            </div>
        </div>

        <div id="gcashFields" class="space-y-4 mb-8 hidden">
            <div>
                <label class="text-sm text-gray-600">GCash Name</label>
                <input type="text" name="gcash_name"
                    value="{{ old('gcash_name', $method->account_name ?? '') }}"
                    placeholder="Alex Morgan"
                    class="border rounded-lg p-3 w-full mt-2 {{ $errors->has('gcash_name') ? 'border-red-400' : '' }}">
            </div>
            <div>
                <label class="text-sm text-gray-600">GCash Number</label>
                <div class="flex mt-2">
                    <span class="inline-flex items-center px-3 py-3 text-sm rounded-l-lg border border-r-0 border-gray-200 bg-gray-50 text-gray-500 font-medium">+63</span>
                    <input type="text" name="gcash_number"
                        value="{{ old('gcash_number', '') }}"
                        placeholder="9123456789" maxlength="10"
                        class="w-full px-4 py-2.5 text-sm rounded-r-lg border border-gray-200 bg-gray-100 text-gray-700 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-cyan-400 focus:border-transparent {{ $errors->has('gcash_number') ? 'border-red-400' : '' }}">
                </div>
            </div>
        </div>

        <div class="flex items-start gap-2 mb-8">
            <input type="checkbox" name="is_default" value="1"
                {{ old('is_default', $method->is_default ?? false) ? 'checked' : '' }}
                class="mt-1 accent-sky-500 w-4 h-4">
            <div>
                <p class="font-medium text-sm">Set as default payment method</p>
                <p class="text-gray-500 text-xs">This payment method will be used for your future purchases.</p>
            </div>
        </div>

        <div class="flex justify-end gap-4">
            <a href="{{ route('payment-methods') }}" class="border rounded-lg px-6 py-3 text-gray-700 hover:bg-gray-50">Cancel</a>
            <button type="submit" class="bg-sky-500 hover:bg-sky-600 text-white px-6 py-3 rounded-lg inline-flex items-center gap-2">
                {{ $isEdit ? 'Update Payment Method' : 'Save Payment Method' }} <span>&rarr;</span>
            </button>
        </div>

    </form>

</div>

<script>
function togglePaymentFields(type) {
    document.getElementById('cardFields').classList.toggle('hidden', type !== 'Visa' && type !== 'Mastercard');
    document.getElementById('gcashFields').classList.toggle('hidden', type !== 'GCash');
}

function toastNotify(type, message) {
    var container = document.getElementById('toastContainer');
    if (!container) {
        container = document.createElement('div');
        container.id = 'toastContainer';
        container.className = 'fixed top-5 right-5 z-[9999] flex flex-col gap-2 pointer-events-none';
        document.body.appendChild(container);
    }
    var colors = { success: 'bg-green-500', error: 'bg-red-500', info: 'bg-blue-500' };
    var toast = document.createElement('div');
    toast.className = (colors[type] || 'bg-gray-500') + ' text-white text-sm px-5 py-3 rounded-xl shadow-lg pointer-events-auto animate-slide-in';
    toast.textContent = message;
    container.appendChild(toast);
    setTimeout(function () { toast.remove(); }, 3000);
}

document.querySelectorAll('#payment-method-options .payment-option').forEach(function (label) {
    label.addEventListener('click', function () {
        document.querySelectorAll('#payment-method-options .payment-option').forEach(function (el) {
            el.classList.remove('border-sky-500', 'bg-sky-50');
            el.classList.add('border-gray-200');
        });
        label.classList.remove('border-gray-200');
        label.classList.add('border-sky-500', 'bg-sky-50');

        var radio = label.querySelector('input[type="radio"]');
        if (radio) togglePaymentFields(radio.value);
    });
});

(function () {
    var checked = document.querySelector('#payment-method-options input[type="radio"]:checked');
    if (checked) togglePaymentFields(checked.value);
})();

(function () {
    var el = document.getElementById('serverErrors');
    if (el) {
        try {
            var errors = JSON.parse(el.getAttribute('data-errors'));
            errors.forEach(function (msg) { toastNotify('error', msg); });
        } catch(e) {}
    }
})();
</script>

@endsection
