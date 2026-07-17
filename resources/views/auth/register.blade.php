@extends('layouts.guest')

@section('title', 'Register — ShopEase')

@section('content')
<div class="min-h-screen bg-[#f5f5f5] flex items-center justify-center p-6" style="font-family: 'Outfit', sans-serif;">
    <div class="w-full max-w-4xl grid grid-cols-1 md:grid-cols-2 gap-12 items-center">

        {{-- Branding Panel --}}
        <div>
            <h2 class="text-3xl font-extrabold text-gray-900 leading-tight mb-4">
                Build your dream <span class="text-[#00BBFF]">setup with us.</span>
            </h2>

            <p class="text-sm text-gray-600 leading-relaxed max-w-sm mb-10">
                Your trusted destination for premium PC parts and components.
                Build, upgrade, and power your dream setup with quality hardware at competitive prices.
            </p>

            <button type="button" class="bg-[#00BBFF] hover:bg-[#00a6e0] transition-colors text-white text-sm font-semibold px-6 py-3 rounded-lg">
                Hurry Now!
            </button>
        </div>

        {{-- Register Card --}}
        <div class="bg-white rounded-2xl p-8 shadow-[0_0_45px_rgba(0,187,255,0.25)]">
            <h3 class="text-lg font-bold text-gray-900">Create your account</h3>
            <p class="text-xs text-gray-400 mt-1 mb-6">Join ShopEase and start building your dream setup today.</p>

            <form method="POST" action="{{ route('register.store') }}">
                @csrf

                <div class="space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1.5">First name</label>
                            <input type="text" name="first_name" value="{{ old('first_name') }}" placeholder="Enter your first name"
                                class="w-full px-4 py-2.5 text-sm rounded-lg border {{ $errors->has('first_name') ? 'border-red-400' : 'border-gray-200' }} text-gray-700 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#00BBFF] focus:border-transparent">
                            @error('first_name')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1.5">Last name</label>
                            <input type="text" name="last_name" value="{{ old('last_name') }}" placeholder="Enter your last name"
                                class="w-full px-4 py-2.5 text-sm rounded-lg border {{ $errors->has('last_name') ? 'border-red-400' : 'border-gray-200' }} text-gray-700 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#00BBFF] focus:border-transparent">
                            @error('last_name')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1.5">Email address</label>
                        <input type="email" name="email" value="{{ old('email') }}" placeholder="Enter your email address"
                            class="w-full px-4 py-2.5 text-sm rounded-lg border {{ $errors->has('email') ? 'border-red-400' : 'border-gray-200' }} text-gray-700 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#00BBFF] focus:border-transparent">
                        @error('email')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1.5">Phone Number</label>
                        <input type="text" name="phone" value="{{ old('phone') }}" placeholder="Enter your phone number"
                            class="w-full px-4 py-2.5 text-sm rounded-lg border {{ $errors->has('phone') ? 'border-red-400' : 'border-gray-200' }} text-gray-700 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#00BBFF] focus:border-transparent">
                        @error('phone')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1.5">Password</label>
                        <input type="password" name="password" id="reg-password" placeholder="Create a password"
                            class="w-full px-4 py-2.5 text-sm rounded-lg border {{ $errors->has('password') ? 'border-red-400' : 'border-gray-200' }} text-gray-700 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#00BBFF] focus:border-transparent">
                        @error('password')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror

                        <div class="mt-2">
                            <div class="h-1.5 w-full rounded-full bg-gray-200 overflow-hidden">
                                <div id="pw-bar" class="h-full w-0 rounded-full transition-all duration-300"></div>
                            </div>
                            <ul class="mt-2 space-y-1 text-xs">
                                <li id="pw-len" class="flex items-center gap-1.5 text-gray-400">
                                    <span class="pw-dot">○</span> At least 8 characters
                                </li>
                                <li id="pw-cap" class="flex items-center gap-1.5 text-gray-400">
                                    <span class="pw-dot">○</span> One capital letter (A–Z)
                                </li>
                                <li id="pw-num" class="flex items-center gap-1.5 text-gray-400">
                                    <span class="pw-dot">○</span> One number (0–9)
                                </li>
                            </ul>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1.5">Confirm Password</label>
                        <input type="password" name="password_confirmation" placeholder="Confirm your password"
                            class="w-full px-4 py-2.5 text-sm rounded-lg border border-gray-200 text-gray-700 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#00BBFF] focus:border-transparent">
                    </div>

                    <label class="flex items-start gap-2 text-xs text-gray-600 cursor-pointer">
                        <input type="checkbox" name="terms" value="1"
                            class="w-3.5 h-3.5 mt-0.5 rounded border-gray-300 text-[#00BBFF] focus:ring-[#00BBFF]">
                        <span>I agree to the <a href="#" class="text-[#00BBFF] hover:underline">Terms of Service</a> and <a href="#" class="text-[#00BBFF] hover:underline">Privacy Policy</a></span>
                    </label>
                    @error('terms')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror

                    <button type="submit"
                        class="w-full bg-[#00BBFF] hover:bg-[#00a6e0] transition-colors text-white text-sm font-semibold py-3 rounded-lg">
                        Sign Up
                    </button>
                </div>
            </form>

            {{-- Social Divider --}}
            <div class="flex items-center gap-3 my-5">
                <div class="flex-1 h-px bg-gray-200"></div>
                <span class="text-[11px] text-gray-400 whitespace-nowrap">or sign up with</span>
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
                Already have an account?
                <a href="{{ route('login') }}" class="text-[#00BBFF] font-medium hover:underline">Login</a>
            </p>
        </div>

    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const pw = document.getElementById('reg-password');
    if (!pw) return;
    const bar = document.getElementById('pw-bar');
    const liLen = document.getElementById('pw-len');
    const liCap = document.getElementById('pw-cap');
    const liNum = document.getElementById('pw-num');

    const setItem = (li, ok) => {
        if (!li) return;
        li.classList.toggle('text-green-600', ok);
        li.classList.toggle('text-gray-400', !ok);
        const dot = li.querySelector('.pw-dot');
        if (dot) dot.textContent = ok ? '●' : '○';
    };

    const evalPw = () => {
        const v = pw.value;
        const len = v.length >= 8;
        const cap = /[A-Z]/.test(v);
        const num = /[0-9]/.test(v);
        setItem(liLen, len); setItem(liCap, cap); setItem(liNum, num);
        const score = [len, cap, num].filter(Boolean).length;
        bar.style.width = (score / 3 * 100) + '%';
        const colors = ['#ef4444', '#f59e0b', '#22c55e'];
        bar.style.background = colors[score - 1] || '#ef4444';
    };
    pw.addEventListener('input', evalPw);
    evalPw();
});
</script>

@endsection
