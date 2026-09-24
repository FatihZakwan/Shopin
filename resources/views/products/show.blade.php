@extends('layouts.app')

@section('content')
<!-- Style CSS untuk Menghilangkan Panah Bawaan Input Number Browser -->
<style>
    /* Chrome, Safari, Edge, Opera */
    input[type=number]::-webkit-outer-spin-button,
    input[type=number]::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }

    /* Firefox */
    input[type=number] {
        -moz-appearance: textfield;
    }
</style>

<div class="max-w-4xl mx-auto bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
    
    @if(session('success'))
        <div class="m-6 mb-0 bg-green-100 text-green-700 p-3 rounded-xl text-sm font-medium flex items-center justify-between">
            <span><i class="fa-solid fa-circle-check mr-1"></i> {{ session('success') }}</span>
            <a href="/cart" class="underline font-bold text-xs">Lihat Keranjang &rarr;</a>
        </div>
    @endif

    @if(session('error'))
        <div class="m-6 mb-0 bg-red-100 text-red-700 p-3 rounded-xl text-sm font-medium">
            <i class="fa-solid fa-circle-exclamation mr-1"></i> {{ session('error') }}
        </div>
    @endif

    <div class="p-6 md:p-8 grid grid-cols-1 md:grid-cols-2 gap-8">
        <!-- Foto Produk Besar -->
        <div class="aspect-square bg-gray-50 rounded-2xl overflow-hidden border border-gray-100 relative">
            @if($product->image)
                <img src="{{ $product->image }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
            @else
                <div class="w-full h-full flex items-center justify-center text-gray-300">
                    <i class="fa-solid fa-image text-6xl"></i>
                </div>
            @endif
        </div>

        <!-- Detail Rincian Produk -->
        <div class="flex flex-col justify-between space-y-4">
            <div>
                <span class="inline-block bg-indigo-50 text-indigo-600 text-xs font-bold px-3 py-1 rounded-full mb-2">
                    {{ $product->category }}
                </span>
                <h1 class="text-2xl font-bold text-gray-800 leading-tight mb-2">{{ $product->name }}</h1>
                
                <div class="bg-gray-50 p-4 rounded-2xl my-4">
                    <p class="text-xs text-gray-400 font-semibold uppercase mb-1">Harga</p>
                    <p class="text-3xl font-extrabold text-indigo-600">Rp{{ number_format($product->price, 0, ',', '.') }}</p>
                </div>

                <div class="space-y-1 text-sm text-gray-600">
                    <p class="font-semibold text-gray-700">Deskripsi Produk:</p>
                    <p class="text-gray-500 leading-relaxed text-xs">{{ $product->description ?? 'Tidak ada deskripsi produk.' }}</p>
                </div>

                <div class="mt-4 text-xs text-gray-500 flex items-center gap-2">
                    <span>Stok Tersedia:</span>
                    <span class="font-bold text-gray-800">{{ $product->stock }} pcs</span>
                </div>
            </div>

            <!-- Form Tambah Keranjang + Pemilih Jumlah (Quantity) -->
            <form action="{{ route('cart.add') }}" method="POST" class="pt-4 border-t space-y-4">
                @csrf
                <input type="hidden" name="product_id" value="{{ $product->id }}">

                <div class="flex items-center gap-4">
                    <label class="text-xs font-bold text-gray-600 uppercase">Jumlah:</label>
                    <div class="flex items-center border border-gray-300 rounded-xl overflow-hidden">
                        <button type="button" onclick="decrementQty()" class="px-3 py-2 bg-gray-100 hover:bg-gray-200 text-gray-600 font-bold text-sm transition active:scale-95">-</button>
                        <input type="number" id="quantity" name="quantity" value="1" min="1" max="{{ $product->stock }}" class="w-12 text-center text-sm font-bold border-none focus:outline-none focus:ring-0">
                        <button type="button" onclick="incrementQty({{ $product->stock }})" class="px-3 py-2 bg-gray-100 hover:bg-gray-200 text-gray-600 font-bold text-sm transition active:scale-95">+</button>
                    </div>
                </div>

                <button type="submit" class="w-full bg-indigo-600 text-white py-3 rounded-2xl font-bold hover:bg-indigo-700 transition shadow-md flex items-center justify-center gap-2">
                    <i class="fa-solid fa-cart-plus"></i> + Masukkan Keranjang
                </button>
            </form>
        </div>
    </div>
</div>

<script>
    function incrementQty(max) {
        let input = document.getElementById('quantity');
        let val = parseInt(input.value) || 1;
        if (val < max) input.value = val + 1;
    }
    function decrementQty() {
        let input = document.getElementById('quantity');
        let val = parseInt(input.value) || 1;
        if (val > 1) input.value = val - 1;
    }
</script>
@endsection