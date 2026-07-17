@extends('layouts.customer')

@section('content')

<div class="max-w-5xl mx-auto">

    @include('pages.customer.orders.components.orders-header')
    @include('pages.customer.orders.components.orders-tabs')
    @include('pages.customer.orders.components.orders-list')
    @include('pages.customer.orders.components.orders-scripts')

</div>

@endsection
