@extends('layouts.customer')

@section('content')

<div class="px-7 py-6">

    @include('pages.customer.addresses.components.address-header')
    @include('pages.customer.addresses.components.address-list')

</div>

@endsection
