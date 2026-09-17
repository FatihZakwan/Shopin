@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto">
    <h1 class="text-2xl font-bold text-gray-800 mb-6 flex items-center gap-2">
        <i class="fa-solid fa-cart-shopping text-indigo-600"></i> Keranjang Belanja
    </h1>

    @if(session('success'))
        <div class="bg-green-100 text-green-700 p-3 rounded-lg mb-4 text-sm font-medium">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-100 text-red-700 p-3 rounded-lg mb-4 text-sm font-medium">
            {{ session('error') }}
        </div>
    @endif

    @if(empty($cart))
        <div class="bg-white p-12 text-center rounded-2xl shadow-sm border border-gray-100">
            <i class="fa-solid fa-cart-flatbed text-5xl text-gray-300 mb-3"></i>
            <p class="text-gray-500 font-medium">Keranjang belanja kamu masih kosong.</p>
            <a href="/" class="inline-block mt-4 bg-indigo-600 text-white px-6 py-2.5 rounded-xl text-sm font-semibold hover:bg-indigo-700 transition">
                Mulai Belanja Now
            </a>
        </div>
    @else
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Tabel Item Keranjang -->
            <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm border border-gray-100 p-4 overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b text-xs uppercase text-gray-400">
                            <th class="py-3 px-2">Produk</th>
                            <th class="py-3 px-2">Harga</th>
                            <th class="py-3 px-2 text-center">Jumlah</th>
                            <th class="py-3 px-2">Subtotal</th>
                            <th class="py-3 px-2 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 text-sm">
                        @foreach($cart as $item)
                            <tr>
                                <td class="py-4 px-2 font-semibold text-gray-800">
                                    {{ $item['name'] }}
                                    <span class="block text-xs text-indigo-600 font-normal">{{ $item['category'] }}</span>
                                </td>
                                <td class="py-4 px-2 text-gray-600">
                                    Rp{{ number_format($item['price'], 0, ',', '.') }}
                                </td>
                                <td class="py-4 px-2">
                                    <form action="/cart/update" method="POST" class="flex items-center justify-center gap-1">
                                        @csrf
                                        <input type="hidden" name="product_id" value="{{ $item['id'] }}">
                                        <input type="number" name="quantity" value="{{ $item['quantity'] }}" min="1" max="{{ $item['stock'] }}" class="w-14 text-center border border-gray-300 rounded-lg p-1 text-xs">
                                        <button type="submit" class="bg-gray-200 hover:bg-gray-300 px-2 py-1 rounded text-xs"><i class="fa-solid fa-rotate"></i></button>
                                    </form>
                                </td>
                                <td class="py-4 px-2 font-bold text-indigo-600">
                                    Rp{{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }}
                                </td>
                                <td class="py-4 px-2 text-center">
                                    <form action="/cart/remove" method="POST" class="inline">
                                        @csrf
                                        <input type="hidden" name="product_id" value="{{ $item['id'] }}">
                                        <button type="submit" class="text-red-500 hover:text-red-700 text-xs font-semibold">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="mt-4 pt-3 border-t flex justify-between">
                    <form action="/cart/clear" method="POST">
                        @csrf
                        <button type="submit" class="text-xs text-red-600 hover:underline"><i class="fa-solid fa-broom"></i> Kosongkan Keranjang</button>
                    </form>
                    <a href="/" class="text-xs text-indigo-600 hover:underline"><i class="fa-solid fa-arrow-left"></i> Tambah Produk Lain</a>
                </div>
            </div>

            <!-- Ringkasan Belanja -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 h-fit">
                <h2 class="text-lg font-bold text-gray-800 mb-4">Ringkasan Belanja</h2>
                <div class="flex justify-between text-sm text-gray-600 mb-2">
                    <span>Total Barang</span>
                    <span>{{ count($cart) }} Macam</span>
                </div>
                <div class="border-t pt-3 mt-3 flex justify-between font-bold text-lg text-gray-800">
                    <span>Total Tagihan</span>
                    <span class="text-indigo-600">Rp{{ number_format($total, 0, ',', '.') }}</span>
                </div>

                <a href="/checkout" class="block w-full text-center mt-6 bg-indigo-600 text-white py-3 rounded-xl font-bold hover:bg-indigo-700 transition shadow-md">
                    Lanjut ke Checkout <i class="fa-solid fa-arrow-right ml-1"></i>
                </a>
            </div>
        </div>
    @endif
</div>
@endsection