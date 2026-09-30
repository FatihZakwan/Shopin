<?php

namespace App\Http\Middleware;

Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        // Pastikan user sudah login DAN role-nya adalah ADMIN
        if (Auth::check() && Auth::user()->role === 'ADMIN') {
            return $next($request);
        }

        // Jika bukan admin, kembalikan ke halaman utama dengan pesan error
        return redirect('/')->with('error', 'Anda tidak memiliki akses ke halaman Admin.');
    }
}