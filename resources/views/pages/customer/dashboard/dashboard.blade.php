@extends('layouts.customer')

@section('content')

<div class="px-6 py-5">

    @include('pages.customer.dashboard.components.dashboard-greeting')
    @include('pages.customer.dashboard.components.dashboard-stats')

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mt-8">

        <div>
            @include('pages.customer.dashboard.components.dashboard-notifications')
        </div>

        <div>
            @include('pages.customer.dashboard.components.dashboard-payment-methods')
        </div>

    </div>

</div>

@endsection
