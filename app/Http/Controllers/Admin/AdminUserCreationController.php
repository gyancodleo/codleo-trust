<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Mail\AdminUserCreateMail;
use Illuminate\Support\Facades\Mail;

class AdminUserCreationController extends Controller
{
    public function index()
    {
        $admins = Admin::all();
        return view('admin.create-admin-user', compact('admins'));
    }

    private function generateStrongPassword($length = 16)
    {
        $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()';
        return substr(str_shuffle(str_repeat($chars, 5)), 0, $length);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'  => 'required',
            'email' => 'required|email|unique:admins,email',
            'role'  => 'required|in:admin,super_admin'
        ]);

        $plainPassword = $this->generateStrongPassword();

        $user = Admin::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($plainPassword),
            'role' => $request->role,
            'is_2fa_enabled' => $request->has('is_2fa_enabled'),
        ]);

        Mail::to($user->email)->send(new AdminUserCreateMail($user, $plainPassword));

        return back()->with('success', 'Admin user created successfully. Login details sent to email.');
    }

    public function update(Request $request, Admin $user)
    {
        $rules = [
            'name'  => 'required',
            'email' => "required|email|unique:admins,email,{$user->id}",
            'role' => 'required|in:admin,super_admin'
        ];

        if ($request->password) {
            $rules['password'] = 'min:6';
        }

        $request->validate($rules);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'is_2fa_enabled' => $request->has('is_2fa_enabled'),
        ];

        if ($request->password) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return back()->with('success', 'Admin user updated successfully.');
    }

    public function destroy(Admin $user)
    {
        if ($user->role === 'super_admin') {
            return back()->with('error', 'Cannot delete super admin.');
        }

        $user->delete();

        return back()->with('success', 'Admin user deleted.');
    }
}
