<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class LoginController extends Controller
{
    /**
     * Show the application's login form.
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Handle a login request to the application.
     */
    public function login(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'pin' => 'required|string',
        ]);

        $credentials = $request->only('name', 'pin');

        $user = User::where('name', $credentials['name'])->first();

        if ($user && $credentials['pin'] === $user->pin) {
            Auth::login($user);
            $request->session()->regenerate();

            return redirect()->intended('/gallery');
        }

        return back()->withErrors([
            'login' => 'Nama atau PIN salah.',
        ])->onlyInput('name');
    }

    /**
     * Log the user out of the application.
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}