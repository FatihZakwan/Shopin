<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Cari user berdasarkan email & password plaintext
        $user = User::where('email', $request->email)
                    ->where('password', $request->password)
                    ->first();

        if (!$user) {
            return back()->withErrors(['email' => 'Email atau password salah!'])->withInput();
        }

        Auth::login($user);
        $request->session()->regenerate();

        if ($user->role === 'ADMIN') {
            return redirect()->intended('/admin');
        }

        return redirect()->intended('/');
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:4|confirmed',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password, // Simpan plaintext
            'role' => 'USER', // Otomatis USER
        ]);

        return redirect()->route('login')->with('success', 'Registrasi berhasil, silakan login!');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}