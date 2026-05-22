{{--
    SIDEBAR COMPONENT
    Dipakai di layout: @include('components.sidebar')
    
    Desktop : bisa collapse jadi icon-only (64px) via tombol ☰ di header sidebar
    Mobile  : off-canvas, dibuka via tombol hamburger di navbar (lihat navbar.blade.php)
    
    State collapse disimpan di localStorage agar persist setelah refresh.
    Alpine.js state di-share ke parent lewat $store('sidebar').
--}}

<aside id="sidebar"
    :class="{
        'w-16': $store.sidebar.collapsed && !isMobile(),
        'w-64': !$store.sidebar.collapsed || isMobile(),
        '-translate-x-full': isMobile() && !$store.sidebar.mobileOpen,
        'translate-x-0': !isMobile() || $store.sidebar.mobileOpen
    }"
    class="fixed md:relative z-50 flex flex-col h-full bg-white border-r
           transition-all duration-300 ease-in-out overflow-hidden flex-shrink-0">

    {{-- ── BRAND HEADER ── --}}
    <div class="flex items-center justify-between border-b flex-shrink-0"
        :class="$store.sidebar.collapsed && !isMobile() ? 'px-0 py-4 justify-center' : 'px-5 py-4'">

        {{-- Logo / Brand --}}
        <a href="{{ url('/admin/dashboard') }}" class="flex items-center gap-2 overflow-hidden"
            :class="$store.sidebar.collapsed && !isMobile() ? 'hidden' : ''">
            <div class="w-7 h-7 bg-primary rounded-md flex items-center justify-center flex-shrink-0">
                <i class="fa-solid fa-shuttle-van text-white text-xs"></i>
            </div>
            <h2 class="text-base font-bold text-primary whitespace-nowrap">Admin <i>Gassin!</i></h2>
        </a>

        {{-- Collapsed brand icon (desktop only) --}}
        <div x-show="$store.sidebar.collapsed && !isMobile()"
            class="w-7 h-7 bg-primary rounded-md flex items-center justify-center mx-auto">
            <i class="fa-solid fa-shuttle-van text-white text-xs"></i>
        </div>

        {{-- Desktop toggle button --}}
        <button @click="$store.sidebar.toggle()"
            class="hidden md:flex items-center justify-center w-8 h-8 rounded-md
                       hover:bg-gray-100 text-gray-400 hover:text-gray-700 transition flex-shrink-0"
            :class="$store.sidebar.collapsed ? 'mx-auto' : ''" title="Toggle sidebar">
            <i class="fa-solid text-sm" :class="$store.sidebar.collapsed ? 'fa-angles-right' : 'fa-angles-left'"></i>
        </button>
    </div>

    {{-- ── NAVIGATION ── --}}
    <nav class="flex-1 py-4 space-y-0.5 text-sm overflow-y-auto overflow-x-hidden"
        :class="$store.sidebar.collapsed && !isMobile() ? 'px-2' : 'px-3'">

        {{-- NAV SECTION LABEL --}}
        <p class="px-3 pb-1 text-[10px] font-semibold text-gray-400 uppercase tracking-widest transition-all"
            :class="$store.sidebar.collapsed && !isMobile() ? 'opacity-0 h-0 overflow-hidden' : 'opacity-100'">
            Menu
        </p>

        {{-- DASHBOARD --}}
        <x-sidebar-link href="{{ url('/admin/dashboard') }}" icon="fa-chart-line" label="Dashboard" :active="request()->is('admin/dashboard')" />

        {{-- USERS --}}
        <x-sidebar-link href="{{ url('/admin/users') }}" icon="fa-users" label="Users" :active="request()->is('admin/users*')" />

        {{-- DRIVERS --}}
        <x-sidebar-link href="{{ url('/admin/drivers') }}" icon="fa-user-tie" label="Drivers" :active="request()->is('admin/drivers*')" />

        {{-- VEHICLES --}}
        <x-sidebar-link href="{{ url('/admin/vehicles') }}" icon="fa-bus" label="Vehicles" :active="request()->is('admin/vehicles*')" />

        {{-- ROUTES --}}
        <x-sidebar-link href="{{ url('/admin/routes') }}" icon="fa-map-marker-alt" label="Routes" :active="request()->is('admin/routes*')" />

        {{-- SCHEDULES --}}
        <x-sidebar-link href="{{ url('/admin/schedules') }}" icon="fa-calendar" label="Schedules" :active="request()->is('admin/schedules*')" />

        {{-- BOOKINGS --}}
        <x-sidebar-link href="{{ url('/admin/bookings') }}" icon="fa-ticket" label="Bookings" :active="request()->is('admin/bookings*')" />

        {{-- TRIP MONITORING --}}
        <x-sidebar-link href="{{ url('/admin/trip-monitoring') }}" icon="fa-location-arrow" label="Trip Monitoring"
            :active="request()->is('admin/trip-monitoring*')" />
    </nav>

    {{-- ── LOGOUT ── --}}
    <div class="border-t flex-shrink-0" :class="$store.sidebar.collapsed && !isMobile() ? 'p-2' : 'p-3'">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button
                class="w-full flex items-center rounded-md text-sm text-red-500
                           hover:bg-red-50 hover:text-red-700 transition-colors group relative"
                :class="$store.sidebar.collapsed && !isMobile() ?
                    'justify-center px-0 py-2.5' :
                    'gap-3 px-3 py-2'">
                <i class="fa-solid fa-right-from-bracket w-4 text-center flex-shrink-0"></i>
                <span :class="$store.sidebar.collapsed && !isMobile() ? 'hidden' : ''"
                    class="whitespace-nowrap">Logout</span>

                {{-- Tooltip when collapsed --}}
                <span x-show="$store.sidebar.collapsed && !isMobile()"
                    class="absolute left-full ml-3 px-2 py-1 text-xs bg-gray-900 text-white rounded-md
                             whitespace-nowrap opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none z-50">
                    Logout
                </span>
            </button>
        </form>
    </div>

</aside>
