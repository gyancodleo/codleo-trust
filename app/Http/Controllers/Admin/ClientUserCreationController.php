<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\client_users as Client;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\ClientUserCreateMail;

class ClientUserCreationController extends Controller
{
    public function index()
    {
        $clients = Client::with(['createdBy', 'updatedBy'])->get();
        return view('admin.create-client-user', compact('clients'));
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
            'email' => 'required|email|unique:client_users,email',
            'company_name'  => 'required'
        ]);

        $plainPassword = $this->generateStrongPassword(12);

        $user = Client::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($plainPassword),
            'company_name' => $request->company_name,
            'is_2fa_enabled' => $request->has('is_2fa_enabled'),
        ]);

        Mail::to($user->email)->send(new ClientUserCreateMail($user, $plainPassword));

        return back()->with('success', 'Client user created successfully. Login details sent to email.');
    }

    public function update(Request $request, Client $user)
    {
        $rules = [
            'name'  => 'required',
            'email' => "required|email|unique:client_users,email,{$user->id}",
            'company_role' => 'required',
        ];

        if ($request->password) {
            $rules['password'] = 'min:6';
        }

        $request->validate($rules);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'company_name' => $request->company_name,
            'is_2fa_enabled' => $request->has('is_2fa_enabled'),
        ];

        if ($request->password) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return back()->with('success', 'Client user updated successfully.');
    }

    public function destroy(Client $user)
    {

        $user->delete();

        return back()->with('success', 'Client user deleted.');
    }
}
