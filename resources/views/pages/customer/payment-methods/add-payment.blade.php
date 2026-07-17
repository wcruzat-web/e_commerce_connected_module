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

    @include('pages.customer.payment-methods.components.add-payment-header')

    <form id="paymentForm" class="bg-white rounded-xl shadow mt-8 p-8" method="POST" action="{{ $formAction }}">
        @csrf
        @if($isEdit)
            @method('PUT')
        @endif

        @include('pages.customer.payment-methods.components.add-payment-method-select')

        <h2 class="font-bold text-lg mb-4">2. Account Information</h2>

        @include('pages.customer.payment-methods.components.add-payment-card-fields')
        @include('pages.customer.payment-methods.components.add-payment-gcash-fields')
        @include('pages.customer.payment-methods.components.add-payment-footer')

    </form>

</div>

@include('pages.customer.payment-methods.components.add-payment-scripts')

@endsection
