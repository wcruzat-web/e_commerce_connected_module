@extends('layouts.blanks')

@section('title', 'Add Address')

@section('content')

<div class="min-h-[80vh] flex items-center justify-center relative">

    <form method="POST" action="{{ route('addresses.store') }}"
        class="bg-white rounded-xl shadow-lg p-8 w-full max-w-2xl">

        @csrf

        <h1 class="text-2xl font-bold text-center">Add New Address</h1>
        <p class="text-gray-500 text-sm text-center mt-1 mb-8">Enter your address details below.</p>

        <h2 class="font-bold text-gray-800 mb-4">Contact Information</h2>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-6">
            <div>
                <label class="text-sm text-gray-600">Full Name</label>
                <input type="text" name="recipient_name" placeholder="Enter your full name" class="border rounded-lg p-3 w-full mt-2">
            </div>
            <div>
                <label class="text-sm text-gray-600">Phone Number</label>
                <input type="text" name="phone_number" placeholder="Enter your phone number" class="border rounded-lg p-3 w-full mt-2">
            </div>
            <div>
                <label class="text-sm text-gray-600">Address Type</label>
                <select name="address_type" class="border rounded-lg p-3 w-full mt-2 bg-white">
                    <option value="Shipping">Shipping</option>
                    <option value="Billing">Billing</option>
                </select>
            </div>
            <div>
                <label class="text-sm text-gray-600">Country / Region</label>
                <select name="country" class="border rounded-lg p-3 w-full mt-2 bg-white">
                    <option value="Philippines">Philippines</option>
                    <option value="United States">United States</option>
                    <option value="Singapore">Singapore</option>
                </select>
            </div>
        </div>

        <h2 class="font-bold text-gray-800 mb-4">Address Details</h2>

        <div class="mb-5">
            <label class="text-sm text-gray-600">Street Address</label>
            <input type="text" name="street" placeholder="House number, street name, building, etc." class="border rounded-lg p-3 w-full mt-2">
        </div>

        <div class="mb-5">
            <label class="text-sm text-gray-600">Barangay / Apartment, Suite (Optional)</label>
            <input type="text" name="barangay" placeholder="Barangay, apartment, suite, etc." class="border rounded-lg p-3 w-full mt-2">
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-6">
            <div>
                <label class="text-sm text-gray-600">City / Municipality</label>
                <input type="text" name="city" placeholder="Enter city or municipality" class="border rounded-lg p-3 w-full mt-2">
            </div>
            <div>
                <label class="text-sm text-gray-600">Province / State</label>
                <input type="text" name="province" placeholder="Enter province or state" class="border rounded-lg p-3 w-full mt-2">
            </div>
            <div>
                <label class="text-sm text-gray-600">ZIP / Postal Code</label>
                <input type="text" name="postal_code" placeholder="Enter ZIP or postal code" class="border rounded-lg p-3 w-full mt-2">
            </div>
        </div>

        <div class="flex items-start gap-2 mb-8">
            <input type="checkbox" name="is_default" value="1" class="mt-1 accent-sky-500 w-4 h-4">
            <div>
                <p class="font-medium text-sm">Set as default address</p>
                <p class="text-gray-500 text-xs">This address will be used as your default shipping address.</p>
            </div>
        </div>

        <div class="flex justify-end gap-4">
            <a href="{{ route('addresses') }}" class="border rounded-lg px-6 py-3 text-gray-700 hover:bg-gray-50">Cancel</a>
            <button type="submit" class="bg-sky-500 hover:bg-sky-600 text-white px-6 py-3 rounded-lg inline-flex items-center gap-2">
                Save Address <span>&rarr;</span>
            </button>
        </div>

    </form>

    <span class="hidden md:block absolute right-2 bottom-2 text-gray-300 text-xl select-none">&#8250;</span>

</div>

@endsection
