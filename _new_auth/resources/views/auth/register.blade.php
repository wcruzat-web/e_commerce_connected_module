@extends('layouts.app')

@section('content')

<div class="min-h-screen flex items-center justify-center bg-gray-100">

    <div class="relative w-[1120px] h-[720px] bg-white border border-gray-500">

        <!-- Logo -->
        <div class="absolute left-8 top-6">
            <h1 class="text-[32px] font-bold">
                Shop<span class="text-sky-500">Ease</span>
            </h1>
        </div>

        <!-- Left Side -->
        <div class="absolute left-8 top-36 w-[340px]">

            <h1 class="text-[30px] font-bold leading-tight">
                Build your dream
                <br>
                <span class="text-sky-500">setup with us.</span>
            </h1>

            <p class="mt-6 text-[15px] leading-6 text-justify font-semibold text-gray-800">
                Your trusted destination for premium PC parts and components.
                Build, upgrade, and power your dream setup with quality
                hardware at competitive prices.
            </p>

            <button
                class="mt-24 bg-sky-500 hover:bg-sky-600 text-white font-semibold px-6 py-2 rounded-md shadow">
                Hurry Now!
            </button>

        </div>

        <!-- Register Card -->
        <div
            class="absolute right-10 top-7 w-[530px] bg-white rounded-md p-6 shadow-[0_0_35px_rgba(56,189,248,.35)] animate-pop">

            <h2 class="text-[28px] font-bold text-gray-800">
                Create your account
            </h2>

            <p class="text-xs text-gray-500 mt-1 mb-5">
                Join ShopEase and start building your dream setup today.
            </p>

            <form method="POST" action="{{ route('register') }}">
                @csrf

                <!-- Names -->
                <div class="grid grid-cols-2 gap-3">

                    <div>
                        <label class="text-sm font-medium">First name</label>

                        <input
                            type="text"
                            name="first_name"
                            value="{{ old('first_name') }}"
                            class="mt-1 w-full border border-gray-300 rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-sky-400 @error('first_name') border-red-400 @enderror"
                            placeholder="Enter your first name">
                        @error('first_name')<span class="field-error">{{ $message }}</span>@enderror
                    </div>

                    <div>
                        <label class="text-sm font-medium">Last name</label>

                        <input
                            type="text"
                            name="last_name"
                            value="{{ old('last_name') }}"
                            class="mt-1 w-full border border-gray-300 rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-sky-400 @error('last_name') border-red-400 @enderror"
                            placeholder="Enter your last name">
                        @error('last_name')<span class="field-error">{{ $message }}</span>@enderror
                    </div>

                </div>

                <!-- Email -->
                <div class="mt-3">

                    <label class="text-sm font-medium">
                        Email address
                    </label>

                    <input
                        type="email"
                        name="email"
                        value="{{ old('email') }}"
                        class="mt-1 w-full border border-gray-300 rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-sky-400 @error('email') border-red-400 @enderror"
                        placeholder="Enter your email address">
                @error('email')<span class="field-error">{{ $message }}</span>@enderror

                </div>

                <!-- Password -->
                <div class="mt-3">

                    <label class="text-sm font-medium">
                        Password
                    </label>

                    <input
                        type="password"
                        name="password"
                        id="reg-password"
                        class="mt-1 w-full border border-gray-300 rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-sky-400 @error('password') border-red-400 @enderror"
                        placeholder="Create a password">
                    @error('password')<span class="field-error">{{ $message }}</span>@enderror

                    <!-- Live strength meter -->
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

                <!-- Confirm -->
                <div class="mt-3">

                    <label class="text-sm font-medium">
                        Confirm Password
                    </label>

                    <input
                        type="password"
                        name="password_confirmation"
                        class="mt-1 w-full border border-gray-300 rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-sky-400">
                    @error('password_confirmation')<span class="field-error">{{ $message }}</span>@enderror

                </div>

                <!-- Terms -->
                <div class="mt-3 flex items-center gap-2 text-[10px]">

                    <input
                        type="checkbox"
                        class="accent-sky-500"
                        required>

                    <span>
                        I agree to the
                        <a href="#" class="text-sky-500">Terms of Service</a>
                        and
                        <a href="#" class="text-sky-500">Privacy Policy</a>
                    </span>

                </div>

                <!-- Register -->
                <button
                    class="mt-3 w-full bg-sky-500 hover:bg-sky-600 text-white py-2 rounded font-semibold transition">

                    Sign Up

                </button>

            </form>

            <!-- Divider -->
            <div class="flex items-center my-4">

                <div class="flex-1 border-b border-gray-300"></div>

                <span class="mx-3 text-xs text-gray-500">
                    or continue with
                </span>

                <div class="flex-1 border-b border-gray-300"></div>

            </div>

            <!-- Google -->
            <div class="flex justify-center">

                <form method="GET" action="{{ route('google.redirect') }}">
                    <button
                        type="submit"
                        class="w-12 h-12 border rounded-full flex items-center justify-center hover:bg-gray-100">

                        <img
                            src="https://developers.google.com/identity/images/g-logo.png"
                            class="w-7 h-7"
                            alt="Google">

                    </button>
                </form>

            </div>

            <p class="text-center text-[11px] mt-4">

                Already have an account?

                <a
                    href="{{ route('login') }}"
                    class="text-sky-500 font-semibold">

                    Login

                </a>

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
