{{--
    ERP MODULE: Authentication — Register Card (Register Page)
    COMPONENT: RegisterCard
    DESCRIPTION: Right-side registration form card.
    ROUTE: POST /register
--}}

<div class="bg-white rounded-2xl p-8 shadow-[0_0_45px_rgba(0,187,255,0.25)]">
    <h3 class="text-lg font-bold text-gray-900">Create your account</h3>
    <p class="text-xs text-gray-400 mt-1 mb-6">Join ShopEase and start building your dream setup today.</p>

    <form method="POST" action="{{ route('register') }}" class="space-y-4">
        @csrf
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1.5">First name</label>
                <input type="text" name="first_name" value="{{ old('first_name') }}" placeholder="Enter your first name"
                    class="w-full px-4 py-2.5 text-sm rounded-lg border {{ $errors->has('first_name') ? 'border-red-400' : 'border-gray-200' }} text-gray-700 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#00BBFF] focus:border-transparent">
                @error('first_name')
                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1.5">Last name</label>
                <input type="text" name="last_name" value="{{ old('last_name') }}" placeholder="Enter your last name"
                    class="w-full px-4 py-2.5 text-sm rounded-lg border {{ $errors->has('last_name') ? 'border-red-400' : 'border-gray-200' }} text-gray-700 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#00BBFF] focus:border-transparent">
                @error('last_name')
                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div>
            <label class="block text-xs font-semibold text-gray-700 mb-1.5">Email address</label>
            <input type="email" name="email" value="{{ old('email') }}" placeholder="Enter your email address"
                class="w-full px-4 py-2.5 text-sm rounded-lg border {{ $errors->has('email') ? 'border-red-400' : 'border-gray-200' }} text-gray-700 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#00BBFF] focus:border-transparent">
            @error('email')
                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label class="block text-xs font-semibold text-gray-700 mb-1.5">Password</label>
            <input type="password" name="password" placeholder="Create a password"
                class="w-full px-4 py-2.5 text-sm rounded-lg border {{ $errors->has('password') ? 'border-red-400' : 'border-gray-200' }} text-gray-700 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#00BBFF] focus:border-transparent">
            @error('password')
                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label class="block text-xs font-semibold text-gray-700 mb-1.5">Confirm Password</label>
            <input type="password" name="password_confirmation" placeholder="Confirm your password"
                class="w-full px-4 py-2.5 text-sm rounded-lg border border-gray-200 text-gray-700 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#00BBFF] focus:border-transparent">
        </div>

        <label class="flex items-start gap-2 text-xs text-gray-600 cursor-pointer">
            <input type="checkbox" name="agree_terms" checked class="w-3.5 h-3.5 mt-0.5 rounded border-gray-300 text-[#00BBFF] focus:ring-[#00BBFF]">
            <span>I agree to the <a href="#" class="text-[#00BBFF] hover:underline">Terms of Service</a> and <a href="#" class="text-[#00BBFF] hover:underline">Privacy Policy</a></span>
        </label>

        <button type="submit" class="w-full bg-[#00BBFF] hover:bg-[#00a6e0] transition-colors text-white text-sm font-semibold py-3 rounded-lg">
            Sign Up
        </button>
    </form>

    @include('pages.customer.auth.components.shared.social-divider')

    <button type="button" onclick="registerWithGoogle()" aria-label="Continue with Google"
        class="mx-auto flex items-center justify-center w-10 h-10 rounded-full border border-gray-200 hover:bg-gray-50 transition-colors">
        @include('pages.customer.auth.components.shared.google-icon')
    </button>

    <p class="text-center text-xs text-gray-500 mt-4">
        Already have an account?
        <a href="{{ route('login') }}" class="text-[#00BBFF] font-medium hover:underline">Login</a>
    </p>
</div>
