<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'Shuttle System' }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif']
                    },
                    colors: {
                        primary: '#C00707'
                    }
                }
            }
        }
    </script>
    <style>
        [x-cloak] {
            display: none !important;
        }

        body {
            visibility: hidden;
        }

        .loaded {
            visibility: visible;
        }
    </style>
    <script>
        window.addEventListener('load', () => {
            document.body.classList.add('loaded');
        });
    </script>
</head>

<body class="bg-gray-100 font-sans">

    {{-- Alpine Store: shared sidebar state --}}
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.store('sidebar', {
                collapsed: localStorage.getItem('sidebarCollapsed') === 'true',
                mobileOpen: false,
                toggle() {
                    this.collapsed = !this.collapsed;
                    localStorage.setItem('sidebarCollapsed', this.collapsed);
                },
                toggleMobile() {
                    this.mobileOpen = !this.mobileOpen;
                },
                closeMobile() {
                    this.mobileOpen = false;
                }
            });
        });

        function isMobile() {
            return window.innerWidth < 768;
        }
    </script>

    <div class="flex h-screen overflow-hidden" x-data>

        {{-- MOBILE OVERLAY --}}
        <div x-show="$store.sidebar.mobileOpen" @click="$store.sidebar.closeMobile()"
            x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-150"
            x-transition:leave-end="opacity-0" class="fixed inset-0 bg-black/40 z-40 md:hidden">
        </div>

        {{-- SIDEBAR --}}
        @include('components.sidebar')

        {{-- MAIN --}}
        <div class="flex-1 flex flex-col min-w-0">
            @include('components.navbar', ['title' => $title ?? 'Dashboard'])
            <main class="flex-1 p-6 overflow-y-auto">
                @yield('content')
            </main>
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    @stack('scripts')
</body>

</html>
