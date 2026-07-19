{{-- CRUZAT — admin dashboard: stats, revenue chart, recent orders, low stocks --}}
{{--
    ERP MODULE: Admin Dashboard
    DESCRIPTION: Dashboard page composing sidebar, topbar, stat cards, revenue,
                 recent orders, low stocks, and scripts from components/.
    CONTROLLER: Admin\DashboardController@index (TODO)
    ROUTE: GET /admin/dashboard
--}}
@extends('layouts.admin')

@section('content')

<div class="flex min-h-screen bg-slate-50" style="font-family: 'Outfit', sans-serif;">

    @include('components.admin.sidebar')

    <div class="flex-1 min-w-0">

        @include('pages.admin.dashboard.components.topbar')
        @include('pages.admin.dashboard.components.export-toast')

        <div class="p-4 lg:p-6 space-y-6">

            {{-- Page header --}}
            <div class="flex items-center justify-between flex-wrap gap-3">
                <div>
                    <h1 class="text-xl font-bold text-gray-900">Dashboard Overview</h1>
                    <p class="text-sm text-gray-400 mt-0.5">June 2026 · Business Name</p>
                </div>
                <div class="flex items-center gap-3">
                    <button type="button" onclick="viewSyncLogs()" class="flex items-center gap-2 border border-cyan-500 text-cyan-500 text-sm font-medium px-4 py-2 rounded-lg hover:bg-cyan-50 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="23 4 23 10 17 10"></polyline>
                            <polyline points="1 20 1 14 7 14"></polyline>
                            <path d="M3.51 9a9 9 0 0 1 14.85-3.36L23 10M1 14l4.64 4.36A9 9 0 0 0 20.49 15"></path>
                        </svg>
                        Sync Logs
                    </button>
                    <button type="button" onclick="exportReport()" class="flex items-center gap-2 bg-cyan-500 hover:bg-cyan-600 transition-colors text-white text-sm font-semibold px-4 py-2 rounded-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                            <polyline points="7 10 12 15 17 10"></polyline>
                            <line x1="12" y1="15" x2="12" y2="3"></line>
                        </svg>
                        Export Report
                    </button>
                </div>
            </div>

            @include('pages.admin.dashboard.components.stat-cards')
            @include('pages.admin.dashboard.components.revenue-section')

            <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
                @include('pages.admin.dashboard.components.recent-orders')
                @include('pages.admin.dashboard.components.low-stocks')
            </div>

        </div>
    </div>
</div>

@include('pages.admin.dashboard.components.dashboard-scripts')

@endsection
