{{--
    ERP MODULE: Admin — Inventory
    DESCRIPTION: Inventory management page (dummy). Placeholder until backend is built.
    TODO (Backend): Replace with stock management for Product model.
--}}
@extends('layouts.admin')

@section('content')

<div class="flex min-h-screen bg-slate-50" style="font-family: 'Outfit', sans-serif;">
    @include('components.admin.sidebar')
    <div class="flex-1 min-w-0">
        @include('pages.admin.dashboard.components.topbar')
        <div class="p-6 text-center">
            <h1 class="text-2xl font-bold text-gray-900">Inventory</h1>
            <p class="text-gray-400 mt-2">Inventory management coming soon.</p>
        </div>
    </div>
</div>

@endsection
