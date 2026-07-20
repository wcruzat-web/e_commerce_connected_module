<header class="bg-white shadow px-4 sm:px-8 py-5 flex items-center justify-between gap-4">

    <div class="flex items-center gap-3 min-w-0">
        <!-- Mobile hamburger -->
        <button onclick="toggleSidebar()" class="lg:hidden p-2 rounded-md hover:bg-gray-100 shrink-0" aria-label="Toggle sidebar">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/>
            </svg>
        </button>

        <h1 class="text-2xl sm:text-3xl font-bold truncate">
            @yield('page_title', 'DASHBOARD')
        </h1>
    </div>

    @auth
    <div class="flex items-center gap-3 shrink-0">
        <div class="text-right hidden sm:block">
            <p class="text-sm font-semibold leading-tight">
                {{ auth()->user()->first_name }} {{ auth()->user()->last_name }}
            </p>
            <p class="text-xs text-gray-500 leading-tight truncate max-w-[180px]">
                {{ auth()->user()->email }}
            </p>
        </div>
        @if(auth()->user()->profile_picture)
            <img src="{{ Str::startsWith(auth()->user()->profile_picture, ['http://', 'https://']) ? auth()->user()->profile_picture : asset(auth()->user()->profile_picture) }}" alt=""
                class="w-10 h-10 rounded-full object-cover border shrink-0">
        @else
            <div class="w-10 h-10 rounded-full bg-sky-200 flex items-center justify-center shrink-0 font-bold text-sky-600 text-sm">
                {{ strtoupper(substr(auth()->user()->first_name, 0, 1)) }}{{ strtoupper(substr(auth()->user()->last_name, 0, 1)) }}
            </div>
        @endif
    </div>
    @endauth

</header>
