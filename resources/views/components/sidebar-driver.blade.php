<aside
    id="sidebar"
    :class="{
        'w-16': $store.sidebar.collapsed && !isMobile(),
        'w-64': !$store.sidebar.collapsed || isMobile(),
        '-translate-x-full': isMobile() && !$store.sidebar.mobileOpen,
        'translate-x-0': !isMobile() || $store.sidebar.mobileOpen
    }"
    class="fixed md:relative z-50 flex flex-col h-full bg-white border-r
           transition-all duration-300 ease-in-out overflow-hidden flex-shrink-0">

    {{-- BRAND HEADER --}}
    <div class="flex items-center justify-between border-b flex-shrink-0"
         :class="$store.sidebar.collapsed && !isMobile() ? 'px-0 py-4 justify-center' : 'px-5 py-4'">

        <a href="{{ route('driver.dashboard') }}"
           class="flex items-center gap-2 overflow-hidden"
           :class="$store.sidebar.collapsed && !isMobile() ? 'hidden' : ''">

            <div class="w-7 h-7 bg-primary rounded-md flex items-center justify-center flex-shrink-0">
                <i class="fa-solid fa-shuttle-van text-white text-xs"></i>
            </div>

            <h2 class="text-base font-bold text-primary whitespace-nowrap">
                Driver <i>Gassin!</i>
            </h2>
        </a>

        {{-- COLLAPSED ICON --}}
        <div x-show="$store.sidebar.collapsed && !isMobile()"
             class="w-7 h-7 bg-primary rounded-md flex items-center justify-center mx-auto">
            <i class="fa-solid fa-shuttle-van text-white text-xs"></i>
        </div>

        {{-- TOGGLE BUTTON --}}
        <button @click="$store.sidebar.toggle()"
                class="hidden md:flex items-center justify-center w-8 h-8 rounded-md
                       hover:bg-gray-100 text-gray-400 hover:text-gray-700 transition flex-shrink-0"
                :class="$store.sidebar.collapsed ? 'mx-auto' : ''">

            <i class="fa-solid text-sm"
               :class="$store.sidebar.collapsed ? 'fa-angles-right' : 'fa-angles-left'"></i>
        </button>
    </div>

    {{-- NAVIGATION --}}
    <nav class="flex-1 py-4 space-y-0.5 text-sm overflow-y-auto overflow-x-hidden"
         :class="$store.sidebar.collapsed && !isMobile() ? 'px-2' : 'px-3'">

        {{-- LABEL --}}
        <p class="px-3 pb-1 text-[10px] font-semibold text-gray-400 uppercase tracking-widest transition-all"
           :class="$store.sidebar.collapsed && !isMobile()
                ? 'opacity-0 h-0 overflow-hidden'
                : 'opacity-100'">
            Menu
        </p>

        {{-- DASHBOARD --}}
        <a href="{{ route('driver.dashboard') }}"
           class="flex items-center rounded-md transition-colors duration-150 group relative
           {{ request()->routeIs('driver.dashboard')
                ? 'bg-primary text-white'
                : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900' }}"
           :class="$store.sidebar.collapsed && !isMobile()
                ? 'justify-center px-0 py-2.5'
                : 'gap-3 px-3 py-2'">

            <i class="fa-solid fa-chart-line w-4 text-center flex-shrink-0"></i>

            <span :class="$store.sidebar.collapsed && !isMobile() ? 'hidden' : ''"
                  class="whitespace-nowrap font-medium">
                Dashboard
            </span>

            <span x-show="$store.sidebar.collapsed && !isMobile()"
                  class="absolute left-full ml-3 px-2 py-1 text-xs bg-gray-900 text-white rounded-md
                         whitespace-nowrap opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none z-50">
                Dashboard
            </span>
        </a>

        {{-- DOCUMENTS --}}
        <a href="{{ route('driver.documents') }}"
           class="flex items-center rounded-md transition-colors duration-150 group relative
           {{ request()->routeIs('driver.documents')
                ? 'bg-primary text-white'
                : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900' }}"
           :class="$store.sidebar.collapsed && !isMobile()
                ? 'justify-center px-0 py-2.5'
                : 'gap-3 px-3 py-2'">

            <i class="fa-solid fa-id-card w-4 text-center flex-shrink-0"></i>

            <span :class="$store.sidebar.collapsed && !isMobile() ? 'hidden' : ''"
                  class="whitespace-nowrap font-medium">
                Verification Documents
            </span>

            <span x-show="$store.sidebar.collapsed && !isMobile()"
                  class="absolute left-full ml-3 px-2 py-1 text-xs bg-gray-900 text-white rounded-md
                         whitespace-nowrap opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none z-50">
                Verification Documents
            </span>
        </a>

        {{-- PROFILE --}}
        <a href="#"
           class="flex items-center rounded-md transition-colors duration-150 group relative
                  text-gray-600 hover:bg-gray-100 hover:text-gray-900"
           :class="$store.sidebar.collapsed && !isMobile()
                ? 'justify-center px-0 py-2.5'
                : 'gap-3 px-3 py-2'">

            <i class="fa-solid fa-user w-4 text-center flex-shrink-0"></i>

            <span :class="$store.sidebar.collapsed && !isMobile() ? 'hidden' : ''"
                  class="whitespace-nowrap font-medium">
                Profile
            </span>

            <span x-show="$store.sidebar.collapsed && !isMobile()"
                  class="absolute left-full ml-3 px-2 py-1 text-xs bg-gray-900 text-white rounded-md
                         whitespace-nowrap opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none z-50">
                Profile
            </span>
        </a>

    </nav>

    {{-- LOGOUT --}}
    <div class="border-t flex-shrink-0"
         :class="$store.sidebar.collapsed && !isMobile() ? 'p-2' : 'p-3'">

        <form method="POST" action="{{ route('driver.logout') }}">
            @csrf

            <button
                class="w-full flex items-center rounded-md text-sm text-red-500
                       hover:bg-red-50 hover:text-red-700 transition-colors group relative"
                :class="$store.sidebar.collapsed && !isMobile()
                    ? 'justify-center px-0 py-2.5'
                    : 'gap-3 px-3 py-2'">

                <i class="fa-solid fa-right-from-bracket w-4 text-center flex-shrink-0"></i>

                <span :class="$store.sidebar.collapsed && !isMobile() ? 'hidden' : ''"
                      class="whitespace-nowrap">
                    Logout
                </span>

                <span x-show="$store.sidebar.collapsed && !isMobile()"
                      class="absolute left-full ml-3 px-2 py-1 text-xs bg-gray-900 text-white rounded-md
                             whitespace-nowrap opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none z-50">
                    Logout
                </span>
            </button>

        </form>

    </div>

</aside>