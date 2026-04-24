<header class="bg-white border-b px-6 py-4 flex items-center justify-between">

    <h1 class="text-lg font-semibold text-gray-800">
        {{ $title ?? 'Dashboard' }}
    </h1>

    <!-- PROFILE DROPDOWN -->
    <div x-data="{ open: false }" class="relative">

        <!-- BUTTON -->
        <button @click="open = !open"
                class="flex items-center gap-2 focus:outline-none">

            <span class="text-sm text-gray-700">
                {{ auth()->user()->name }}
            </span>

            <div class="w-8 h-8 bg-primary text-white flex items-center justify-center rounded-full text-sm font-bold">
                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
            </div>
        </button>

        <!-- DROPDOWN -->
        <div x-show="open"
             @click.outside="open = false"
             x-transition
             class="absolute right-0 mt-2 w-48 bg-white border rounded-md shadow-lg z-50">

            <a href="{{ route('profile.edit') }}"
               class="block px-4 py-2 text-sm hover:bg-gray-100">
                Profile
            </a>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="w-full text-left px-4 py-2 text-sm hover:bg-gray-100">
                    Logout
                </button>
            </form>

        </div>

    </div>

</header>