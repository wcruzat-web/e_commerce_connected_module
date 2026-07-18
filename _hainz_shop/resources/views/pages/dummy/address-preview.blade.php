{{--
    DUMMY PAGE — Address Selection UI Preview
    PURPOSE: Test the address selection + add new address UI before integrating into checkout.
    DATA SOURCE: $addresses from CustomerAddress model
    ROUTE: GET /dummy/addresses
--}}

@extends('layouts.app')

@section('content')

<div class="max-w-3xl mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold text-gray-900 mb-6">Address Selection Preview</h1>
    <p class="text-sm text-gray-400 mb-8">Click an address to select it, or add a new one.</p>

    {{-- Saved Addresses --}}
    <div class="space-y-4 mb-8">
        @foreach ($addresses as $address)
            <label class="address-card block border border-gray-200 rounded-2xl p-5 cursor-pointer transition-colors hover:border-cyan-300 has-[:checked]:border-cyan-500 has-[:checked]:bg-cyan-50/40">
                <input type="radio" name="selected_address" value="{{ $address->address_id }}" class="hidden" {{ $address->is_default ? 'checked' : '' }}>
                <div class="flex items-start justify-between">
                    <div>
                        <div class="flex items-center gap-2 mb-1">
                            <span class="text-sm font-semibold text-gray-900">{{ $address->recipient_name }}</span>
                            @if($address->is_default)
                                <span class="text-[10px] font-semibold bg-cyan-100 text-cyan-700 px-2 py-0.5 rounded-full">DEFAULT</span>
                            @endif
                        </div>
                        <p class="text-xs text-gray-500">{{ $address->street }}, {{ $address->barangay }}</p>
                        <p class="text-xs text-gray-500">{{ $address->city }}, {{ $address->province }} {{ $address->postal_code }}</p>
                        <p class="text-xs text-gray-500">{{ $address->country }}</p>
                        <p class="text-xs text-gray-400 mt-1">{{ $address->phone_number }}</p>
                    </div>
                    <div class="w-5 h-5 rounded-full border-2 border-gray-300 flex items-center justify-center shrink-0 mt-1">
                        <div class="w-2.5 h-2.5 rounded-full {{ $address->is_default ? 'bg-cyan-500' : '' }}"></div>
                    </div>
                </div>
            </label>
        @endforeach
    </div>

    {{-- Add New Address Button --}}
    <button type="button" onclick="document.getElementById('addAddressForm').classList.toggle('hidden')"
        class="w-full flex items-center justify-center gap-2 border-2 border-dashed border-gray-300 rounded-2xl py-4 text-sm font-medium text-gray-500 hover:border-cyan-400 hover:text-cyan-500 transition-colors">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <line x1="12" y1="5" x2="12" y2="19"></line>
            <line x1="5" y1="12" x2="19" y2="12"></line>
        </svg>
        Add New Address
    </button>

    {{-- Add New Address Form (hidden by default) --}}
    <div id="addAddressForm" class="hidden mt-6 border border-gray-200 rounded-2xl p-6">
        <h2 class="text-sm font-semibold text-gray-900 mb-4">New Shipping Address</h2>
        <div class="space-y-4">
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1.5">Recipient Name</label>
                    <input type="text" placeholder="Full name" class="w-full px-4 py-2.5 text-sm rounded-lg border border-gray-200 text-gray-700 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-cyan-400">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1.5">Phone Number</label>
                    <input type="tel" placeholder="+1 (555) 000-0000" class="w-full px-4 py-2.5 text-sm rounded-lg border border-gray-200 text-gray-700 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-cyan-400">
                </div>
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1.5">Street</label>
                <input type="text" placeholder="123 Tech Boulevard" class="w-full px-4 py-2.5 text-sm rounded-lg border border-gray-200 text-gray-700 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-cyan-400">
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1.5">Barangay</label>
                <input type="text" placeholder="Silicon Valley" class="w-full px-4 py-2.5 text-sm rounded-lg border border-gray-200 text-gray-700 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-cyan-400">
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1.5">City</label>
                    <input type="text" placeholder="San Francisco" class="w-full px-4 py-2.5 text-sm rounded-lg border border-gray-200 text-gray-700 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-cyan-400">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1.5">Province</label>
                    <input type="text" placeholder="California" class="w-full px-4 py-2.5 text-sm rounded-lg border border-gray-200 text-gray-700 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-cyan-400">
                </div>
            </div>
            <div class="grid grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1.5">Postal Code</label>
                    <input type="text" placeholder="94105" class="w-full px-4 py-2.5 text-sm rounded-lg border border-gray-200 text-gray-700 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-cyan-400">
                </div>
                <div class="col-span-2">
                    <label class="block text-xs font-medium text-gray-600 mb-1.5">Country</label>
                    <input type="text" placeholder="United States" class="w-full px-4 py-2.5 text-sm rounded-lg border border-gray-200 text-gray-700 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-cyan-400">
                </div>
            </div>
            <div class="flex items-center gap-2">
                <input type="checkbox" id="set_default" class="w-4 h-4 text-cyan-500 focus:ring-cyan-400 rounded">
                <label for="set_default" class="text-xs text-gray-600">Set as default address</label>
            </div>
            <button type="button" class="w-full bg-cyan-500 hover:bg-cyan-600 transition-colors text-white text-sm font-semibold py-2.5 rounded-lg">
                Save Address
            </button>
        </div>
    </div>
</div>

<script>
    document.querySelectorAll('.address-card').forEach(card => {
        card.addEventListener('click', function() {
            document.querySelectorAll('.address-card').forEach(c => {
                c.querySelector('.w-2\\.5').classList.remove('bg-cyan-500');
                c.classList.remove('border-cyan-500', 'bg-cyan-50/40');
                c.classList.add('border-gray-200');
            });
            this.classList.remove('border-gray-200');
            this.classList.add('border-cyan-500', 'bg-cyan-50/40');
            this.querySelector('.w-2\\.5').classList.add('bg-cyan-500');
            this.querySelector('input[type=radio]').checked = true;
        });
    });
</script>

@endsection
