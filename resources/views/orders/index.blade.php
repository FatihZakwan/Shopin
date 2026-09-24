@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold text-gray-800 mb-6 flex items-center gap-2">
        <i class="fa-solid fa-box-archive text-indigo-600"></i> Pesanan Saya
    </h1>

    @if(session('success'))
        <div class="bg-green-100 text-green-700 p-4 rounded-xl mb-6 text-sm font-medium">
            {{ session('success') }}
        </div>
    @endif

    @if($orders->isEmpty())
        <div class="bg-white rounded-2xl p-8 text-center border border-gray-100 shadow-sm">
            <i class="fa-solid fa-basket-shopping text-5xl text-gray-300 mb-3"></i>
            <p class="text-gray-500 font-medium">Anda belum memiliki pesanan apapun.</p>
            <a href="{{ route('home') }}" class="inline-block mt-4 bg-indigo-600 text-white px-5 py-2.5 rounded-xl font-bold hover:bg-indigo-700 transition">
                Mulai Belanja
            </a>
        </div>
    @else
        <div class="space-y-4">
            @foreach($orders as $order)
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 transition hover:shadow-md">
                    <div class="flex flex-wrap justify-between items-center border-b pb-3 mb-3 gap-2">
                        <div>
                            <span class="text-xs font-bold text-gray-400 uppercase">INVOICE</span>
                            <p class="font-bold text-gray-800">{{ $order->invoice }}</p>
                            <p class="text-xs text-gray-400">{{ $order->created_at->format('d M Y, H:i') }} WIB</p>
                        </div>
                        
                        <div>
                            @if($order->status === 'PAID')
                                <span class="bg-green-100 text-green-700 text-xs font-bold px-3 py-1 rounded-full">Selesai / Lunas</span>
                            @elseif($order->status === 'UNPAID')
                                <span class="bg-yellow-100 text-yellow-700 text-xs font-bold px-3 py-1 rounded-full">Menunggu Pembayaran</span>
                            @else
                                <span class="bg-gray-100 text-gray-600 text-xs font-bold px-3 py-1 rounded-full">{{ $order->status }}</span>
                            @endif
                        </div>
                    </div>

                    <!-- Item Pesanan -->
                    <div class="divide-y divide-gray-50">
                        @foreach($order->orderItems as $item)
                            <div class="py-2 flex justify-between items-center text-sm">
                                <div>
                                    <p class="font-semibold text-gray-800">{{ $item->product_name }}</p>
                                    <p class="text-xs text-gray-500">{{ $item->quantity }} x Rp{{ number_format($item->price, 0, ',', '.') }}</p>
                                </div>
                                <span class="font-semibold text-gray-700">Rp{{ number_format($item->subtotal, 0, ',', '.') }}</span>
                            </div>
                        @endforeach
                    </div>

                    <div class="border-t pt-3 mt-3 flex justify-between items-center">
                        <div>
                            <span class="text-xs text-gray-500">Total Belanja:</span>
                            <p class="text-lg font-bold text-indigo-600">Rp{{ number_format($order->total, 0, ',', '.') }}</p>
                        </div>

                        <div class="flex gap-2">
                            @if($order->status === 'UNPAID' && $order->payment && $order->payment->reference)
                                <a href="https://tripay.co.id/checkout/{{ $order->payment->reference }}" target="_blank" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-xl text-xs font-bold transition">
                                    Bayar Sekarang
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection