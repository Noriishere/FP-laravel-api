@extends('layouts.app');
@section('content');
<div class="bg-white rounded-xl shadow p-5">

    <div class="flex justify-between items-center mb-4">
        <h3 class="text-sm font-semibold text-gray-700">Users</h3>

        <a href="#" class="bg-blue-600 text-white px-3 py-2 rounded-lg text-sm">
            + Tambah User
        </a>
    </div>

    <div class="mb-4 flex gap-2">
        <input type="text" placeholder="Search..." class="border rounded-lg px-3 py-2 w-full text-sm">
        
        <select class="border rounded-lg px-3 py-2 text-sm">
            <option>All</option>
            <option>Admin</option>
            <option>Driver</option>
            <option>User</option>
        </select>
    </div>

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
            @foreach($users as $user)
            <tr class="border-b">
                <td class="py-2">{{ $user->name }}</td>
                <td>{{ $user->email }}</td>
                <td>
                    <span class="px-2 py-1 text-xs rounded bg-blue-100 text-blue-600">
                        {{ $user->role }}
                    </span>
                </td>
                <td>
                    <span class="text-green-600 text-xs">Active</span>
                </td>
                <td class="flex gap-2">
                    <button>Detail</button>
                    <button>Edit</button>
                    <button class="text-red-500">Delete</button>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

</div>
@endsection