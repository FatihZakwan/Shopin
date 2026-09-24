<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            $user = Auth::user();

            // Pengalihan otomatis jika role ADMIN + Pesan Animasi
            if (strtoupper($user->role) === 'ADMIN') {
                return redirect()->route('admin.dashboard')->with('success', 'Selamat datang kembali ' . $user->name . '!');
            }

            // Pengalihan jika role USER + Pesan Animasi
            return redirect()->intended('/')->with('success', 'Selamat datang kembali, ' . $user->name . '!');
        }

        return back()->withErrors(['email' => 'Email atau password salah!'])->withInput();
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
            'password' => Hash::make($request->password), // Di-hash otomatis
            'role' => 'USER',
        ]);

        return redirect()->route('login')->with('success', 'Registrasi berhasil, silakan login!');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Ditambahkan pesan session agar SweetAlert2 logout terpicu
        return redirect('/')->with('success', 'Anda telah berhasil logout!');
    }
}