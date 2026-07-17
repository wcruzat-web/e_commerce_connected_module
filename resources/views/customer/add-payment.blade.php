@extends('layouts.blanks')

@php
    $isEdit = isset($method);
    $formAction = $isEdit
        ? route('payment-methods.update', $method->payment_method_id)
        : route('payment-methods.store');
    $selectedType = old('payment_type', $method->payment_type ?? 'Credit Card');
@endphp

@section('title', $isEdit ? 'Edit Payment Method' : 'Add Payment Method')

@section('content')

<div class="max-w-4xl mx-auto">

    <a href="{{ route('payment-methods') }}" class="inline-flex items-center gap-2 text-sky-500 text-sm font-medium hover:underline">
        <span>&larr;</span> Back to Payment Methods
    </a>

    <h1 class="text-3xl font-bold mt-4">{{ $isEdit ? 'Edit Payment Method' : 'Add Payment Method' }}</h1>
    <p class="text-gray-500 mt-2">Add a new payment method to make your checkout faster and more secure.</p>

    <form class="bg-white rounded-xl shadow mt-8 p-8" method="POST" action="{{ $formAction }}">
        @csrf
        @if($isEdit)
            @method('PUT')
        @endif

        {{-- 1. Choose Payment Method --}}
        <h2 class="font-bold text-lg mb-4">1. Choose Payment Method</h2>

        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8" id="payment-method-options">
            @foreach(['Credit Card', 'Debit Card', 'GCash', 'Maya'] as $type)
                <label class="payment-option relative border-2 rounded-xl p-5 flex flex-col justify-center cursor-pointer hover:border-sky-300
                    {{ $selectedType === $type ? 'border-sky-500 bg-sky-50' : 'border-gray-200' }}">
                    <input type="radio" name="payment_type" value="{{ $type }}"
                        class="accent-sky-500 w-4 h-4 absolute top-4 left-4"
                        {{ $selectedType === $type ? 'checked' : '' }}>
                    <span class="font-semibold text-gray-800 text-center mt-2">{{ $type }}</span>
                </label>
            @endforeach
        </div>

        {{-- 2. Account Information --}}
        <h2 class="font-bold text-lg mb-4">2. Account Information</h2>

        <div class="mb-5">
            <label class="text-sm text-gray-600">Account / Card Number</label>
            <input type="text" name="masked_account_number"
                value="{{ old('masked_account_number', $method->masked_account_number ?? '') }}"
                placeholder="1234 5678 9012 3456"
                class="border rounded-lg p-3 w-full mt-2">
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-8">
            <div>
                <label class="text-sm text-gray-600">Account Name</label>
                <input type="text" name="account_name"
                    value="{{ old('account_name', $method->account_name ?? '') }}"
                    placeholder="Name on account"
                    class="border rounded-lg p-3 w-full mt-2">
            </div>
            <div>
                <label class="text-sm text-gray-600">Expiration Date</label>
                <input type="date" name="expiry_date"
                    value="{{ old('expiry_date', isset($method) && $method->expiry_date ? $method->expiry_date->format('Y-m-d') : '') }}"
                    class="border rounded-lg p-3 w-full mt-2">
            </div>
            <div>
                <label class="text-sm text-gray-600">Provider (optional)</label>
                <input type="text" name="provider"
                    value="{{ old('provider', $method->provider ?? '') }}"
                    placeholder="Visa, BPI, etc."
                    class="border rounded-lg p-3 w-full mt-2">
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
document.querySelectorAll('#payment-method-options .payment-option').forEach(function (label) {
    label.addEventListener('click', function () {
        document.querySelectorAll('#payment-method-options .payment-option').forEach(function (el) {
            el.classList.remove('border-sky-500', 'bg-sky-50');
            el.classList.add('border-gray-200');
        });
        label.classList.remove('border-gray-200');
        label.classList.add('border-sky-500', 'bg-sky-50');
    });
});
</script>

@endsection
