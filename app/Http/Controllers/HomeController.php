<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::query();

        // Fitur Search berdasarkan nama
        if ($request->has('search') && $request->search != '') {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // Fitur Filter Kategori
        if ($request->has('category') && $request->category != '' && $request->category != 'Semua') {
            $query->where('category', $request->category);
        }

        $products = $query->latest()->get();

        $categories = ['Semua', 'Elektronik', 'Fashion', 'Makanan', 'Aksesoris', 'Lainnya'];

        return view('home', compact('products', 'categories'));
    }
}