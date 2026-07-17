@extends('layouts.app')

@section('content')

<div class="min-h-screen flex items-center justify-center bg-gray-100">

    <div class="w-[1120px] h-[720px] bg-white border border-gray-300 flex items-center justify-center">

        <div class="w-[440px] bg-white rounded-md px-10 py-8 shadow-[0_0_35px_rgba(56,189,248,.35)]">

            <!-- Title -->
            <h1 class="text-3xl font-bold text-center uppercase">
                Forgot Password
            </h1>

            <p class="text-center text-sm text-gray-600 mt-2 mb-8 leading-5">
                Enter your registered email address below, and
                we'll send you a secure link to reset your password.
            </p>

            <form action="#" method="POST">
                @csrf

                <div>
                    <label class="block text-sm font-semibold mb-2">
                        Email address
                    </label>

                    <input
                        type="email"
                        name="email"
                        value="{{ old('email') }}"
                        required
                        placeholder="Enter your email address"
                        class="w-full border border-gray-300 rounded-md px-4 py-3 focus:outline-none focus:ring-2 focus:ring-sky-400">
                </div>

                <button
                    type="submit"
                    class="w-full mt-7 bg-sky-500 hover:bg-sky-600 text-white font-semibold py-3 rounded-md transition">

                    Send Reset Link

                </button>

            </form>

            <!-- Back -->
            <div class="mt-8 flex justify-center">

                <a
                    href="{{ route('login') }}"
                    class="flex items-center gap-3 text-sky-500 hover:text-sky-600 font-semibold">

                    <svg xmlns="http://www.w3.org/2000/svg"
                        class="w-8 h-8"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                        stroke-width="2.5">

                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M15.75 19.5L8.25 12l7.5-7.5"/>

                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M9 12h11"/>

                    </svg>

                    Back To Login

                </a>

            </div>

        </div>

    </div>

</div>

@endsection