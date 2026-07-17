<header class="bg-white shadow px-8 py-5 flex items-center justify-between">

    <h1 class="text-3xl font-bold">
        @yield('page_title', 'DASHBOARD')
    </h1>

    @auth
    <div class="flex items-center gap-3">
        <div class="text-right">
            <p class="text-sm font-semibold leading-tight">
                {{ auth()->user()->first_name }} {{ auth()->user()->last_name }}
            </p>
            <p class="text-xs text-gray-500 leading-tight">
                {{ auth()->user()->email }}
            </p>
        </div>
        @if(auth()->user()->profile_picture)
            <img src="{{ auth()->user()->profile_picture }}" alt=""
                class="w-10 h-10 rounded-full object-cover border">
        @else
            <div class="w-10 h-10 rounded-full bg-sky-200 flex items-center justify-center">👤</div>
        @endif
    </div>
    @endauth

</header>
