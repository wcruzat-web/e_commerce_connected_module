@extends('layouts.customer')

@section('content')

<div class="px-7 py-6">

    @include('pages.customer.wishlist.components.wishlist-header')
    @include('pages.customer.wishlist.components.wishlist-toolbar')
    @include('pages.customer.wishlist.components.wishlist-items')
    @include('pages.customer.wishlist.components.wishlist-footer')

</div>

@endsection
