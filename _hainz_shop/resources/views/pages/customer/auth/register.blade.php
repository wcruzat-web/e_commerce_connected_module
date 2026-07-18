{{--
    ERP MODULE: Authentication — Register Page
    PAGE: Register
    DESCRIPTION: Registration page with branding panel and register form card.
    ROUTES:
      GET  /register  — showRegistrationForm()
      POST /register  — register()
    ToDo: Create Auth\RegisterController
--}}

@extends('layouts.guest')

@section('content')
<div class="min-h-screen bg-[#f5f5f5] flex items-center justify-center p-6" style="font-family: 'Outfit', sans-serif;">
    <div class="w-full max-w-4xl grid grid-cols-1 md:grid-cols-2 gap-12 items-center">

        @include('pages.customer.auth.components.shared.auth-branding', [
            'heading' => 'Build your dream',
            'highlight' => 'setup with us.',
            'buttonText' => 'Hurry Now!',
        ])

        @include('pages.customer.auth.components.cards.register-card')

    </div>
</div>

@include('pages.customer.auth.components.auth-scripts')
@endsection
