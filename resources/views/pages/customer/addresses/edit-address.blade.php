@extends('layouts.customer')

@section('content')

<div class="px-7 py-6">

    <a href="{{ route('addresses') }}" class="text-sky-500 text-sm hover:underline">&larr; Back to addresses</a>

    <h1 class="text-4xl font-bold mt-2">Edit Address</h1>
    <p class="text-gray-600 mt-1">Update the details for this saved address.</p>

    <form method="POST" action="{{ route('addresses.update', $addressModel->address_id) }}"
        class="mt-6 border rounded-2xl p-6 bg-white shadow-sm max-w-3xl">

        @csrf
        @method('PUT')

        @include('pages.customer.addresses.components.edit-address-contact')
        @include('pages.customer.addresses.components.edit-address-details')
        @include('pages.customer.addresses.components.edit-address-footer')

    </form>

</div>

@endsection
