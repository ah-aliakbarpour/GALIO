<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Auth\Events\Registered;
use Illuminate\Validation\ValidationException;

class AdminController extends Controller
{
    public function index()
    {
        $admins = Admin::query()
            ->where('id', '!=', Auth::user()->admin->id)
            ->paginate(20);

        return view('admin.admin.index', compact([
            'admins',
        ]));
    }

    public function show(Admin $admin)
    {
        return view('admin.admin.show', compact([
            'admin',
        ]));
    }

    public function create()
    {
        return view('admin.admin.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],

            'role' => ['required', 'string', 'max:255'],
        ]);

        $user = User::create([
            'is_admin' => '1',
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => Hash::make($request->input('password')),
        ]);

        $user->admin()->create([
            'is_main_admin' => $request->input('main_admin') == 'on' ? '1' : '0',
            'role' => $request->input('role'),
        ]);

        return redirect()->route('admin.admin.index')
            ->with([
                'alert' => [
                    'massage' => 'Admin created successfully.',
                    'type' => 'success',
                ],
            ]);
    }

    public function edit(Admin $admin)
    {
        return view('admin.admin.edit', compact([
            'admin',
        ]));
    }

    public function update(Request $request, Admin $admin)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', "unique:users,email,{$admin->user_id}"],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],

            'role' => ['required', 'string', 'max:255'],
        ]);

        $admin->user()->update([
            'is_admin' => '1',
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => Hash::make($request->input('password')),
        ]);

        $admin->update([
            'is_main_admin' => $request->input('main_admin') == 'on' ? '1' : '0',
            'role' => $request->input('role'),
        ]);

        return redirect()->route('admin.admin.index')
            ->with([
                'alert' => [
                    'massage' => 'Admin edited successfully.',
                    'type' => 'success',
                ],
            ]);
    }

    public function destroy(Admin $admin)
    {
        $admin->user()->delete();

        return redirect()->route('admin.admin.index')
            ->with([
                'alert' => [
                    'massage' => 'Admin deleted successfully.',
                    'type' => 'success',
                ],
            ]);
    }

    public function selections(Request $request)
    {
        $adminsId = $request->input('admins_id');

        // No admin selected
        if (!$adminsId)
            throw ValidationException::withMessages(['selections' => 'No admin selected.']);

        foreach (array_keys($adminsId) as $id)
            Admin::query()->find($id)->user()->delete();

        return redirect()->route('admin.admin.index')
            ->with([
                'alert' => [
                    'massage' => 'Admins deleted successfully.',
                    'type' => 'success',
                ],
            ]);
    }
}
