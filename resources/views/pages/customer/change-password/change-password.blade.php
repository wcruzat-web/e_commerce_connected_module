@extends('layouts.blanks')

@section('title', 'Change Password')

@section('content')

<div class="flex items-center justify-center">

    @if($isGoogle ?? false)
        @include('pages.customer.change-password.components.change-password-google')
    @else
        @include('pages.customer.change-password.components.change-password-form')
    @endif

</div>

@endsection
