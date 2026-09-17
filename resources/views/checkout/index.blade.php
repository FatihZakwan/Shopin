@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto">
    <h1 class="text-2xl font-bold text-gray-800 mb-6 flex items-center gap-2">
        <i class="fa-solid fa-credit-card text-indigo-600"></i> Checkout Pesanan
    </h1>

    @if(session('error'))
        <div class="bg-red-100 text-red-700 p-3 rounded-lg mb-4 text-sm font-medium">
            {{ session('error') }}
        </div>
    @endif

    <form action="{{ route('checkout.store') }}" method="POST">
        @csrf
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            
            <!-- Formulir Data Pengiriman -->
            <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm border border-gray-100 p-6 space-y-4">
                <h2 class="text-lg font-bold text-gray-800 border-b pb-3">Informasi Pengiriman</h2>

                <div>
                    <label class="block text-xs font-semibold uppercase text-gray-500 mb-1">Nama Lengkap</label>
                    <input type="text" name="customer_name" value="{{ Auth::user()->name }}" required class="w-full border border-gray-300 rounded-xl p-2.5 text-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold uppercase text-gray-500 mb-1">Email</label>
                        <input type="email" name="customer_email" value="{{ Auth::user()->email }}" required class="w-full border border-gray-300 rounded-xl p-2.5 text-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold uppercase text-gray-500 mb-1">Nomor WhatsApp / HP</label>
                        <input type="text" name="phone" placeholder="08123456789" required class="w-full border border-gray-300 rounded-xl p-2.5 text-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold uppercase text-gray-500 mb-1">Alamat Lengkap Pengiriman</label>
                    <textarea name="address" rows="3" placeholder="Jl. Merdeka No. 123, Kota, Provinsi, Kode Pos" required class="w-full border border-gray-300 rounded-xl p-2.5 text-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none"></textarea>
                </div>

                <h2 class="text-lg font-bold text-gray-800 border-b pb-3 pt-4">Metode Pembayaran</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                    <label class="border rounded-xl p-3 flex items-center gap-2 cursor-pointer hover:border-indigo-500">
                        <input type="radio" name="payment_method" value="BANK_TRANSFER" checked class="text-indigo-600">
                        <span class="text-sm font-semibold text-gray-700">Transfer Bank</span>
                    </label>
                    <label class="border rounded-xl p-3 flex items-center gap-2 cursor-pointer hover:border-indigo-500">
                        <input type="radio" name="payment_method" value="E_WALLET" class="text-indigo-600">
                        <span class="text-sm font-semibold text-gray-700">E-Wallet (QRIS)</span>
                    </label>
                    <label class="border rounded-xl p-3 flex items-center gap-2 cursor-pointer hover:border-indigo-500">
                        <input type="radio" name="payment_method" value="COD" class="text-indigo-600">
                        <span class="text-sm font-semibold text-gray-700">Bayar di Tempat (COD)</span>
                    </label>
                </div>
            </div>

            <!-- Rincian Ringkasan Pesanan -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 h-fit space-y-4">
                <h2 class="text-lg font-bold text-gray-800 border-b pb-3">Ringkasan Pesanan</h2>

                <div class="divide-y divide-gray-100 max-h-60 overflow-y-auto">
                    @foreach($cart as $item)
                        <div class="py-2 flex justify-between text-sm">
                            <div>
                                <p class="font-semibold text-gray-800">{{ $item['name'] }}</p>
                                <p class="text-xs text-gray-500">{{ $item['quantity'] }} x Rp{{ number_format($item['price'], 0, ',', '.') }}</p>
                            </div>
                            <span class="font-bold text-gray-700">Rp{{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }}</span>
                        </div>
                    @endforeach
                </div>

                <div class="border-t pt-3 flex justify-between font-bold text-lg text-gray-800">
                    <span>Total Bayar</span>
                    <span class="text-indigo-600">Rp{{ number_format($total, 0, ',', '.') }}</span>
                </div>

                <button type="submit" class="w-full bg-indigo-600 text-white py-3 rounded-xl font-bold hover:bg-indigo-700 transition shadow-md">
                    Buat Pesanan Sekarang <i class="fa-solid fa-arrow-right ml-1"></i>
                </button>
            </div>
        </div>
    </form>
</div>
@endsection