<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Mail\WelcomeMail;

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
            $role = strtoupper($user->role);

            // 1. Khusus Super Admin -> langsung ke dashboard Super Admin
            if ($role === 'SUPER_ADMIN' || $role === 'SUPERADMIN') {
                return redirect()->intended('/')->with('success', 'Selamat datang Super Admin, ' . $user->name . '!');
            }

            // 2. Admin Toko & User biasa -> diarahkan ke Halaman Utama (Beranda /)
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
            'name'             => 'required|string|max:255',
            'email'            => 'required|string|email|max:255|unique:users',
            'email_notifikasi' => 'required|string|email|max:255',
            'password'         => 'required|string|min:4|confirmed',
        ]);
    
        $user = User::create([
            'name'             => $request->name,
            'email'            => $request->email,
            'email_notifikasi' => $request->email_notifikasi,
            'password'         => Hash::make($request->password),
            'role'             => 'USER',
        ]);
    
        // Tentukan target pengiriman email (mengutamakan email_notifikasi)
        $targetEmail = $user->email_notifikasi ?? $user->email;
    
        try {
            Mail::send('emails.welcome', [
                'name'  => $user->name,
                'email' => $user->email,
            ], function ($message) use ($targetEmail) {
                $message->to($targetEmail)
                        ->subject('Selamat Datang di SHOPIN!');
            });
        } catch (\Exception $e) {
            \Log::error('Gagal mengirim email welcome: ' . $e->getMessage());
        }
    
        return redirect()->route('login')->with('success', 'Registrasi berhasil, silakan login!');
    }
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('success', 'Anda telah berhasil logout!');
    }
}