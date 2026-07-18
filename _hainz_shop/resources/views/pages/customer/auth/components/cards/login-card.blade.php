{{--
    ERP MODULE: Authentication — Login Card (Login Page)
    COMPONENT: LoginCard
    DESCRIPTION: Right-side login form card with email, password, remember me, and Google OAuth.
    ROUTE: POST /login
--}}

<div class="bg-white rounded-2xl p-8 shadow-[0_0_45px_rgba(0,187,255,0.25)]">
    <h3 class="text-lg font-bold text-gray-900">Login to your account</h3>
    <p class="text-xs text-gray-400 mt-1 mb-6">Enter your credentials to access your account</p>

    <form method="POST" action="{{ route('login') }}" class="space-y-4">
        @csrf
        <div>
            <label class="block text-xs font-semibold text-gray-700 mb-1.5">Email</label>
            <input type="email" name="email" value="{{ old('email') }}" placeholder="Enter your email"
                class="w-full px-4 py-2.5 text-sm rounded-lg border {{ $errors->has('email') ? 'border-red-400' : 'border-gray-200' }} text-gray-700 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#00BBFF] focus:border-transparent">
            @error('email')
                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <div class="flex items-center justify-between mb-1.5">
                <label class="block text-xs font-semibold text-gray-700">Password</label>
                <a href="{{ route('forgot.password') }}" class="text-xs font-medium text-[#00BBFF] hover:underline">Forgot Password?</a>
            </div>
            <input type="password" name="password" placeholder="Enter your password"
                class="w-full px-4 py-2.5 text-sm rounded-lg border {{ $errors->has('password') ? 'border-red-400' : 'border-gray-200' }} text-gray-700 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#00BBFF] focus:border-transparent">
            @error('password')
                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
            @enderror
        </div>

        <label class="flex items-center gap-2 text-xs text-gray-600 cursor-pointer">
            <input type="checkbox" name="remember" checked class="w-3.5 h-3.5 rounded border-gray-300 text-[#00BBFF] focus:ring-[#00BBFF]">
            Remember me
        </label>

        <button type="submit" class="w-full bg-[#00BBFF] hover:bg-[#00a6e0] transition-colors text-white text-sm font-semibold py-3 rounded-lg">
            Log In
        </button>
    </form>

    @include('pages.customer.auth.components.shared.social-divider')

    <button type="button" onclick="loginWithGoogle()" aria-label="Continue with Google"
        class="mx-auto flex items-center justify-center w-10 h-10 rounded-full border border-gray-200 hover:bg-gray-50 transition-colors">
        @include('pages.customer.auth.components.shared.google-icon')
    </button>

    <p class="text-center text-xs text-gray-500 mt-4">
        Don't have an account?
        <a href="{{ route('register') }}" class="text-[#00BBFF] font-medium hover:underline">Sign up</a>
    </p>
</div>
