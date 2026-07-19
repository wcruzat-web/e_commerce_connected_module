{{-- AGNER — forgot-password page: copied from _new_auth --}}
@extends('layouts.guest')

@section('title', 'Forgot Password — ShopEase')

@section('content')
<div class="min-h-screen bg-[#f5f5f5] flex items-center justify-center p-6" style="font-family: 'Outfit', sans-serif;">
    <div class="w-full max-w-md">

        <div class="bg-white rounded-2xl p-10 shadow-[0_0_45px_rgba(0,187,255,0.25)] text-center">
            <h1 class="text-lg font-extrabold tracking-wide text-gray-900 uppercase">Forgot Password</h1>
            <p class="text-xs text-gray-500 mt-2 leading-relaxed">
                Enter your registered email address below, and we'll send you a secure link to reset your password.
            </p>

            <form method="POST" action="#" class="mt-6 text-left">
                @csrf

                <label class="block text-xs font-semibold text-gray-700 mb-1.5">Email address</label>
                <input type="email" name="email" value="{{ old('email') }}" placeholder="Enter your email address"
                    class="w-full px-4 py-2.5 text-sm rounded-lg border border-gray-300 text-gray-700 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#00BBFF] focus:border-transparent">

                <button type="submit"
                    class="w-full mt-5 bg-[#00BBFF] hover:bg-[#00a6e0] transition-colors text-white text-sm font-semibold py-3 rounded-lg">
                    Send Reset Link
                </button>
            </form>

            <a href="{{ route('login') }}"
               class="inline-flex items-center gap-2 text-sm font-medium text-[#00BBFF] hover:underline mt-5">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="19" y1="12" x2="5" y2="12"></line>
                    <polyline points="12 19 5 12 12 5"></polyline>
                </svg>
                Back To Login
            </a>
        </div>

    </div>
</div>

@endsection
