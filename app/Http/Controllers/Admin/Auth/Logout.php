<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Container\Attributes\Auth;
use Illuminate\Http\Request;

class Logout extends Controller
{
    public function __invoke(Request $request)
    {

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login')->with('success', 'You have been logged out.');
    }
}
