@extends('layouts.blanks')

@section('title', 'Add Address')

@section('content')

<div class="flex items-center justify-center relative">

    <form method="POST" action="{{ route('addresses.store') }}"
        class="bg-white rounded-xl shadow-lg p-8 w-full max-w-2xl">

        @csrf

        <h1 class="text-2xl font-bold text-center">Add New Address</h1>
        <p class="text-gray-500 text-sm text-center mt-1 mb-8">Enter your address details below.</p>

        @include('pages.customer.addresses.components.add-address-contact')
        @include('pages.customer.addresses.components.add-address-details')
        @include('pages.customer.addresses.components.add-address-footer')

    </form>

    <span class="hidden md:block absolute right-2 bottom-2 text-gray-300 text-xl select-none">&#8250;</span>

</div>

@endsection
