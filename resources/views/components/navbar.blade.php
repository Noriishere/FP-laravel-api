<header class="bg-white border-b px-6 py-4 flex items-center justify-between">

    <h1 class="text-lg font-semibold text-gray-800">
        {{ $title ?? 'Dashboard' }}
    </h1>

    <div class="flex items-center gap-4">

        <span class="text-sm text-gray-600">
            {{ auth()->user()->name ?? 'Admin' }}
        </span>

        <div class="w-8 h-8 bg-primary text-white flex items-center justify-center rounded-full text-sm font-bold">
            {{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}
        </div>

    </div>

</header>