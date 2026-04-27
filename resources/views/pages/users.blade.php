@extends('layouts.app')
@section('content')
<div class="bg-white rounded-xl shadow p-5">

    {{-- HEADER --}}
    <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-3 mb-4">
        <h3 class="text-sm font-semibold text-gray-700">Users</h3>

        <a href="{{ route('users.create') }}" 
           class="bg-blue-600 text-white px-3 py-2 rounded-lg text-sm w-full md:w-auto text-center">
            + Tambah User
        </a>
    </div>

    {{-- FILTER --}}
    <form method="GET" class="mb-4 flex flex-col md:flex-row gap-2">
        <input name="search" value="{{ request('search') }}" placeholder="Search..."
            class="border rounded-lg px-3 py-2 text-sm w-full">

        <select name="role" class="border rounded-lg px-3 py-2 text-sm w-full md:w-40">
            <option value="">All</option>
            <option value="admin" {{ request('role')=='admin'?'selected':'' }}>Admin</option>
            <option value="driver" {{ request('role')=='driver'?'selected':'' }}>Driver</option>
            <option value="user" {{ request('role')=='customer'?'selected':'' }}>User</option>
        </select>

        <button class="bg-gray-800 text-white px-3 py-2 rounded-lg text-sm w-full md:w-auto">
            Filter
        </button>
    </form>

    {{-- TABLE --}}
    <div class="overflow-x-auto">
        <table class="w-full text-sm min-w-[600px]">
            <thead>
                <tr class="text-left text-gray-500 border-b">
                    <th class="py-2">Nama</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>

            <tbody>
                @forelse ($users as $user)
                <tr class="border-b">
                    <td class="py-2">{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>

                    <td>
                        <span class="text-xs px-2 py-1 rounded 
                            {{ $user->role=='admin' ? 'bg-blue-100 text-blue-600' : 
                               ($user->role=='driver' ? 'bg-yellow-100 text-yellow-600' : 'bg-gray-100 text-gray-600') }}">
                            {{ $user->role }}
                        </span>
                    </td>

                    {{-- ACTION --}}
                    <td class="py-2">
                        <div class="flex items-center justify-center gap-3">

                            {{-- EDIT --}}
                            <a href="{{ route('users.edit', $user->id) }}" 
                               class="text-blue-600 hover:text-blue-800">
                                <i class="fa-solid fa-pen-to-square"></i>
                            </a>

                            {{-- DELETE --}}
                            <form action="{{ route('users.destroy', $user->id) }}" method="POST">
                                @csrf
                                @method('DELETE')

                                <button onclick="return confirm('Yakin hapus user ini?')" 
                                        class="text-red-500 hover:text-red-700">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </form>

                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="text-center py-4 text-gray-400">
                        Tidak ada data user
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- PAGINATION --}}
    <div class="mt-4">
        {{ $users->links() }}
    </div>

</div>
@endsection