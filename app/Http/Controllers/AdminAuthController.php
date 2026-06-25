<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class AdminAuthController extends Controller
{
    public function login(Request $request) 
    {
        // 1. Validate input
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        // 2. Check user
        $user = DB::table('users')->where('email', $request->email)->first();

        if (!$user) {
            return back()->withErrors(['email' => 'Email tidak wujud.']);
        }

        if (!Hash::check($request->password, $user->password)) {
            return back()->withErrors(['password' => 'Kata laluan salah.']);
        }

        // 3. Save session
        session(['admin_id' => $user->id]);

        // 4. Redirect to correct dashboard route
        return redirect()->route('admin.index');
    }
}