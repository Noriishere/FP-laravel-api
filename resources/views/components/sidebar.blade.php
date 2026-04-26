<aside class="w-64 bg-white border-r hidden md:flex flex-col">

    {{-- HEADER --}}
    <div class="px-6 py-4 border-b">
        <h2 class="text-lg font-bold text-primary">
            Admin <i>Gassin!</i>
        </h2>
    </div>

    <nav class="flex-1 px-3 py-6 space-y-1 text-sm">

        {{-- DASHBOARD --}}
        <a href="{{ url('/admin/dashboard') }}"
           class="flex items-center gap-2 px-4 py-2 rounded-md transition
           {{ request()->is('admin/dashboard') ? 'bg-primary text-white' : 'hover:bg-gray-100' }}">
            <i class="fa-solid fa-chart-line w-4"></i>
            Dashboard
        </a>

        {{-- USERS --}}
        <a href="{{ url('/admin/users') }}"
           class="flex items-center gap-2 px-4 py-2 rounded-md transition
           {{ request()->is('admin/users*') ? 'bg-primary text-white' : 'hover:bg-gray-100' }}">
            <i class="fa-solid fa-users w-4"></i>
            Users
        </a>

        {{-- DRIVERS --}}
        <div>

            <button onclick="toggleDriversMenu(this)" 
                class="w-full flex items-center justify-between px-4 py-2 rounded-md transition
                {{ request()->is('admin/drivers*') || request()->is('admin/driver-documents*') ? 'bg-primary text-white' : 'hover:bg-gray-100' }}">

                <span class="flex items-center gap-2">
                    <i class="fa-solid fa-id-card w-4"></i>
                    Drivers
                </span>

                <i class="fa-solid fa-chevron-down text-xs transition-transform duration-200 toggle-icon"></i>
            </button>

            {{-- SUBMENU --}}
            <div class="driversMenu ml-6 mt-1 space-y-1
                {{ request()->is('admin/drivers*') || request()->is('admin/driver-documents*') ? '' : 'hidden' }}">

                <a href="{{ url('/admin/drivers') }}"
                   class="block px-3 py-2 rounded-md text-sm transition
                   {{ request()->is('admin/drivers*') ? 'bg-gray-200' : 'hover:bg-gray-100' }}">
                    Driver List
                </a>

                <a href="{{ url('/admin/driver-documents') }}"
                   class="block px-3 py-2 rounded-md text-sm transition
                   {{ request()->is('admin/driver-documents*') ? 'bg-gray-200' : 'hover:bg-gray-100' }}">
                    Driver Documents
                </a>

            </div>

        </div>

        {{-- VEHICLES --}}
        <a href="{{ url('/admin/vehicles') }}"
           class="flex items-center gap-2 px-4 py-2 rounded-md transition
           {{ request()->is('admin/vehicles*') ? 'bg-primary text-white' : 'hover:bg-gray-100' }}">
            <i class="fa-solid fa-bus w-4"></i>
            Vehicles
        </a>

        {{-- SCHEDULES --}}
        <a href="{{ url('/admin/schedules') }}"
           class="flex items-center gap-2 px-4 py-2 rounded-md transition
           {{ request()->is('admin/schedules*') ? 'bg-primary text-white' : 'hover:bg-gray-100' }}">
            <i class="fa-solid fa-calendar w-4"></i>
            Schedules
        </a>

        {{-- BOOKINGS --}}
        <a href="{{ url('/admin/bookings') }}"
           class="flex items-center gap-2 px-4 py-2 rounded-md transition
           {{ request()->is('admin/bookings*') ? 'bg-primary text-white' : 'hover:bg-gray-100' }}">
            <i class="fa-solid fa-ticket w-4"></i>
            Bookings
        </a>

    </nav>

    {{-- LOGOUT --}}
    <div class="p-4 border-t">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button class="flex items-center gap-2 text-sm text-red-600 hover:underline">
                <i class="fa-solid fa-right-from-bracket"></i>
                Logout
            </button>
        </form>
    </div>

</aside>