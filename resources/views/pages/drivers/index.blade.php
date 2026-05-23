@extends('layouts.app')

@section('content')
    <div class="space-y-5">

        {{-- STATS --}}
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">

            <div class="bg-white rounded-xl shadow p-5">

                <p class="text-sm text-gray-500">
                    Total Drivers
                </p>

                <h2 class="text-2xl font-bold mt-2">
                    {{ \App\Models\Driver::count() }}
                </h2>

            </div>

            <div class="bg-white rounded-xl shadow p-5">

                <p class="text-sm text-gray-500">
                    Online
                </p>

                <h2 class="text-2xl font-bold mt-2 text-green-600">
                    {{ \App\Models\Driver::where('status', 'online')->count() }}
                </h2>

            </div>

            <div class="bg-white rounded-xl shadow p-5">

                <p class="text-sm text-gray-500">
                    Busy
                </p>

                <h2 class="text-2xl font-bold mt-2 text-yellow-500">
                    {{ \App\Models\Driver::where('status', 'busy')->count() }}
                </h2>

            </div>

            <div class="bg-white rounded-xl shadow p-5">

                <p class="text-sm text-gray-500">
                    Offline
                </p>

                <h2 class="text-2xl font-bold mt-2 text-gray-700">
                    {{ \App\Models\Driver::where('status', 'offline')->count() }}
                </h2>

            </div>

        </div>

        {{-- MAIN CARD --}}
        <div class="bg-white rounded-2xl shadow">

            {{-- HEADER --}}
            <div class="p-5 border-b">

                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">

                    <div>

                        <h2 class="text-lg font-bold text-gray-800">
                            Drivers Management
                        </h2>

                        <p class="text-sm text-gray-500 mt-1">
                            Manage all registered drivers
                        </p>

                    </div>

                    <a href="{{ route('drivers.create') }}"
                        class="bg-blue-600 text-white px-4 py-2 rounded-xl text-sm font-medium hover:bg-blue-700 transition">

                        <i class="fa-solid fa-plus mr-1"></i>

                        Create Driver

                    </a>

                </div>

            </div>

            {{-- TABLE --}}
            <div x-data="driverTable()" x-init="loadData()">

                <div class="overflow-x-auto">

                    <table class="w-full min-w-[950px]">

                        {{-- HEAD --}}
                        <thead class="bg-gray-50">

                            <tr class="text-left text-sm text-gray-500">

                                <th class="px-6 py-4">
                                    Driver
                                </th>

                                <th class="px-6 py-4">
                                    Phone
                                </th>

                                <th class="px-6 py-4">
                                    Email
                                </th>

                                <th class="px-6 py-4">
                                    Documents
                                </th>

                                <th class="px-6 py-4">
                                    Status
                                </th>

                                <th class="px-6 py-4 text-center">
                                    Action
                                </th>

                            </tr>

                        </thead>

                        {{-- BODY --}}
                        <tbody>

                            <template x-for="driver in drivers" :key="driver.id">

                                <tr class="border-t hover:bg-gray-50 transition">

                                    {{-- DRIVER --}}
                                    <td class="px-6 py-4">

                                        <div class="flex items-center gap-3">

                                            <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 font-semibold"
                                                x-text="driver.user.name.charAt(0).toUpperCase()">
                                            </div>

                                            <div>

                                                <p class="font-medium text-gray-800" x-text="driver.user.name">
                                                </p>

                                                <p class="text-xs text-gray-400" x-text="'ID: #' + driver.id">
                                                </p>

                                            </div>

                                        </div>

                                    </td>

                                    {{-- PHONE --}}
                                    <td class="px-6 py-4 text-sm text-gray-600" x-text="driver.phone">
                                    </td>

                                    {{-- EMAIL --}}
                                    <td class="px-6 py-4 text-sm text-gray-600" x-text="driver.user.email">
                                    </td>

                                    {{-- DOCUMENTS --}}
                                    <td class="px-6 py-4">

                                        <span class="text-xs font-medium px-3 py-1 rounded-full bg-gray-100 text-gray-700"
                                            x-text="driver.documents.length + ' files'">
                                        </span>

                                    </td>

                                    {{-- STATUS --}}
                                    <td class="px-6 py-4">

                                        <div class="flex flex-col gap-2">

                                            <span class="w-fit text-xs font-medium px-3 py-1 rounded-full"
                                                :class="{
                                                    'bg-green-100 text-green-600': driver.status === 'online',
                                                    'bg-yellow-100 text-yellow-700': driver.status === 'busy',
                                                    'bg-gray-100 text-gray-700': driver.status === 'offline'
                                                }"
                                                x-text="driver.status">
                                            </span>

                                            <span
                                                class="w-fit text-xs font-medium px-3 py-1 rounded-full bg-blue-100 text-blue-600">

                                                Verified

                                            </span>

                                        </div>

                                    </td>

                                    {{-- ACTION --}}
                                    <td class="px-6 py-4">

                                        <div class="flex items-center justify-center gap-3">

                                            {{-- SHOW --}}
                                            <a :href="'/admin/drivers/' + driver.id"
                                                class="w-9 h-9 rounded-lg bg-gray-100 text-gray-700 flex items-center justify-center hover:bg-gray-200 transition">

                                                <i class="fa-solid fa-eye"></i>

                                            </a>

                                            {{-- EDIT --}}
                                            <a :href="'/admin/drivers/' + driver.id + '/edit'"
                                                class="w-9 h-9 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center hover:bg-blue-100 transition">

                                                <i class="fa-solid fa-pen-to-square"></i>

                                            </a>

                                            {{-- DELETE --}}
                                            <form :action="'/admin/drivers/' + driver.id" method="POST"
                                                onsubmit="return confirm('Yakin hapus driver ini?')">

                                                @csrf
                                                @method('DELETE')

                                                <button
                                                    class="w-9 h-9 rounded-lg bg-red-50 text-red-600 flex items-center justify-center hover:bg-red-100 transition">

                                                    <i class="fa-solid fa-trash"></i>

                                                </button>

                                            </form>

                                        </div>

                                    </td>

                                </tr>

                            </template>

                            {{-- EMPTY --}}
                            <template x-if="drivers.length === 0">

                                <tr>

                                    <td colspan="6" class="py-16 text-center">

                                        <div class="flex flex-col items-center">

                                            <div
                                                class="w-16 h-16 rounded-full bg-gray-100 flex items-center justify-center mb-3">

                                                <i class="fa-solid fa-id-card text-2xl text-gray-400"></i>

                                            </div>

                                            <p class="text-gray-500 font-medium">
                                                Tidak ada data driver
                                            </p>

                                        </div>

                                    </td>

                                </tr>

                            </template>

                        </tbody>

                    </table>

                </div>

                {{-- PAGINATION --}}
                <div class="p-5 border-t">

                    <div class="flex justify-center">

                        <nav class="inline-flex items-center gap-1">

                            <template x-for="link in links" :key="link.label">

                                <button @click="link.url && loadData(link.url)" x-html="link.label" :disabled="!link.url"
                                    class="min-w-[38px] h-10 px-3 rounded-lg text-sm transition"
                                    :class="{
                                        'bg-blue-600 text-white font-semibold': link.active,
                                        'bg-white border hover:bg-gray-50 text-gray-700': !link.active && link.url,
                                        'bg-gray-100 text-gray-400 cursor-not-allowed': !link.url
                                    }">
                                </button>

                            </template>

                        </nav>

                    </div>

                </div>

            </div>

        </div>

    </div>

    @push('scripts')
        <script>
            function driverTable() {

                return {

                    drivers: [],

                    links: [],

                    async loadData(url = '/admin/drivers') {

                        let res = await fetch(url, {

                            headers: {
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        });

                        let data = await res.json();

                        this.drivers = data.data;

                        this.links = data.links;
                        console.log(data.links)
                    }
                }
            }
        </script>
    @endpush
@endsection