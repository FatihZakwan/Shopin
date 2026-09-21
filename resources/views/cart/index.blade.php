@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto space-y-6">
    <h1 class="text-2xl font-bold text-gray-800 flex items-center gap-2">
        <i class="fa-solid fa-cart-shopping text-indigo-600"></i> Keranjang Belanja
    </h1>

    @if(session('success'))
        <div class="bg-green-100 text-green-700 p-3 rounded-xl text-sm font-medium">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-100 text-red-700 p-3 rounded-xl text-sm font-medium">
            {{ session('error') }}
        </div>
    @endif

    @if($cartItems->isEmpty())
        <div class="bg-white rounded-2xl p-12 text-center shadow-sm border border-gray-100 space-y-3">
            <i class="fa-solid fa-basket-shopping text-5xl text-gray-300"></i>
            <p class="text-gray-500 font-medium">Keranjang belanja kamu masih kosong.</p>
            <a href="/" class="inline-block bg-indigo-600 text-white px-6 py-2.5 rounded-xl font-bold text-sm hover:bg-indigo-700 transition">
                Mulai Belanja Now
            </a>
        </div>
    @else
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Daftar Item Keranjang -->
            <div class="lg:col-span-2 space-y-3">
                @foreach($cartItems as $item)
                    <div class="bg-white rounded-2xl p-4 shadow-sm border border-gray-100 flex items-center justify-between gap-4">
                        <div class="flex items-center gap-4">
                            <img src="{{ $item->product->image }}" alt="{{ $item->product->name }}" class="w-16 h-16 object-cover rounded-xl border border-gray-100">
                            <div>
                                <h3 class="font-bold text-gray-800 text-sm line-clamp-1">{{ $item->product->name }}</h3>
                                <p class="text-xs text-indigo-600 font-extrabold mt-0.5">
                                    Rp{{ number_format($item->product->price, 0, ',', '.') }}
                                </p>
                            </div>
                        </div>

                        <!-- Tombol Tambah/Kurang & Hapus -->
                        <div class="flex items-center gap-4">
                            <div class="flex items-center border border-gray-200 rounded-xl overflow-hidden bg-gray-50">
                                <form action="{{ route('cart.update') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="cart_id" value="{{ $item->id }}">
                                    <input type="hidden" name="type" value="dec">
                                    <button type="submit" class="w-8 h-8 flex items-center justify-center font-bold text-gray-600 hover:bg-gray-200 text-xs">-</button>
                                </form>

                                <span class="w-8 text-center text-xs font-bold">{{ $item->quantity }}</span>

                                <form action="{{ route('cart.update') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="cart_id" value="{{ $item->id }}">
                                    <input type="hidden" name="type" value="inc">
                                    <button type="submit" class="w-8 h-8 flex items-center justify-center font-bold text-gray-600 hover:bg-gray-200 text-xs">+</button>
                                </form>
                            </div>

                            <form action="{{ route('cart.remove') }}" method="POST">
                                @csrf
                                <input type="hidden" name="cart_id" value="{{ $item->id }}">
                                <button type="submit" class="text-red-500 hover:text-red-700 text-sm p-1">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Ringkasan Belanja -->
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 h-fit space-y-4">
                <h2 class="font-bold text-gray-800 border-b pb-3">Ringkasan Belanja</h2>
                <div class="flex justify-between items-center text-sm">
                    <span class="text-gray-500">Total Harga</span>
                    <span class="font-extrabold text-indigo-600 text-lg">Rp{{ number_format($total, 0, ',', '.') }}</span>
                </div>
                <a href="{{ route('checkout.index') }}" class="block text-center w-full bg-indigo-600 text-white py-3 rounded-xl font-bold hover:bg-indigo-700 transition shadow-md">
                    Lanjut ke Checkout <i class="fa-solid fa-arrow-right ml-1"></i>
                </a>
            </div>
        </div>
    @endif
</div>
@endsection