<div class="bg-white rounded-2xl shadow-xl shadow-sky-100 p-10 w-full max-w-md text-center animate-pop">

            <div class="w-16 h-16 mx-auto rounded-full bg-sky-100 flex items-center justify-center mb-5">
                <img src="https://cdn.jsdelivr.net/npm/lucide-static@0.460.0/icons/lock.svg"
                    class="w-8 h-8" alt="">
            </div>

            <h1 class="text-2xl font-bold mb-3">
                Change Password unavailable
            </h1>

            <p class="text-gray-500 text-sm leading-relaxed">
                You signed in with <span class="font-semibold text-gray-700">Google</span>,
                so this account does not use a password. To keep your account secure,
                manage your sign-in options through your Google account.
            </p>

            <a href="{{ route('profile') }}"
               class="mt-6 inline-flex items-center gap-2 text-sky-500 font-medium hover:underline">
                <span>&larr;</span> Back to Profile
            </a>

        </div>
