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

        <h2 class="font-bold text-gray-800 mb-4">Contact Information</h2>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-6">
            <div>
                <label class="text-sm text-gray-600">Full Name</label>
                <input type="text" name="recipient_name" value="{{ old('recipient_name', $addressModel->recipient_name) }}" class="border rounded-lg p-3 w-full mt-2">
            </div>
            <div>
                <label class="text-sm text-gray-600">Phone Number</label>
                <input type="text" name="phone_number" value="{{ old('phone_number', $addressModel->phone_number) }}" class="border rounded-lg p-3 w-full mt-2">
            </div>
            <div>
                <label class="text-sm text-gray-600">Address Type</label>
                <select name="address_type" class="border rounded-lg p-3 w-full mt-2 bg-white">
                    {{-- CHANGES HERE: replaced Shipping/Billing with Home/Work/Other --}}
                    <option value="Home" {{ $addressModel->address_type === 'Home' ? 'selected' : '' }}>Home</option>
                    <option value="Work" {{ $addressModel->address_type === 'Work' ? 'selected' : '' }}>Work</option>
                    <option value="Other" {{ $addressModel->address_type === 'Other' ? 'selected' : '' }}>Other</option>
                </select>
            </div>
            <div>
                <label class="text-sm text-gray-600">Country / Region</label>
                <select name="country" class="border rounded-lg p-3 w-full mt-2 bg-white">
                    <option value="Philippines" {{ ($addressModel->country ?? 'Philippines') === 'Philippines' ? 'selected' : '' }}>Philippines</option>
                    <option value="United States" {{ ($addressModel->country ?? '') === 'United States' ? 'selected' : '' }}>United States</option>
                    <option value="Singapore" {{ ($addressModel->country ?? '') === 'Singapore' ? 'selected' : '' }}>Singapore</option>
                </select>
            </div>
        </div>

        <h2 class="font-bold text-gray-800 mb-4">Address Details</h2>

        <div class="mb-5">
            <label class="text-sm text-gray-600">Street Address</label>
            <input type="text" name="street" value="{{ old('street', $addressModel->street) }}" class="border rounded-lg p-3 w-full mt-2">
        </div>

        <div class="mb-5">
            <label class="text-sm text-gray-600">Barangay / Apartment, Suite (Optional)</label>
            <input type="text" name="barangay" value="{{ old('barangay', $addressModel->barangay) }}" class="border rounded-lg p-3 w-full mt-2">
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-6">
            <div>
                <label class="text-sm text-gray-600">City / Municipality</label>
                <input type="text" name="city" value="{{ old('city', $addressModel->city) }}" class="border rounded-lg p-3 w-full mt-2">
            </div>
            <div>
                <label class="text-sm text-gray-600">Province / State</label>
                <input type="text" name="province" value="{{ old('province', $addressModel->province) }}" class="border rounded-lg p-3 w-full mt-2">
            </div>
            <div>
                <label class="text-sm text-gray-600">ZIP / Postal Code</label>
                <input type="text" name="postal_code" value="{{ old('postal_code', $addressModel->postal_code) }}" class="border rounded-lg p-3 w-full mt-2">
            </div>
        </div>

        <div class="flex items-start gap-2 mb-8">
            <input type="checkbox" name="is_default" value="1" {{ $addressModel->is_default ? 'checked' : '' }} class="mt-1 accent-sky-500 w-4 h-4">
            <div>
                <p class="font-medium text-sm">Set as default address</p>
                <p class="text-gray-500 text-xs">This address will be used as your default shipping address.</p>
            </div>
        </div>

        <div class="flex justify-end gap-4">
            <a href="{{ route('addresses') }}" class="border rounded-lg px-6 py-3 text-gray-700 hover:bg-gray-50">Cancel</a>
            <button type="submit" class="bg-sky-500 hover:bg-sky-600 text-white px-6 py-3 rounded-lg inline-flex items-center gap-2">
                Save Changes <span>&rarr;</span>
            </button>
        </div>

    </form>

</div>

@endsection
