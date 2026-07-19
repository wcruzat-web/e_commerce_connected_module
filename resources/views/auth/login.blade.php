{{-- AGNER — login page: restyled with Outfit font, #00BBFF accent (ERPV0.2) --}}
@extends('layouts.guest')

@section('title', 'Login — ShopEase')

@section('content')
<div class="min-h-screen bg-[#f5f5f5] flex items-center justify-center p-6" style="font-family: 'Outfit', sans-serif;">
    <div class="w-full max-w-4xl grid grid-cols-1 md:grid-cols-2 gap-12 items-center">

        {{-- Branding Panel --}}
        <div>
            <h2 class="text-3xl font-extrabold text-gray-900 leading-tight mb-4">
                Welcome <span class="text-[#00BBFF]">back!</span>
            </h2>

            <p class="text-sm text-gray-600 leading-relaxed max-w-sm mb-10">
                Your trusted destination for premium PC parts and components.
                Build, upgrade, and power your dream setup with quality hardware at competitive prices.
            </p>

            <button type="button" class="bg-[#00BBFF] hover:bg-[#00a6e0] transition-colors text-white text-sm font-semibold px-6 py-3 rounded-lg">
                Discount is waiting!
            </button>
        </div>

        {{-- Login Card --}}
        <div class="bg-white rounded-2xl p-8 shadow-[0_0_45px_rgba(0,187,255,0.25)]">
            <h3 class="text-lg font-bold text-gray-900">Login to your account</h3>
            <p class="text-xs text-gray-400 mt-1 mb-6">Enter your credentials to access your account</p>

            <form method="POST" action="{{ route('login.store') }}">
                @csrf

                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1.5">Email</label>
                        {{-- CHANGES HERE: added error display --}}
                        <input type="email" name="email"
                            value="{{ old('email', request()->cookie('remember_login_email', '')) }}"
                            placeholder="Enter your email" autocomplete="username"
                            class="w-full px-4 py-2.5 text-sm rounded-lg border {{ $errors->has('email') ? 'border-red-400' : 'border-gray-200' }} text-gray-700 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#00BBFF] focus:border-transparent">
                        @error('email')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <div class="flex items-center justify-between mb-1.5">
                            <label class="block text-xs font-semibold text-gray-700">Password</label>
                            <a href="{{ route('forgot') }}" class="text-xs font-medium text-[#00BBFF] hover:underline">Forgot Password?</a>
                        </div>
                        <input type="password" name="password"
                            value="{{ request()->cookie('remember_login_password', '') }}"
                            placeholder="Enter your password" autocomplete="current-password"
                            class="w-full px-4 py-2.5 text-sm rounded-lg border border-gray-200 text-gray-700 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#00BBFF] focus:border-transparent">
                    </div>

                    <label class="flex items-center gap-2 text-xs text-gray-600 cursor-pointer">
                        <input type="checkbox" name="remember" {{ request()->cookie('remember_login_email') ? 'checked' : '' }}
                            class="w-3.5 h-3.5 rounded border-gray-300 text-[#00BBFF] focus:ring-[#00BBFF]">
                        Keep me signed in
                    </label>

                    <button type="submit"
                        class="w-full bg-[#00BBFF] hover:bg-[#00a6e0] transition-colors text-white text-sm font-semibold py-3 rounded-lg">
                        Log In
                    </button>
                </div>
            </form>

            {{-- Social Divider --}}
            <div class="flex items-center gap-3 my-5">
                <div class="flex-1 h-px bg-gray-200"></div>
                <span class="text-[11px] text-gray-400 whitespace-nowrap">or continue with</span>
                <div class="flex-1 h-px bg-gray-200"></div>
            </div>

            {{-- Google --}}
            <form method="GET" action="{{ route('google.redirect') }}" class="flex justify-center">
                <button type="submit" aria-label="Continue with Google"
                    class="flex items-center justify-center w-10 h-10 rounded-full border border-gray-200 hover:bg-gray-50 transition-colors">
                    <img src="https://developers.google.com/identity/images/g-logo.png" class="w-5 h-5" alt="Google">
                </button>
            </form>

            <p class="text-center text-xs text-gray-500 mt-4">
                Don't have an account?
                <a href="{{ route('register') }}" class="text-[#00BBFF] font-medium hover:underline">Sign up</a>
            </p>
        </div>

    </div>
</div>

@if(session('registered'))
    <div class="modal-backdrop" id="success-modal">
        <div class="modal-card">
            <div class="modal-check">&#10003;</div>
            <h2 class="text-2xl font-bold mb-2">Account Created!</h2>
            <p class="text-gray-500 text-sm">{{ session('success') ?? 'Your account was created successfully. Please log in.' }}</p>
            <a href="#" id="success-ok"
               class="mt-6 inline-block bg-[#00BBFF] hover:bg-[#00a6e0] text-white px-8 py-3 rounded-lg font-semibold transition hover:-translate-y-0.5">
                Continue to Login
            </a>
        </div>
    </div>

    <script>
    (function () {
        const colors = ['#38bdf8', '#22c55e', '#f59e0b', '#ef4444', '#a855f7'];
        for (let i = 0; i < 40; i++) {
            const c = document.createElement('div');
            c.className = 'confetti';
            c.style.left = (Math.random() * 100) + 'vw';
            c.style.background = colors[i % colors.length];
            c.style.animationDelay = (Math.random() * 0.6) + 's';
            c.style.animationDuration = (1.8 + Math.random() * 1.2) + 's';
            document.body.appendChild(c);
            setTimeout(() => c.remove(), 3600);
        }
        const modal = document.getElementById('success-modal');
        const close = () => { if (modal) modal.remove(); };
        document.getElementById('success-ok')?.addEventListener('click', (e) => { e.preventDefault(); close(); });
        modal?.addEventListener('click', (e) => { if (e.target === modal) close(); });
        setTimeout(close, 6000);
    })();
    </script>
@endif

@endsection

