<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserProfileController extends Controller
{
    public function index()
    {
        $user = Auth::guard('web')->user();
        return view('backend.user.profile.index', compact('user'));
    }

    public function update(Request $request)
    {
        $user = Auth::guard('web')->user();

        $rules = [
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'current_password' => 'nullable|required_with:password',
            'password' => 'nullable|min:8|confirmed',
        ];
        if ($user->must_change_password) {
            $rules['password'] = 'required|min:8|confirmed';
        }

        $request->validate($rules, [
            'current_password.required_with' => 'Please enter your current password to change the password.',
            'password.required' => 'Please set a new password to continue.',
        ]);

        if ($request->filled('password')) {
            if (!Hash::check($request->input('current_password'), $user->password)) {
                return redirect()->back()->withErrors(['current_password' => 'The current password you entered is incorrect.'])->withInput();
            }
        }

        $data = [
            'name' => $request->input('name'),
            'phone' => $request->input('phone'),
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->input('password'));
            $data['must_change_password'] = 0;
        }

        $user->update($data);

        return redirect()->route('user.profile.index')->with('success', 'Profile updated successfully.');
    }
}