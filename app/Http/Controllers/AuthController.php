<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AuthController extends Controller
{
    /**
     * Tampilkan formulir login.
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Tangani permintaan login.
     */
    public function login(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'pin' => 'required|numeric',
        ]);

        $user = User::where('name', $request->input('name'))
            ->where('pin', $request->input('pin'))
            ->first();

        if ($user) {
            Auth::login($user);
            $request->session()->regenerate();
            return redirect()->intended('/dashboard');
        }

        return back()->withErrors([
            'login' => 'Nama atau PIN salah.',
        ]);
    }

    /**
     * Log out pengguna.
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}