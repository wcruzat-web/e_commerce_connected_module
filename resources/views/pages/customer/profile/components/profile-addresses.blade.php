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
                        class="w-1/2 js-confirm-delete">
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
