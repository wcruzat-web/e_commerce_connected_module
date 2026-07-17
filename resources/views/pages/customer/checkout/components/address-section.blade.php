{{--
    ERP MODULE: Checkout — Address Section
    COMPONENT: AddressSection
    DESCRIPTION: Saved address cards with radio selection, hidden address fields, "Use Another Address" button, and address modal overlay. AJAX save via /checkout/address.
    DATA SOURCE: $addresses from CheckoutController@index (Customer::addresses relationship)
    TODO: Extract JS to separate file when REST API is implemented
--}}
@if($addresses->count())
    <div id="savedAddressCards" class="space-y-3 mb-4">
        @foreach ($addresses as $address)
            <label class="address-card block border border-gray-200 rounded-xl p-4 cursor-pointer transition-colors hover:border-cyan-300 {{ $address->is_default ? 'border-cyan-500 bg-cyan-50/40' : '' }}"
                   data-street="{{ $address->street }}"
                   data-barangay="{{ $address->barangay }}"
                   data-city="{{ $address->city }}"
                   data-province="{{ $address->province }}"
                   data-postal="{{ $address->postal_code }}"
                   data-country="{{ $address->country }}"
                   data-type="{{ $address->address_type }}">
                 <input type="radio" name="selected_address" value="{{ $address->address_id }}" class="hidden" {{ $address->is_default ? 'checked' : '' }}>
                 <div class="flex items-start justify-between">
                     <div>
                         <div class="flex items-center gap-2 mb-1">
                             @if($address->is_default)
                                 <span class="text-[10px] font-semibold bg-cyan-100 text-cyan-700 px-2 py-0.5 rounded-full">DEFAULT</span>
                             @endif
                             <span class="text-[10px] font-medium text-gray-400 uppercase">{{ $address->address_type }}</span>
                         </div>
                         <p class="text-xs text-gray-500">{{ $address->street }}, {{ $address->barangay }}</p>
                         <p class="text-xs text-gray-500">{{ $address->city }}, {{ $address->province }} {{ $address->postal_code }}</p>
                    </div>
                    <div class="flex flex-col items-end gap-1 shrink-0 mt-1">
                        <div class="w-5 h-5 rounded-full border-2 flex items-center justify-center {{ $address->is_default ? 'border-cyan-500' : 'border-gray-300' }}">
                            <div class="w-2.5 h-2.5 rounded-full {{ $address->is_default ? 'bg-cyan-500' : '' }}"></div>
                        </div>
                        <button type="button" class="edit-address-btn text-gray-300 hover:text-cyan-500 transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                            </svg>
                        </button>
                    </div>
                </div>
            </label>
        @endforeach
    </div>
@endif

{{-- Address fields container (hidden until populated) --}}
<div id="addressFields" class="{{ $addresses->where('is_default', true)->count() ? '' : 'hidden' }}">
    <input type="hidden" name="address_type" id="address_type" value="Home">
    <input type="hidden" name="street" id="street">
    <input type="hidden" name="barangay" id="barangay">
    <input type="hidden" name="city" id="city">
    <input type="hidden" name="province" id="province">
    <input type="hidden" name="postal_code" id="postal_code">
    <input type="hidden" name="country" id="country">
</div>

<button type="button" id="addAddressBtn" onclick="openAddressModal(true)"
    class="w-full flex items-center justify-center gap-2 border-2 border-dashed border-gray-300 rounded-xl py-3 text-sm font-medium text-gray-500 hover:border-cyan-400 hover:text-cyan-500 transition-colors">
    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <line x1="12" y1="5" x2="12" y2="19"></line>
        <line x1="5" y1="12" x2="19" y2="12"></line>
    </svg>
    Use Another Address
</button>

{{-- Address Modal Overlay --}}
<div id="addressModal" class="fixed inset-0 z-50 hidden bg-black/40 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-lg p-6 max-h-[90vh] overflow-y-auto">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-sm font-bold text-gray-900">Shipping Address</h3>
            <button type="button" onclick="closeAddressModal()" class="text-gray-400 hover:text-gray-600">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="18" y1="6" x2="6" y2="18"></line>
                    <line x1="6" y1="6" x2="18" y2="18"></line>
                </svg>
            </button>
        </div>
        <div class="space-y-3">
            <input type="hidden" id="modal_address_id">
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs text-gray-500 mb-1">Address Type</label>
                    <select id="modal_type"
                        class="w-full px-3 py-2.5 text-sm rounded-lg border border-gray-200 focus:outline-none focus:ring-2 focus:ring-cyan-400">
                        <option value="Home">Home</option>
                        <option value="Work">Work</option>
                        <option value="Other">Other</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs text-gray-500 mb-1">Street</label>
                    <input type="text" id="modal_street" placeholder="123 Tech Boulevard"
                        class="w-full px-3 py-2.5 text-sm rounded-lg border border-gray-200 focus:outline-none focus:ring-2 focus:ring-cyan-400">
                </div>
            </div>
            <div>
                <label class="block text-xs text-gray-500 mb-1">Barangay</label>
                <input type="text" id="modal_barangay" placeholder="Silicon Valley"
                    class="w-full px-3 py-2.5 text-sm rounded-lg border border-gray-200 focus:outline-none focus:ring-2 focus:ring-cyan-400">
            </div>
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs text-gray-500 mb-1">City</label>
                    <input type="text" id="modal_city" placeholder="San Francisco"
                        class="w-full px-3 py-2.5 text-sm rounded-lg border border-gray-200 focus:outline-none focus:ring-2 focus:ring-cyan-400">
                </div>
                <div>
                    <label class="block text-xs text-gray-500 mb-1">Province</label>
                    <input type="text" id="modal_province" placeholder="California"
                        class="w-full px-3 py-2.5 text-sm rounded-lg border border-gray-200 focus:outline-none focus:ring-2 focus:ring-cyan-400">
                </div>
            </div>
            <div class="grid grid-cols-3 gap-3">
                <div>
                    <label class="block text-xs text-gray-500 mb-1">Postal Code</label>
                    <input type="text" id="modal_postal" placeholder="94105"
                        class="w-full px-3 py-2.5 text-sm rounded-lg border border-gray-200 focus:outline-none focus:ring-2 focus:ring-cyan-400">
                </div>
                <div class="col-span-2">
                    <label class="block text-xs text-gray-500 mb-1">Country</label>
                    <input type="text" id="modal_country" placeholder="United States"
                        class="w-full px-3 py-2.5 text-sm rounded-lg border border-gray-200 focus:outline-none focus:ring-2 focus:ring-cyan-400">
                </div>
            </div>
            <button type="button" onclick="useAddressFromModal()"
                class="w-full bg-cyan-500 hover:bg-cyan-600 text-white text-sm font-semibold py-2.5 rounded-lg transition-colors">
                Use This Address
            </button>
        </div>
    </div>
</div>
