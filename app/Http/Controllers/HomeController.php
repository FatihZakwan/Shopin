<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::query();

        // Fitur Search berdasarkan nama produk
        if ($request->has('search') && $request->search != '') {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // Fitur Filter Kategori
        if ($request->has('category') && $request->category != '' && $request->category != 'Semua') {
            $categoryParam = $request->category;

            if ($categoryParam === 'Lainnya') {
                // Kecualikan hanya kategori utama yang sudah punya tombol sendiri
                $query->whereNotIn('category', [
                    'Elektronik', 'Elektronik & Gadget',
                    'Fashion', 'Pakaian & Fashion',
                    'Makanan',
                    'Aksesoris',
                    'Sekolah', 'Perlengkapan Sekolah & Kantor'
                ]);
            } elseif ($categoryParam === 'perlengkapan sekolah' || $categoryParam === 'Sekolah') {
                // Menangani pencarian kategori sekolah/kantor secara fleksibel
                $query->where(function ($q) {
                    $q->where('category', 'like', '%Sekolah%')
                      ->orWhere('category', 'like', '%Kantor%');
                });
            } else {
                $query->where('category', 'like', '%' . $categoryParam . '%');
            }
        }

        $products = $query->latest()->get();

        // Daftar Kategori untuk Tombol Filter di View
        $categories = ['Semua', 'Elektronik', 'Fashion', 'Makanan', 'Aksesoris', 'Perlengkapan Sekolah', 'Lainnya'];

        return view('home', compact('products', 'categories'));
    }
}