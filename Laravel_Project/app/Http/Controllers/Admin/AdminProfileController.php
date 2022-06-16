<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;

class AdminProfileController extends Controller
{
    public function profile() {
        $user = Auth::user();

        return view('admin.profile', compact([
            'user',
        ]));
    }

    public function save_changes(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
        ]);

        User::query()->find(Auth::id())
            ->update([
                'name' => $request->input('name'),
            ]);

        return redirect()->route('admin.profile')
            ->with([
                'alert' => [
                    'massage' => 'Saved.',
                    'type' => 'success',
                ],
            ]);
    }

    public function change_password(Request $request)
    {
        $request->validate([
            'current_password' => ['required'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $current_password = Auth::User()->password;

        if(Hash::check($request->input('current_password'), $current_password))
        {
            User::query()->find(Auth::id())
                ->update([
                    'password' => Hash::make($request->input('password')),
                ]);
        }
        else
            throw ValidationException::withMessages(['current_password' => 'Current password is incorrect.']);

        return redirect()->route('admin.profile')
            ->with([
                'alert' => [
                    'massage' => 'Password changed.',
                    'type' => 'success',
                ],
            ]);
    }
}
