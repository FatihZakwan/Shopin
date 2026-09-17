@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto">
    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Dashboard Admin</h1>
        <p class="text-gray-500 text-sm">Selamat datang, {{ Auth::user()->name }}!</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-indigo-600 text-white p-6 rounded-2xl shadow-md">
            <p class="text-xs font-semibold uppercase tracking-wider text-indigo-200">Total Produk</p>
            <h2 class="text-3xl font-extrabold mt-2">{{ \App\Models\Product::count() }}</h2>
        </div>
        <div class="bg-emerald-600 text-white p-6 rounded-2xl shadow-md">
            <p class="text-xs font-semibold uppercase tracking-wider text-emerald-200">Total User</p>
            <h2 class="text-3xl font-extrabold mt-2">{{ \App\Models\User::count() }}</h2>
        </div>
        <div class="bg-amber-500 text-white p-6 rounded-2xl shadow-md">
            <p class="text-xs font-semibold uppercase tracking-wider text-amber-100">Total Pesanan</p>
            <h2 class="text-3xl font-extrabold mt-2">{{ \App\Models\Order::count() }}</h2>
        </div>
    </div>
</div>
@endsection