{{-- [AGNER] Profile page — personal info, password, account info, saved addresses --}}

@extends('layouts.customer')

@section('content')

<div class="px-7 py-6">

    <!-- Title -->
    <h1 class="text-4xl font-bold">
        My Profile
    </h1>

    <p class="text-gray-600 mt-1">
        Manage your personal information and account settings
    </p>

    <!-- Profile Information -->
    <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data"
        class="mt-6 border rounded-2xl p-6 bg-white shadow-sm">
        @csrf

        <div class="flex justify-between items-center mb-5">

            <h2 class="font-semibold text-lg">
                Profile Information
            </h2>

            <button type="submit"
                class="border-2 border-sky-500 text-sky-500 hover:bg-sky-500 hover:text-white px-8 py-2 rounded-lg font-medium transition">
                Save Changes
            </button>

        </div>

        @if($errors->any())
            <div class="mb-4 rounded-lg bg-red-50 border border-red-200 text-red-700 text-sm p-3">
                <ul class="list-disc pl-5">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="flex flex-col lg:flex-row gap-8">

            <!-- Profile Picture -->

            <div class="flex flex-col items-center shrink-0">

                <div class="relative">

                    <div class="w-36 h-36 rounded-full bg-sky-200 flex items-center justify-center text-4xl overflow-hidden">
                        @if($customer->profile_picture)
                            <img id="preview" src="{{ Str::startsWith($customer->profile_picture, ['http://', 'https://']) ? $customer->profile_picture : asset($customer->profile_picture) }}" alt="{{ $customer->full_name }}"
                                class="w-full h-full object-cover">
                        @else
                            <span id="preview" class="text-sky-600 font-bold">
                                {{ strtoupper(substr($customer->first_name, 0, 1)) }}{{ strtoupper(substr($customer->last_name, 0, 1)) }}
                            </span>
                        @endif
                    </div>

                    <button type="button" onclick="document.getElementById('profile_picture').click()"
                        class="absolute bottom-1 right-1 bg-white rounded-full shadow p-2 hover:bg-gray-100"
                        title="Upload new photo">
                        <img src="https://cdn.jsdelivr.net/npm/lucide-static@0.460.0/icons/camera.svg"
                            class="w-5 h-5" alt="Upload">
                    </button>

                    <input type="file" id="profile_picture" name="profile_picture"
                        accept="image/*" class="hidden">
                </div>

                <p class="text-xs text-gray-400 mt-2 text-center">JPG/PNG, max 2MB</p>

            </div>

            <!-- Form -->

            <div class="flex-1">

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">

                    <div>
                        <label class="font-medium">First Name</label>
                        <input type="text" name="first_name" value="{{ old('first_name', $customer->first_name) }}"
                            class="mt-2 w-full border rounded px-3 py-2">
                    </div>

                    <div>
                        <label class="font-medium">Last Name</label>
                        <input type="text" name="last_name" value="{{ old('last_name', $customer->last_name) }}"
                            class="mt-2 w-full border rounded px-3 py-2">
                    </div>

                    <div>
                        <label class="font-medium">Email Address</label>
                        <input type="email" name="email" value="{{ old('email', $customer->email) }}"
                            class="mt-2 w-full border rounded px-3 py-2">
                    </div>

                    <div>
                        <label class="font-medium">Phone Number</label>
                        <input type="text" name="phone_number" value="{{ old('phone_number', $customer->phone_number) }}"
                            class="mt-2 w-full border rounded px-3 py-2">
                    </div>

                </div>

            </div>

        </div>
    </form>

    <!-- Change Password -->

    <div class="mt-5 border rounded-2xl p-6 bg-white shadow-sm">

        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">

            <div class="flex gap-5 items-center">

                <div
                    class="w-16 h-16 rounded-full bg-sky-100 flex items-center justify-center shrink-0">
                    <img src="https://cdn.jsdelivr.net/npm/lucide-static@0.460.0/icons/lock.svg"
                        class="w-7 h-7" alt="">
                </div>

                <div>

                    <h2 class="font-semibold text-lg">

                        Change Password

                    </h2>

                    <p class="text-gray-500 text-sm">

                        Update your password to keep your account secure.

                    </p>

                </div>

            </div>

            @if($isGoogle ?? false)
                <span class="inline-flex items-center gap-2 text-sm text-gray-500 bg-gray-100 border border-gray-200 px-4 py-2 rounded-lg">
                    <img src="https://cdn.jsdelivr.net/npm/lucide-static@0.460.0/icons/lock.svg"
                        class="w-4 h-4" alt="">
                    Via Google &mdash; password not available
                </span>
            @else
                <a href="{{ route('change-password') }}">
                    <button type="button"
                        class="border-2 border-sky-500 text-sky-500 hover:bg-sky-500 hover:text-white px-8 py-2 rounded-lg transition w-full sm:w-auto">
                        Change Password
                    </button>
                </a>
            @endif

        </div>

    </div>

    <!-- Account Information -->

    <div class="mt-5 border rounded-2xl p-6 bg-white shadow-sm">

        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">

            <div class="flex gap-5 items-center">

                <div
                    class="w-16 h-16 rounded-full bg-sky-100 flex items-center justify-center shrink-0">
                    <img src="https://cdn.jsdelivr.net/npm/lucide-static@0.460.0/icons/shield.svg"
                        class="w-7 h-7" alt="">
                </div>

                <div>

                    <h2 class="font-semibold text-lg">

                        Account Information

                    </h2>

                    <p class="text-gray-500 text-sm">

                        View your account status and join date.

                    </p>

                </div>

            </div>

            <div class="flex flex-wrap items-center gap-4 sm:gap-6">

                <div class="text-right">

                    <p class="text-gray-500 text-sm">

                        Member Since

                    </p>

                    <p>

                        {{ $customer->created_at->format('M d, Y') }}

                    </p>

                </div>

                <div>
                    <p class="text-gray-500 text-sm">
                        Account Status
                    </p>
                    @if($customer->email_verified_at)
                        <span class="inline-flex items-center gap-1 bg-green-100 text-green-700 px-4 py-1 rounded-full text-sm font-semibold">
                            <img src="https://cdn.jsdelivr.net/npm/lucide-static@0.460.0/icons/check-circle.svg"
                                class="w-4 h-4" alt=""> Verified
                        </span>
                    @else
                        <span class="bg-yellow-100 text-yellow-700 px-4 py-1 rounded-full text-sm font-semibold">
                            {{ $customer->status }}
                        </span>
                    @endif
                </div>

            </div>

        </div>

    </div>

    {{-- [AGNER] Saved addresses section, same data source & card style as addresses page --}}

    <div class="mt-5 border rounded-2xl p-6 bg-white shadow-sm">

        <div class="flex justify-between items-center mb-5">

            <h2 class="font-semibold text-lg">
                Saved Addresses
            </h2>

            <a href="{{ route('add-address') }}">
                <button type="button"
                    class="border-2 border-sky-500 text-sky-500 hover:bg-sky-500 hover:text-white px-8 py-2 rounded-lg font-medium transition">
                    + Add New Address
                </button>
            </a>

        </div>

        <div class="grid grid-cols-2 gap-5">

            @forelse($addresses as $address)

            <div class="border-2 {{ $address->is_default ? 'border-sky-500' : 'border-gray-200' }} rounded-2xl overflow-hidden bg-white">

                <div class="p-5">

                    <div class="flex justify-between">

                        <div class="flex gap-4">

                            <div class="w-12 h-12 rounded-full bg-sky-100 flex items-center justify-center">

                                <img src="https://cdn.jsdelivr.net/npm/lucide-static@0.460.0/icons/{{ $address->address_type === 'Work' ? 'building' : ($address->address_type === 'Other' ? 'map-pin' : 'home') }}.svg"
                                    class="w-6 h-6" alt="">

                            </div>

                            <div>

                                <div class="flex items-center gap-3">

                                    <h3 class="font-semibold text-xl">
                                        {{ $address->address_type }}
                                    </h3>

                                    @if($address->is_default)
                                    <span class="inline-flex items-center gap-1 bg-sky-100 text-sky-600 text-xs px-3 py-1 rounded-full">

                                        <img src="https://cdn.jsdelivr.net/npm/lucide-static@0.460.0/icons/check-circle.svg" class="w-3 h-3" alt=""> Default
                                    </span>
                                    @endif

                                </div>

                                <p class="mt-2 font-medium">
                                    {{ $address->recipient_name }}
                                </p>

                                <p>
                                    {{ $address->phone_number }}
                                </p>

                                <p class="mt-3 leading-6 text-gray-700">
                                    {{ $address->fullAddress }}
                                </p>

                            </div>

                        </div>

                    </div>

                </div>

                <div class="border-t flex">

                    <a href="{{ route('addresses.edit', $address->address_id) }}"
                        class="w-1/2 py-3 text-sky-500 hover:bg-sky-50 transition text-center">

                        Edit
                    </a>

                    <form method="POST" action="{{ route('addresses.destroy', $address->address_id) }}"
                        class="w-1/2"
                        onsubmit="return confirm('Delete this address?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                            class="w-full py-3 text-red-500 hover:bg-red-50 transition border-l">

                            Delete
                        </button>
                    </form>

                </div>

            </div>

            @empty

            <div class="col-span-2 border-2 border-dashed border-sky-300 rounded-2xl p-10 text-center text-gray-400">
                No saved addresses yet.
            </div>

            @endforelse

        </div>

    </div>

    <!-- Help -->

    <div class="mt-8 border-t pt-5 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div class="flex gap-4">
            <div
                class="w-10 h-10 rounded-full bg-sky-500 text-white flex items-center justify-center shrink-0">
                ?
            </div>
            <div>
                <h3 class="font-semibold">
                    Need help?
                </h3>
                <p class="text-sm text-gray-500">
                    If you have any questions or need assistance, our support team is here to help.
                </p>
            </div>
        </div>
        <button
            class="bg-sky-500 hover:bg-sky-600 text-white px-8 py-3 rounded-lg w-full sm:w-auto">
            Contact Support &rarr;
        </button>
    </div>

</div>

@endsection
