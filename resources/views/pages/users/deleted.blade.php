@extends('layouts.app')

@section('content')
    <div class="space-y-5">

        {{-- STATS --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

            <div class="bg-white rounded-xl shadow p-5">
                <p class="text-sm text-gray-500">
                    Deleted Accounts
                </p>

                <h2 class="text-2xl font-bold mt-2 text-red-600">
                    {{ $users->total() }}
                </h2>
            </div>

            <div class="bg-white rounded-xl shadow p-5">
                <p class="text-sm text-gray-500">
                    Deleted Drivers
                </p>

                <h2 class="text-2xl font-bold mt-2 text-yellow-500">
                    {{ \App\Models\User::onlyTrashed()->where('role', 'driver')->count() }}
                </h2>
            </div>

            <div class="bg-white rounded-xl shadow p-5">
                <p class="text-sm text-gray-500">
                    Deleted Customers
                </p>

                <h2 class="text-2xl font-bold mt-2 text-gray-700">
                    {{ \App\Models\User::onlyTrashed()->where('role', 'customer')->count() }}
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
                            Deleted Accounts
                        </h2>

                        <p class="text-sm text-gray-500 mt-1">
                            Restore or permanently delete accounts
                        </p>

                    </div>

                    <a href="{{ route('users.index') }}"
                        class="bg-blue-600 text-white px-4 py-2 rounded-xl text-sm font-medium hover:bg-blue-700 transition">

                        <i class="fa-solid fa-arrow-left mr-1"></i>

                        Back To Users

                    </a>

                </div>

            </div>

            {{-- FILTER --}}
            <div class="p-5 border-b">

                <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-3">

                    <div class="md:col-span-2">

                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="Cari nama atau email..."
                            class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-red-500">

                    </div>

                    <div>

                        <select name="role"
                            class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-red-500">

                            <option value="">
                                Semua Role
                            </option>

                            <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>
                                Admin
                            </option>

                            <option value="driver" {{ request('role') == 'driver' ? 'selected' : '' }}>
                                Driver
                            </option>

                            <option value="customer" {{ request('role') == 'customer' ? 'selected' : '' }}>
                                Customer
                            </option>

                        </select>

                    </div>

                    <div>

                        <button
                            class="w-full bg-red-600 text-white rounded-xl px-4 py-2.5 text-sm hover:bg-red-700 transition">

                            <i class="fa-solid fa-filter mr-1"></i>

                            Filter

                        </button>

                    </div>

                </form>

            </div>

            {{-- TABLE --}}
            <div class="overflow-x-auto">

                <table class="w-full min-w-[900px]">

                    <thead class="bg-gray-50">

                        <tr class="text-left text-sm text-gray-500">

                            <th class="px-6 py-4">
                                User
                            </th>

                            <th class="px-6 py-4">
                                Email
                            </th>

                            <th class="px-6 py-4">
                                Role
                            </th>

                            <th class="px-6 py-4">
                                Deleted At
                            </th>

                            <th class="px-6 py-4 text-center">
                                Action
                            </th>

                        </tr>

                    </thead>

                    <tbody>

                        @forelse ($users as $user)
                            <tr class="border-t hover:bg-gray-50 transition">

                                <td class="px-6 py-4">

                                    <div class="flex items-center gap-3">

                                        <div
                                            class="w-10 h-10 rounded-full bg-red-100 flex items-center justify-center text-red-600 font-semibold">

                                            {{ strtoupper(substr($user->name, 0, 1)) }}

                                        </div>

                                        <div>

                                            <p class="font-medium text-gray-800">
                                                {{ $user->name }}
                                            </p>

                                            <p class="text-xs text-gray-400">
                                                ID: #{{ $user->id }}
                                            </p>

                                        </div>

                                    </div>

                                </td>

                                <td class="px-6 py-4 text-sm text-gray-600">
                                    {{ $user->email }}
                                </td>

                                <td class="px-6 py-4">

                                    <span
                                        class="text-xs font-medium px-3 py-1 rounded-full

                                {{ $user->role == 'admin'
                                    ? 'bg-blue-100 text-blue-600'
                                    : ($user->role == 'driver'
                                        ? 'bg-yellow-100 text-yellow-700'
                                        : 'bg-gray-100 text-gray-700') }}">

                                        {{ ucfirst($user->role) }}

                                    </span>

                                </td>

                                <td class="px-6 py-4 text-sm text-gray-500">

                                    {{ $user->deleted_at->format('d M Y H:i') }}

                                </td>

                                <td class="px-6 py-4">

                                    <div class="flex items-center justify-center gap-3">

                                        {{-- RESTORE --}}
                                        <form action="{{ route('users.restore', $user->id) }}" method="POST">

                                            @csrf
                                            @method('PATCH')

                                            <button onclick="return confirm('Restore account ini?')"
                                                class="w-9 h-9 rounded-lg bg-green-50 text-green-600 flex items-center justify-center hover:bg-green-100 transition">

                                                <i class="fa-solid fa-rotate-left"></i>

                                            </button>

                                        </form>

                                        {{-- FORCE DELETE --}}
                                        <form action="{{ route('users.forceDelete', $user->id) }}" method="POST">

                                            @csrf
                                            @method('DELETE')

                                            <button onclick="return confirm('Hapus permanen account ini?')"
                                                class="w-9 h-9 rounded-lg bg-red-50 text-red-600 flex items-center justify-center hover:bg-red-100 transition">

                                                <i class="fa-solid fa-trash"></i>

                                            </button>

                                        </form>

                                    </div>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td colspan="5" class="py-16 text-center">

                                    <div class="flex flex-col items-center">

                                        <div
                                            class="w-16 h-16 rounded-full bg-red-100 flex items-center justify-center mb-3">

                                            <i class="fa-solid fa-trash text-2xl text-red-400"></i>

                                        </div>

                                        <p class="text-gray-500 font-medium">
                                            Tidak ada deleted account
                                        </p>

                                    </div>

                                </td>

                            </tr>
                        @endforelse

                    </tbody>

                </table>

            </div>

            {{-- PAGINATION --}}
            <div class="p-5 border-t">

                {{ $users->links() }}

            </div>

        </div>

    </div>
@endsection
