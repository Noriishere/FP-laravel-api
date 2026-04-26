@extends('layouts.app')
@section('content')
    <div class="bg-white rounded-xl shadow p-5">

        <div class="flex justify-between items-center mb-4">
            <h3 class="text-sm font-semibold text-gray-700">Users</h3>

            <a href="#" class="bg-blue-600 text-white px-3 py-2 rounded-lg text-sm">
                + Tambah User
            </a>
        </div>

        <form method="GET" class="mb-4 flex gap-2">
            <input name="search" value="{{ request('search') }}" placeholder="Search..."
                class="border rounded-lg px-3 py-2 text-sm">

            <select name="role" class="border rounded-lg px-3 py-2 text-sm">
                <option value="">All</option>
                <option value="admin">Admin</option>
                <option value="driver">Driver</option>
                <option value="user">User</option>
            </select>

            <button class="bg-gray-800 text-white px-3 py-2 rounded-lg text-sm">
                Filter
            </button>
        </form>

        <table class="w-full text-sm">
            <thead>
                <tr class="text-left text-gray-500 border-b">
                    <th>Nama</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>

            <tbody>
                @foreach ($users as $user)
                    <tr>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>

                        <td>
                            <span class="text-xs px-2 py-1 rounded bg-blue-100 text-blue-600">
                                {{ $user->role }}
                            </span>
                        </td>

                        <td>
                            <form action="{{ route('users.destroy', $user->id) }}" method="POST">
                                @csrf
                                @method('DELETE')

                                <a href="{{ route('users.edit', $user->id) }}">Edit</a>

                                <button onclick="return confirm('Yakin?')" class="text-red-500">
                                    Delete
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
                <div class="mt-4">
                    {{ $users->links() }}
                </div>
            </tbody>
        </table>

    </div>
@endsection
