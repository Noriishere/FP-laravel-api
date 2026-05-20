<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UsersController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query();

        if ($request->search) {

            $query->where(function ($q) use ($request) {

                $q->where(
                    'name',
                    'like',
                    '%'.$request->search.'%'
                )
                    ->orWhere(
                        'email',
                        'like',
                        '%'.$request->search.'%'
                    );
            });
        }

        if ($request->role) {

            $query->where(
                'role',
                $request->role
            );
        }

        $title = 'Users || Admin Gassin!';

        $navtitle = 'Users';

        $users = $query
            ->latest()
            ->paginate(10);

        return view(
            'pages.users',
            compact(
                'users',
                'title',
                'navtitle'
            )
        );
    }

    public function deletedAccounts(Request $request)
    {
        $query = User::onlyTrashed();

        if ($request->search) {

            $query->where(function ($q) use ($request) {

                $q->where(
                    'name',
                    'like',
                    '%'.$request->search.'%'
                )
                    ->orWhere(
                        'email',
                        'like',
                        '%'.$request->search.'%'
                    );
            });
        }

        if ($request->role) {

            $query->where(
                'role',
                $request->role
            );
        }

        $title = 'Deleted Accounts || Admin Gassin!';

        $navtitle = 'Deleted Accounts';

        $users = $query
            ->latest('deleted_at')
            ->paginate(10);

        return view(
            'pages.users.deleted',
            compact(
                'users',
                'title',
                'navtitle'
            )
        );
    }

    public function restore($id)
    {
        $user = User::onlyTrashed()
            ->findOrFail($id);

        $user->restore();

        return back()->with(
            'success',
            'Account restored'
        );
    }

    public function forceDelete($id)
    {
        $user = User::onlyTrashed()
            ->findOrFail($id);

        $user->forceDelete();

        return back()->with(
            'success',
            'Account permanently deleted'
        );
    }

    public function create()
    {
        $title = 'Create User || Admin Gassin!';

        $navtitle = 'Create User';

        return view(
            'pages.users.create',
            compact(
                'title',
                'navtitle'
            )
        );
    }

    public function store(Request $request)
    {
        $request->validate([

            'name' => 'required',

            'email' => [

                'required',

                'email',

                Rule::unique('users')
                    ->whereNull('deleted_at'),
            ],

            'password' => 'required|min:6',

            'role' => 'required',
        ]);

        User::create([

            'name' => $request->name,

            'email' => $request->email,

            'password' => Hash::make(
                $request->password
            ),

            'role' => $request->role,
        ]);

        return redirect()
            ->route('users.index')
            ->with(
                'success',
                'User created'
            );
    }

    public function edit(User $user)
    {
        $title = 'Edit User || Admin Gassin!';

        $navtitle = 'Edit User';

        return view(
            'pages.users.edit',
            compact(
                'user',
                'title',
                'navtitle'
            )
        );
    }

    public function update(
        Request $request,
        User $user
    ) {

        $request->validate([

            'name' => 'required',

            'email' => [

                'required',

                'email',

                Rule::unique('users')
                    ->ignore($user->id)
                    ->whereNull('deleted_at'),
            ],

            'role' => 'required',
        ]);

        $user->update([

            'name' => $request->name,

            'email' => $request->email,

            'role' => $request->role,
        ]);

        return redirect()
            ->route('users.index')
            ->with(
                'success',
                'User updated'
            );
    }

    public function destroy(User $user)
    {
        $user->update([
            'email' => time().'_'.$user->email,
        ]);

        $user->delete();

        return back()->with(
            'success',
            'User deleted'
        );
    }
}
