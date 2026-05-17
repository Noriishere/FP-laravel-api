{{--
    Blade Component: sidebar-link
    Usage: <x-sidebar-link href="..." icon="fa-chart-line" label="Dashboard" :active="true" />
--}}
@props(['href', 'icon', 'label', 'active' => false])

<a href="{{ $href }}"
   class="flex items-center rounded-md transition-colors duration-150 group relative
          {{ $active
              ? 'bg-primary text-white'
              : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900' }}"
   :class="$store.sidebar.collapsed && !isMobile()
       ? 'justify-center px-0 py-2.5'
       : 'gap-3 px-3 py-2'">

    <i class="fa-solid {{ $icon }} w-4 flex-shrink-0 text-center text-sm"></i>

    <span :class="$store.sidebar.collapsed && !isMobile() ? 'hidden' : ''"
          class="whitespace-nowrap font-medium text-sm">
        {{ $label }}
    </span>

    {{-- Tooltip (desktop collapsed only) --}}
    <span x-show="$store.sidebar.collapsed && !isMobile()"
          class="absolute left-full ml-3 px-2.5 py-1 text-xs font-medium bg-gray-900 text-white
                 rounded-md whitespace-nowrap opacity-0 group-hover:opacity-100
                 transition-opacity pointer-events-none z-50 shadow-lg">
        {{ $label }}
    </span>
</a>