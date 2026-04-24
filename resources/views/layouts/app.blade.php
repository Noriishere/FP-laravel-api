<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name', 'Shuttle System') }}</title>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <script src="https://cdn.tailwindcss.com"></script>

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    },
                    colors: {
                        primary: '#C00707',
                    }
                }
            }
        }
    </script>
</head>

<body class="bg-gray-100 font-sans">

<div class="flex h-screen overflow-hidden">

    <!-- SIDEBAR -->
    <aside class="w-64 bg-white border-r hidden md:flex flex-col">

        <div class="px-6 py-4 border-b">
            <h2 class="text-lg font-bold text-primary">
                Shuttle Admin
            </h2>
        </div>

        <nav class="flex-1 px-4 py-6 space-y-2 text-sm">

            <a href="/dashboard" class="block px-4 py-2 rounded-md bg-primary text-white">
                Dashboard
            </a>

            <a href="#" class="block px-4 py-2 rounded-md hover:bg-gray-100">
                Users
            </a>

            <a href="#" class="block px-4 py-2 rounded-md hover:bg-gray-100">
                Drivers
            </a>

            <a href="#" class="block px-4 py-2 rounded-md hover:bg-gray-100">
                Vehicles
            </a>

            <a href="#" class="block px-4 py-2 rounded-md hover:bg-gray-100">
                Schedules
            </a>

            <a href="#" class="block px-4 py-2 rounded-md hover:bg-gray-100">
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

    <!-- MAIN CONTENT -->
    <div class="flex-1 flex flex-col">

        <!-- NAVBAR -->
        <header class="bg-white border-b px-6 py-4 flex items-center justify-between">

            <h1 class="text-lg font-semibold text-gray-800">
                {{ $title ?? 'Dashboard' }}
            </h1>

            <div class="text-sm text-gray-600">
                {{ auth()->user()->name ?? 'Admin' }}
            </div>

        </header>

        <!-- CONTENT -->
        <main class="flex-1 p-6 overflow-y-auto">
            @yield('content')
        </main>

    </div>

</div>

</body>
</html>