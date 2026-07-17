{{--
    ERP MODULE: Authentication — Forgot Password Page
    PAGE: ForgotPassword
    DESCRIPTION: Single-centered card to request a password reset link.
    ROUTES:
      GET  /forgot-password  — showLinkRequestForm()
      POST /forgot-password  — sendResetLinkEmail()
    ToDo: Create Auth\ForgotPasswordController
--}}

@extends('layouts.guest')

@section('content')
<div class="min-h-screen bg-[#f5f5f5] flex items-center justify-center p-6" style="font-family: 'Outfit', sans-serif;">
    <div class="w-full max-w-md">

        @include('pages.customer.auth.components.cards.forgot-password-form')

    </div>
</div>

@include('pages.customer.auth.components.auth-scripts')
@endsection
