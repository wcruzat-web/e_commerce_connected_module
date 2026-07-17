{{--
    ERP MODULE: Authentication — Login Page
    PAGE: Login
    DESCRIPTION: Login page with branding panel and login form card.
    ROUTES:
      GET  /login  — showLoginForm()
      POST /login  — login()
    ToDo: Create Auth\LoginController
--}}

@extends('layouts.guest')

@section('content')
<div class="min-h-screen bg-[#f5f5f5] flex items-center justify-center p-6" style="font-family: 'Outfit', sans-serif;">
    <div class="w-full max-w-4xl grid grid-cols-1 md:grid-cols-2 gap-12 items-center">

        @include('pages.customer.auth.components.shared.auth-branding', [
            'heading' => 'Welcome',
            'highlight' => 'back!',
            'buttonText' => 'Discount is waiting!',
        ])

        @include('pages.customer.auth.components.cards.login-card')

    </div>
</div>
@include('pages.customer.auth.components.auth-scripts')
@endsection