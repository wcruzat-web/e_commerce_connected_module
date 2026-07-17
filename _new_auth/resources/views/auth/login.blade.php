@extends('layouts.app')

@section('content')

<div class="min-h-screen flex items-center justify-center bg-gray-100">

    <div class="relative w-[1180px] h-[720px] bg-white border border-gray-400">

        <!-- Logo -->
        <div class="absolute top-8 left-10">
            <h1 class="text-5xl font-bold">
                Shop<span class="text-sky-500">Ease</span>
            </h1>
        </div>

        <!-- Left Side -->
        <div class="absolute left-10 top-44 w-[360px]">

            <h1 class="text-6xl font-bold leading-tight">
                Welcome
                <span class="text-sky-500">back!
                </span>
            </h1>

            <p class="mt-10 text-lg leading-8 text-gray-700 text-justify">
                Your trusted destination for premium PC parts and components.
                Build, upgrade, and power your dream setup with quality hardware at competitive prices.
            </p>

            <button
                class="mt-28 bg-sky-500 hover:bg-sky-600 text-white px-8 py-3 rounded-md font-semibold shadow">
                Discount is waiting!
            </button>

        </div>

        <!-- Login Card -->
        <div class="absolute right-14 top-20 w-[540px] bg-white rounded-lg shadow-[0_0_35px_rgba(56,189,248,.30)] p-8 animate-pop">

            <h2 class="text-3xl font-bold">
                Login to your account
            </h2>

            <p class="text-gray-500 mt-2 mb-7">
                Enter your credentials to access your account
            </p>

            <!-- Login Form -->
            <form method="POST" action="{{ route('login.store') }}">

                @csrf

                <label class="font-medium">
                    Email
                </label>

                <input
                    type="email"
                    name="email"
                    value="{{ old('email', request()->cookie('remember_login_email', '')) }}"
                    placeholder="Enter your email"
                    autocomplete="username"
                    class="mt-2 mb-5 w-full border rounded-md px-4 py-3 focus:outline-none focus:ring-2 focus:ring-sky-400">

                <div class="flex justify-between">

                    <label class="font-medium">
                        Password
                    </label>

                    <a href="{{ route('forgot') }}"
                       class="text-sky-500 text-sm hover:underline">
                        Forgot Password?
                    </a>

                </div>

                <input
                    type="password"
                    name="password"
                    value="{{ request()->cookie('remember_login_password', '') }}"
                    placeholder="Enter your password"
                    autocomplete="current-password"
                    class="mt-2 w-full border rounded-md px-4 py-3 focus:outline-none focus:ring-2 focus:ring-sky-400">

                <div class="mt-4">

                    <label class="flex items-center gap-2">

                        <input type="checkbox" name="remember" {{ request()->cookie('remember_login_email') ? 'checked' : '' }}
                            class="w-4 h-4 rounded border-gray-300 text-sky-500 focus:ring-sky-400">

                        <span class="text-sm">
                            Keep me signed in
                        </span>

                    </label>

                </div>

                <button
                    type="submit"
                    class="mt-5 w-full bg-sky-500 hover:bg-sky-600 transition rounded-md py-3 text-white font-semibold">

                    Log In

                </button>

            </form>

            <!-- Divider -->
            <div class="flex items-center my-7">

                <div class="flex-1 border-b border-gray-300"></div>

                <span class="px-3 text-gray-500 text-sm">
                    or continue with
                </span>

                <div class="flex-1 border-b border-gray-300"></div>

            </div>

            <!-- Google -->
            <div class="flex justify-center">

                <form method="GET" action="{{ route('google.redirect') }}">
                    <button
                        type="submit"
                        class="w-14 h-14 border rounded-full flex items-center justify-center hover:bg-gray-100 transition">

                        <img
                            src="https://developers.google.com/identity/images/g-logo.png"
                            class="w-7 h-7"
                            alt="Google">

                    </button>
                </form>

            </div>

            <p class="mt-7 text-center text-sm">

                Don't have an account?

                <a href="{{ route('register') }}"
                   class="text-sky-500 font-semibold hover:underline">

                    Sign Up

                </a>

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
               class="mt-6 inline-block bg-sky-500 hover:bg-sky-600 text-white px-8 py-3 rounded-lg font-semibold transition hover:-translate-y-0.5">
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
