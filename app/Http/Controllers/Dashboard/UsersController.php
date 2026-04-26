<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

use Illuminate\Support\Facades\Hash;

class UsersController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query();

        if ($request->search) {
            $query->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
        }

        if ($request->role) {
            $query->where('role', $request->role);
        }
        $title = 'Users || Admin Gassin!';
        $navtitle = 'Users';
        $users = $query->latest()->paginate(10);

        return view('pages.users', compact('users', 'title', 'navtitle'));
    }

    public function create()
    {
        $title = 'Create User || Admin Gassin!';
        $navtitle = 'Create User';
        return view('pages.users.create', compact('title', 'navtitle'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
            'role' => 'required'
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role
        ]);

        return redirect()->route('users.index')->with('success', 'User created');
    }

    public function edit(User $user)
    {
        $title = 'Edit User || Admin Gassin!';
        $navtitle = 'Edit User';
        return view('pages.users.edit', compact('user', 'title', 'navtitle'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'role' => 'required'
        ]);

        $user->update($request->only('name', 'email', 'role'));

        return redirect()->route('users.index')->with('success', 'User updated');
    }

    public function destroy(User $user)
    {
        $user->delete();

        return back()->with('success', 'User deleted');
    }
}