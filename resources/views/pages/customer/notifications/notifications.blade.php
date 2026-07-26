@extends('layouts.customer')

@section('content')

<div>

@include('pages.customer.notifications.components.notifications-header')

<div class="bg-white dark:bg-gray-800 rounded-xl shadow mt-8 p-5">

@php
$badges = [
'order' => ['bg' => 'bg-blue-100 dark:bg-blue-900/40', 'text' => 'text-blue-600 dark:text-blue-400', 'label' => 'O'],
'promotion' => ['bg' => 'bg-green-100 dark:bg-green-900/40', 'text' => 'text-green-600 dark:text-green-400', 'label' => 'P'],
'payment' => ['bg' => 'bg-purple-100 dark:bg-purple-900/40', 'text' => 'text-purple-600 dark:text-purple-400', 'label' => '₱'],
'system' => ['bg' => 'bg-gray-100 dark:bg-gray-700', 'text' => 'text-gray-600 dark:text-gray-400', 'label' => 'S'],
];
@endphp

@include('pages.customer.notifications.components.notifications-filters')
@include('pages.customer.notifications.components.notifications-list')

</div>

</div>

@endsection
