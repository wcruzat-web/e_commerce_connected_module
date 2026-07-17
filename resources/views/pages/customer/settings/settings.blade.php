@extends('layouts.customer')

@section('content')

<div>

<h1 class="text-3xl font-bold">
Settings
</h1>

<p class="text-gray-500 mt-2">
Manage your account preferences.
</p>

<form id="settings-form" method="POST" action="{{ route('settings.update') }}">
@csrf

@include('pages.customer.settings.components.settings-preferences')
@include('pages.customer.settings.components.settings-notifications')

</form>

</div>

@endsection
