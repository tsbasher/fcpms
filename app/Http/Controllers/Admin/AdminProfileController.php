<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AdminProfileController extends Controller
{
    public function index()
    {
        $admin = Auth::guard('admin')->user();
        return view('backend.admin.profile.index', compact('admin'));
    }

    public function update(Request $request)
    {
        $admin = Auth::guard('admin')->user();

        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'current_password' => 'nullable|required_with:password',
            'password' => 'nullable|min:8|confirmed',
        ], [
            'current_password.required_with' => 'Please enter your current password to change the password.',
        ]);

        if ($request->filled('password')) {
            if (!Hash::check($request->input('current_password'), $admin->password)) {
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

        $admin->update($data);

        return redirect()->route('admin.profile.index')->with('success', 'Profile updated successfully.');
    }
}