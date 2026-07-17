<form method="POST" action="{{ route('change-password.update') }}"
            class="bg-white rounded-2xl shadow-xl shadow-sky-100 p-10 w-full max-w-md animate-pop">

            @csrf

            <h1 class="text-2xl font-bold text-center mb-8">Change Password</h1>

            <div class="mb-5">
                <label class="text-sm font-semibold text-gray-700">Current Password</label>
                <input type="password" name="current_password" placeholder="Enter your current password"
                    class="border rounded-lg p-3 w-full mt-2 focus:outline-none focus:ring-2 focus:ring-sky-400 @error('current_password') border-red-400 @enderror">
                @error('current_password')<span class="field-error">{{ $message }}</span>@enderror
            </div>

            <div class="mb-5">
                <label class="text-sm font-semibold text-gray-700">New Password</label>
                <input type="password" name="password" placeholder="At least 8 characters"
                    class="border rounded-lg p-3 w-full mt-2 focus:outline-none focus:ring-2 focus:ring-sky-400 @error('password') border-red-400 @enderror">
                @error('password')<span class="field-error">{{ $message }}</span>@enderror
            </div>

            <div class="mb-8">
                <label class="text-sm font-semibold text-gray-700">Confirm Password</label>
                <input type="password" name="password_confirmation" placeholder="Confirm your new password"
                    class="border rounded-lg p-3 w-full mt-2 focus:outline-none focus:ring-2 focus:ring-sky-400">
            </div>

            <button type="submit" class="w-full bg-sky-500 hover:bg-sky-600 text-white font-medium py-3 rounded-lg transition hover:-translate-y-0.5">
                Change Password
            </button>

            <a href="{{ route('profile') }}" class="mt-6 flex items-center justify-center gap-2 text-sky-500 font-medium hover:underline">
                <span>&larr;</span> Back
            </a>

        </form>
