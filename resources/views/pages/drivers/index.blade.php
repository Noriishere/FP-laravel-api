@extends('layouts.app')

@section('content')
    <div class="bg-white rounded-xl shadow p-5">

        {{-- HEADER --}}
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-sm font-semibold text-gray-700">
                Driver List
            </h3>
        </div>

        {{-- TABLE --}}
        <div x-data="driverTable()" x-init="loadData()">

            {{-- TABLE --}}
            <div class="overflow-x-auto">
                <table class="w-full min-w-[750px] text-sm">

                    {{-- HEAD --}}
                    <thead>
                        <tr class="text-left text-xs uppercase tracking-wide text-gray-400 border-b">
                            <th class="py-3">Driver</th>
                            <th>Email</th>
                            <th>Dokumen</th>
                            <th>Status</th>
                            <th class="text-center">Aksi</th>
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
                                            <p class="font-medium text-gray-800" x-text="driver.user.name"></p>
                                            <p class="text-xs text-gray-400" x-text="'ID: ' + driver.id"></p>
                                        </div>

                                    </div>
                                </td>

                                {{-- EMAIL --}}
                                <td class="text-gray-600" x-text="driver.user.email"></td>

                                {{-- DOCUMENT --}}
                                <td>
                                    <span class="px-3 py-1 text-xs rounded-full bg-gray-100 text-gray-600 font-medium"
                                        x-text="driver.documents_count + ' file'">
                                    </span>
                                </td>

                                {{-- STATUS --}}
                                <td>
                                    <span class="px-3 py-1 text-xs rounded-full font-medium"
                                        :class="{
                                            'bg-green-100 text-green-600': driver.verification_status === 'approved',
                                            'bg-yellow-100 text-yellow-600': driver.verification_status === 'pending',
                                            'bg-red-100 text-red-600': driver.verification_status === 'rejected'
                                        }"
                                        x-text="driver.verification_status">
                                    </span>
                                </td>

                                {{-- ACTION --}}
                                <td>
                                    <div class="flex justify-center">
                                        <a :href="'/admin/driver-documents?driver_id=' + driver.id"
                                            class="flex items-center gap-1 text-xs px-3 py-1.5 rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-100 transition">
                                            <i class="fa-solid fa-file-lines text-[11px]"></i>
                                            Detail
                                        </a>
                                    </div>
                                </td>

                            </tr>
                        </template>

                        {{-- EMPTY STATE --}}
                        <template x-if="drivers.length === 0">
                            <tr>
                                <td colspan="5" class="text-center py-10 text-gray-400">
                                    Belum ada driver
                                </td>
                            </tr>
                        </template>

                    </tbody>

                </table>
            </div>

            {{-- PAGINATION NUMBER --}}
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
                        }
                    }
                }
            </script>
        @endpush
    @endsection
