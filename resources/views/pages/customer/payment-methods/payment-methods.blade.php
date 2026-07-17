@extends('layouts.customer')

@section('content')

<div>

@include('pages.customer.payment-methods.components.payment-methods-header')

<div class="bg-white rounded-xl shadow mt-8 p-5">

@include('pages.customer.payment-methods.components.payment-methods-list')

</div>

@include('pages.customer.payment-methods.components.payment-methods-scripts')

</div>

@endsection
