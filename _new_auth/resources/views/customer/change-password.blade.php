@extends('layouts.blanks')

@section('title', 'Change Password')

@section('content')

<div class="min-h-[80vh] flex items-center justify-center">

    @if($isGoogle ?? false)

        {{-- Google-authenticated users have no local password to change. --}}
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

    @else

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

    @endif

</div>

@endsection
