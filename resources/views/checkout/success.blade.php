@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto bg-white p-8 rounded-2xl shadow-sm border border-gray-100">
    <div class="text-center mb-6">
        <div class="w-16 h-16 bg-green-100 text-green-600 rounded-full flex items-center justify-center mx-auto text-2xl mb-3">
            <i class="fa-solid fa-check"></i>
        </div>
        <h1 class="text-2xl font-bold text-gray-800">Pesanan Berhasil Dibuat!</h1>
        <p class="text-gray-500 text-sm">Nomor Invoice: <span class="font-bold text-indigo-600">{{ $order->invoice }}</span></p>
    </div>

    @if(session('success'))
        <div class="bg-green-100 text-green-700 p-3 rounded-lg mb-6 text-sm text-center font-medium">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-gray-50 p-4 rounded-xl mb-6 text-sm space-y-2">
        <div class="flex justify-between">
            <span class="text-gray-500">Status Pembayaran:</span>
            <span class="font-bold {{ $order->status == 'PAID' ? 'text-green-600' : 'text-amber-600' }}">
                {{ $order->status }}
            </span>
        </div>
        <div class="flex justify-between">
            <span class="text-gray-500">Penerima:</span>
            <span class="font-semibold text-gray-800">{{ $order->customer_name }} ({{ $order->phone }})</span>
        </div>
        <div class="flex justify-between">
            <span class="text-gray-500">Alamat Kirim:</span>
            <span class="font-semibold text-gray-800">{{ $order->address }}</span>
        </div>
        <div class="flex justify-between border-t pt-2 mt-2">
            <span class="text-gray-500">Total Tagihan:</span>
            <span class="font-extrabold text-indigo-600 text-base">Rp{{ number_format($order->total, 0, ',', '.') }}</span>
        </div>
    </div>

    <h2 class="font-bold text-gray-800 mb-3">Daftar Barang:</h2>
    <div class="divide-y divide-gray-100 border-t border-b mb-6">
        @foreach($order->items as $item)
            <div class="py-3 flex justify-between text-sm">
                <div>
                    <span class="font-semibold text-gray-800">{{ $item->product_name }}</span>
                    <span class="text-xs text-gray-500 block">{{ $item->quantity }} x Rp{{ number_format($item->price, 0, ',', '.') }}</span>
                </div>
                <span class="font-bold text-gray-700">Rp{{ number_format($item->subtotal, 0, ',', '.') }}</span>
            </div>
        @endforeach
    </div>

    @if($order->status == 'UNPAID')
        <form action="{{ route('checkout.pay', $order->id) }}" method="POST" class="mb-4">
            @csrf
            <button type="submit" class="w-full bg-emerald-600 text-white py-3 rounded-xl font-bold hover:bg-emerald-700 transition shadow-md">
                <i class="fa-solid fa-wallet mr-1"></i> Simulasi Bayar Sekarang
            </button>
        </form>
    @endif

    <div class="text-center">
        <a href="/" class="text-sm text-indigo-600 hover:underline"><i class="fa-solid fa-house"></i> Kembali ke Beranda</a>
    </div>
</div>
@endsection