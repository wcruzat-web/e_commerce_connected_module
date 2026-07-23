{{-- AGNER — orders page: status tabs, customer_received, track button (ERPV0.2.3, ERPV0.2.4) --}}
@extends('layouts.customer')

@section('content')

<div class="max-w-5xl mx-auto">

    @include('pages.customer.orders.components.orders-header')
    @include('pages.customer.orders.components.orders-tabs')
    @include('pages.customer.orders.components.orders-list')

</div>

@endsection
