{{-- AGNER — profile page: saved addresses, component-split (ERPV0.2.2, ERPV0.4) --}}
@extends('layouts.customer')

@section('content')

<div class="px-7 py-6">

    <h1 class="text-4xl font-bold">
        My Profile
    </h1>

    <p class="text-gray-600 mt-1">
        Manage your personal information and account settings
    </p>

    @include('pages.customer.profile.components.profile-info-form')
    @include('pages.customer.profile.components.profile-change-password')
    @include('pages.customer.profile.components.profile-account-info')
    @include('pages.customer.profile.components.profile-addresses')
    @include('pages.customer.profile.components.profile-help')

</div>

@endsection
