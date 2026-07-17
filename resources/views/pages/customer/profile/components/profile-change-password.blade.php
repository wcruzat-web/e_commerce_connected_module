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
