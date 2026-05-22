@extends('layouts.app')

@section('content')
    <div class="bg-white rounded-xl shadow p-5">

        {{-- HEADER --}}
        <div class="flex justify-between items-center mb-4">

            <div>
                <h3 class="text-sm font-semibold text-gray-700">
                    Driver List
                </h3>

                <p class="text-xs text-gray-400 mt-1">
                    Manage all registered drivers.
                </p>
            </div>

            <a href="{{ route('drivers.create') }}"
                class="flex items-center gap-2 px-4 py-2 rounded-lg bg-primary text-white text-sm hover:opacity-90 transition">

                <i class="fa-solid fa-plus text-xs"></i>

                Create Driver

            </a>

        </div>

        {{-- TABLE --}}
        <div x-data="driverTable()" x-init="loadData()">

            <div class="overflow-x-auto">

                <table class="w-full min-w-[850px] text-sm">

                    {{-- HEAD --}}
                    <thead>

                        <tr class="text-left text-xs uppercase tracking-wide text-gray-400 border-b">

                            <th class="py-3">
                                Driver
                            </th>

                            <th>
                                Phone
                            </th>

                            <th>
                                Email
                            </th>

                            <th>
                                Documents
                            </th>

                            <th>
                                Status
                            </th>

                            <th class="text-center">
                                Action
                            </th>

                        </tr>

                    </thead>

                    {{-- BODY --}}
                    <tbody class="divide-y">

                        <template x-for="driver in drivers" :key="driver.id">

                            <tr class="hover:bg-gray-50 transition">

                                {{-- DRIVER --}}
                                <td class="py-4">

                                    <div class="flex items-center gap-3">

                                        <div class="w-9 h-9 rounded-full bg-primary/10 text-primary flex items-center justify-center font-semibold"
                                            x-text="driver.user.name.charAt(0).toUpperCase()">
                                        </div>

                                        <div>

                                            <p class="font-medium text-gray-800" x-text="driver.user.name">
                                            </p>

                                            <p class="text-xs text-gray-400" x-text="'ID: ' + driver.id">
                                            </p>

                                        </div>

                                    </div>

                                </td>

                                {{-- PHONE --}}
                                <td class="text-gray-600" x-text="driver.phone">
                                </td>

                                {{-- EMAIL --}}
                                <td class="text-gray-600" x-text="driver.user.email">
                                </td>

                                {{-- DOCUMENT --}}
                                <td>

                                    <span class="px-3 py-1 text-xs rounded-full bg-gray-100 text-gray-600 font-medium"
                                        x-text="driver.documents.length + ' file'">
                                    </span>

                                </td>

                                {{-- STATUS --}}
                                <td>

                                    <div class="flex flex-col gap-1">

                                        <span class="w-fit px-3 py-1 text-xs rounded-full font-medium"
                                            :class="{
                                                'bg-green-100 text-green-600': driver.status === 'online',
                                                'bg-yellow-100 text-yellow-600': driver.status === 'busy',
                                                'bg-gray-100 text-gray-600': driver.status === 'offline'
                                            }"
                                            x-text="driver.status">
                                        </span>

                                        <span
                                            class="w-fit px-3 py-1 text-xs rounded-full bg-blue-100 text-blue-600 font-medium">
                                            verified
                                        </span>

                                    </div>

                                </td>

                                {{-- ACTION --}}
                                <td>

                                    <div class="flex justify-center items-center gap-2">

                                        {{-- SHOW --}}
                                        <a :href="'/admin/drivers/' + driver.id"
                                            class="flex items-center gap-1 text-xs px-3 py-1.5 rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-100 transition">

                                            <i class="fa-solid fa-eye text-[11px]"></i>

                                            Detail

                                        </a>

                                        {{-- EDIT --}}
                                        <a :href="'/admin/drivers/' + driver.id + '/edit'"
                                            class="flex items-center gap-1 text-xs px-3 py-1.5 rounded-lg bg-yellow-50 text-yellow-600 hover:bg-yellow-100 transition">

                                            <i class="fa-solid fa-pen text-[11px]"></i>

                                            Edit

                                        </a>

                                        {{-- DELETE --}}
                                        <form :action="'/admin/drivers/' + driver.id" method="POST"
                                            onsubmit="return confirm('Delete this driver?')">

                                            @csrf
                                            @method('DELETE')

                                            <button type="submit"
                                                class="flex items-center gap-1 text-xs px-3 py-1.5 rounded-lg bg-red-50 text-red-600 hover:bg-red-100 transition">

                                                <i class="fa-solid fa-trash text-[11px]"></i>

                                                Delete

                                            </button>

                                        </form>

                                    </div>

                                </td>

                            </tr>

                        </template>

                        {{-- EMPTY --}}
                        <template x-if="drivers.length === 0">

                            <tr>

                                <td colspan="6" class="text-center py-10 text-gray-400">

                                    Belum ada driver

                                </td>

                            </tr>

                        </template>

                    </tbody>

                </table>

            </div>

            {{-- PAGINATION --}}
            <div class="flex justify-center mt-6">

                <nav class="inline-flex items-center gap-1 bg-white border rounded-lg shadow-sm px-2 py-2">

                    <template x-for="link in links" :key="link.label">

                        <button @click="link.url && loadData(link.url)" x-html="link.label" :disabled="!link.url"
                            class="min-w-[36px] h-9 flex items-center justify-center text-sm rounded-md transition"
                            :class="{
                                'bg-primary text-white font-semibold shadow': link.active,
                                'text-gray-600 hover:bg-gray-100': !link.active && link.url,
                                'text-gray-300 cursor-not-allowed': !link.url
                            }">
                        </button>

                    </template>

                </nav>

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

                        if (url && url.startsWith('http://')) {

                            url = url.replace(
                                'http://',
                                'https://'
                            );
                        }

                        let res = await fetch(url, {

                            headers: {
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        });

                        let data = await res.json();

                        this.drivers = data.data;

                        this.links = data.links;
                    }
                }
            }
        </script>
    @endpush
@endsection
