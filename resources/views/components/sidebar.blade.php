<aside class="w-64 bg-white border-r hidden md:flex flex-col">

    <div class="px-6 py-4 border-b">
        <h2 class="text-lg font-bold text-primary">
            Admin <i>Gassin!</i>
        </h2>
    </div>

    <nav class="flex-1 px-4 py-6 space-y-2 text-sm">

        <a href="/admin/dashboard"
           class="block px-4 py-2 rounded-md {{ request()->is('admin/dashboard') ? 'bg-primary text-white' : 'hover:bg-gray-100' }}">
            Dashboard
        </a>

        <a href="/admin/users"
           class="block px-4 py-2 rounded-md {{ request()->is('admin/users') ? 'bg-primary text-white' : 'hover:bg-gray-100' }}">
            Users
        </a>

        <a href="/admin/drivers"
           class="block px-4 py-2 rounded-md {{ request()->is('admin/drivers') ? 'bg-primary text-white' : 'hover:bg-gray-100' }}">
            Drivers
        </a>

        <a href="/admin/vehicles"
           class="block px-4 py-2 rounded-md {{ request()->is('admin/vehicles') ? 'bg-primary text-white' : 'hover:bg-gray-100' }}">
            Vehicles
        </a>

        <a href="/admin/schedules"
           class="block px-4 py-2 rounded-md {{ request()->is('admin/schedules') ? 'bg-primary text-white' : 'hover:bg-gray-100' }}">
            Schedules
        </a>

        <a href="/admin/bookings"
           class="block px-4 py-2 rounded-md {{ request()->is('admin/bookings') ? 'bg-primary text-white' : 'hover:bg-gray-100' }}">
            Bookings
        </a>

    </nav>

    <div class="p-4 border-t">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button class="w-full text-left text-sm text-red-600 hover:underline">
                Logout
            </button>
        </form>
    </div>

</aside>