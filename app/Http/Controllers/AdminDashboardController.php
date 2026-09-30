<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    public function index()
    {
        // Anda bisa mengganti angka/data ini dengan query Eloquent sesungguhnya nanti
        $stats = [
            'total_users' => 120,
            'total_products' => 45,
            'total_orders' => 89,
            'total_revenue' => 15400000,
        ];

        return view('admin.dashboard', compact('stats'));
    }
}