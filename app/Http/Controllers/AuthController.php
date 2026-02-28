<?php

namespace App\Http\Controllers;

use App\Models\IPBan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        // 1. Check if IP is banned
        if (IPBan::isBanned($request->ip())) {
            return back()->withErrors([
                'ip_banned' => 'Your IP address has been temporarily banned due to multiple failed login attempts. Please try again later.',
            ])->onlyInput('user_name');
        }

        $credentials = $request->validate([
            'user_name' => ['required'],
            'password' => ['required'],
        ]);

        // 2. Attempt login
        if (Auth::attempt(['user_name' => $credentials['user_name'], 'password' => $credentials['password']])) {
            // Reset failed attempts on successful login
            IPBan::where('ip_address', $request->ip())->update([
                'failed_attempts' => 0,
            ]);

            $request->session()->regenerate();

            return redirect()->intended('dashboard');
        }

        // 3. Record failed attempt
        IPBan::recordFailedAttempt($request->ip(), $request->userAgent());

        return back()->withErrors([
            'user_name' => 'The provided credentials do not match our records.',
        ])->onlyInput('user_name');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
