{{--
    ERP MODULE: Customer — Account
    DESCRIPTION: User account/profile page (dummy). Placeholder until auth is built.
    TODO (Backend): Replace with Auth::user() data and account management.
--}}
@extends('layouts.app')

@section('content')

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 text-center">
    <h1 class="text-2xl font-bold text-gray-900">My Account</h1>
    <p class="text-gray-400 mt-2">Account page coming soon.</p>
    <a href="{{ route('tracking') }}" class="inline-block mt-6 text-sm font-medium text-cyan-500 hover:text-cyan-600">&larr; Back to Tracking</a>
</div>

@endsection
