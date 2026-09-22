<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. STATISTIK KARTU UTAMA
        $totalProducts = Product::count();
        
        // Menghitung khusus Pengguna (Pembeli) agar angka di kartu statistik akurat
        $totalUsers = User::where('role', 'USER')->orWhereNull('role')->count();
        
        $totalOrders = Order::count();
        $totalRevenue = Order::where('status', 'PAID')->sum('total');

        // 2. DATA GRAFIK PENGHASILAN (Tahun Ini)
        $monthlyRevenue = Order::select(
            DB::raw('MONTH(created_at) as month'),
            DB::raw('SUM(total) as total')
        )
        ->where('status', 'PAID')
        ->whereYear('created_at', date('Y'))
        ->groupBy('month')
        ->orderBy('month')
        ->pluck('total', 'month')
        ->toArray();

        $chartData = [];
        for ($i = 1; $i <= 12; $i++) {
            $chartData[] = $monthlyRevenue[$i] ?? 0;
        }

        // 3. TABEL DATA PENGGUNA (Mengambil SEMUA akun: ADMIN & USER)
        $users = User::latest()->get(); 

        // 4. TABEL DATA PRODUK
        $products = Product::latest()->get();

        return view('admin.dashboard', compact(
            'totalProducts',
            'totalUsers',
            'totalOrders',
            'totalRevenue',
            'chartData',
            'users',
            'products'
        ));
    }
}