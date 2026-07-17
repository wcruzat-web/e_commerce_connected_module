@extends('layouts.customer')

@section('content')

<div>

@include('pages.customer.notifications.components.notifications-header')

<div class="bg-white rounded-xl shadow mt-8 p-5">

@php
$badges = [
'order' => ['bg' => 'bg-blue-100', 'text' => 'text-blue-600', 'label' => 'O'],
'promotion' => ['bg' => 'bg-green-100', 'text' => 'text-green-600', 'label' => 'P'],
'payment' => ['bg' => 'bg-purple-100', 'text' => 'text-purple-600', 'label' => '₱'],
'system' => ['bg' => 'bg-gray-100', 'text' => 'text-gray-600', 'label' => 'S'],
];
@endphp

@include('pages.customer.notifications.components.notifications-filters')
@include('pages.customer.notifications.components.notifications-list')

</div>

</div>

@endsection
