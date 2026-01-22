<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        if ($request->isMethod('POST')) {
            $credentials = $request->validate([
                'user_email' => ['required', 'email'],
                'user_password' => ['required'],
            ]);

            // Map 'user_password' to 'password' for the Auth::attempt method
            if (Auth::attempt([
                'user_email' => $credentials['user_email'],
                'password' => $credentials['user_password'],
                'user_status' => 'ACTIVE'
            ])) {
                $request->session()->regenerate();

                return redirect()->route('dashboard.index');
            }

            return back()->withErrors(['user_email' => 'Invalid credentials.']);
        }

        return view('login');
    }
}
