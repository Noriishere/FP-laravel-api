<aside class="w-64 bg-white border-r hidden md:flex flex-col">

    <div class="px-6 py-4 border-b">
        <h2 class="text-lg font-bold text-primary">
            Driver <i>Gassin!</i>
        </h2>
    </div>

    <nav class="flex-1 px-3 py-6 space-y-1 text-sm">

        <a href="{{ route('driver.dashboard') }}"
           class="flex items-center gap-2 px-4 py-2 rounded-md transition
           {{ request()->routeIs('driver.dashboard') ? 'bg-primary text-white' : 'hover:bg-gray-100' }}">
            <i class="fa-solid fa-chart-line w-4"></i>
            Dashboard
        </a>

        <a href="{{ route('driver.documents') }}"
           class="flex items-center gap-2 px-4 py-2 rounded-md transition
           {{ request()->routeIs('driver.documents') ? 'bg-primary text-white' : 'hover:bg-gray-100' }}">
            <i class="fa-solid fa-id-card w-4"></i>
            Verification Documents
        </a>
        
        <a href="#"
           class="flex items-center gap-2 px-4 py-2 rounded-md transition hover:bg-gray-100">
            <i class="fa-solid fa-user w-4"></i>
            Profile
        </a>

    </nav>

    <div class="p-4 border-t">

        <form method="POST" action="{{ route('driver.logout') }}">
            @csrf

            <button
                class="flex items-center gap-2 text-sm text-red-600 hover:underline"
            >
                <i class="fa-solid fa-right-from-bracket"></i>
                Logout
            </button>

        </form>

    </div>

</aside>