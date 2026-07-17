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
