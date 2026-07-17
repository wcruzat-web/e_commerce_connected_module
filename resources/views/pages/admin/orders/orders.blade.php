{{--
    ERP MODULE: Admin Orders
    DESCRIPTION: Full orders list page following the admin dashboard layout.
    ROUTE: GET /admin/orders
--}}
@extends('layouts.admin')

@section('content')

<div class="flex min-h-screen bg-slate-50" style="font-family: 'Outfit', sans-serif;">

    {{-- Sidebar navigation --}}
    @include('components.admin.sidebar')

    <div class="flex-1 min-w-0">

        {{-- Topbar with ERP sync status and user info --}}
        @include('pages.admin.dashboard.components.topbar')

        <div class="p-4 lg:p-6 space-y-6">

            {{-- Page header with back button and print --}}
            <div class="flex items-center justify-between flex-wrap gap-3">
                <div class="flex items-center gap-3">
                    {{-- Back to dashboard --}}
                    <a href="{{ route('admin.dashboard') }}" class="w-8 h-8 rounded-lg border border-gray-200 flex items-center justify-center text-gray-500 hover:bg-gray-50 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="15 18 9 12 15 6"></polyline>
                        </svg>
                    </a>
                    <div>
                        <h1 class="text-xl font-bold text-gray-900">Orders</h1>
                        <p class="text-sm text-gray-400 mt-0.5">Manage all orders</p>
                    </div>
                </div>
                {{-- Print button --}}
                <button type="button" onclick="window.print()" class="flex items-center gap-2 border border-gray-200 text-gray-600 text-sm font-medium px-4 py-2 rounded-lg hover:bg-gray-50 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="6 9 6 2 18 2 18 9"></polyline>
                        <path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path>
                        <rect x="6" y="14" width="12" height="8"></rect>
                    </svg>
                    Print
                </button>
            </div>

            {{-- Search and filters --}}
            @include('pages.admin.orders.components.orders-toolbar')

            <div id="ordersTableWrapper">
                @include('pages.admin.orders.components.orders-table-wrapper')
            </div>

            {{-- Order details modal --}}
            @include('pages.admin.orders.components.order-details-modal')

            {{-- Client-side filtering --}}
            @include('pages.admin.orders.components.orders-scripts')

        </div>
    </div>
</div>

@endsection

{{-- Print styles: hide everything except the orders table --}}
@push('styles')
<style>
    @media print {
        body * {
            visibility: hidden;
        }
        .orders-print-area,
        .orders-print-area * {
            visibility: visible;
        }
        .orders-print-area {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
            border: none !important;
            border-radius: 0 !important;
            box-shadow: none !important;
        }
        .orders-print-area thead th {
            background: #f8f9fb !important;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }
        .orders-print-area .status-badge {
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }
    }
</style>
@endpush
