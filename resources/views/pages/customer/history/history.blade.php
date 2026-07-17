@extends('layouts.customer')

@section('content')

<div class="max-w-5xl mx-auto">

    @include('pages.customer.history.components.history-header')
    @include('pages.customer.history.components.history-list')
    @include('pages.customer.history.components.history-scripts')

</div>

@endsection
