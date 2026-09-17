@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto bg-white p-6 rounded-2xl shadow-sm border border-gray-100 mt-6">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        
        <!-- Gambar Produk -->
        <div class="h-80 bg-gray-100 rounded-xl flex items-center justify-center text-gray-400">
            <i class="fa-solid fa-image text-7xl"></i>
        </div>

        <!-- Detail Produk -->
        <div class="flex flex-col justify-between">
            <div>
                <span class="text-xs font-semibold text-indigo-600 bg-indigo-50 px-2.5 py-1 rounded-full">{{ $product->category }}</span>
                <h1 class="text-2xl font-bold text-gray-800 mt-2">{{ $product->name }}</h1>
                <p class="text-2xl font-extrabold text-indigo-600 mt-2">Rp{{ number_format($product->price, 0, ',', '.') }}</p>
                <p class="text-sm text-gray-500 mt-2"><i class="fa-solid fa-boxes-stacked"></i> Stok Tersedia: <span class="font-semibold text-gray-800">{{ $product->stock }}</span></p>

                <div class="mt-4">
                    <h3 class="text-sm font-bold text-gray-700">Deskripsi:</h3>
                    <p class="text-gray-600 text-sm mt-1 leading-relaxed">{{ $product->description ?? 'Tidak ada deskripsi.' }}</p>
                </div>
            </div>

            <form action="/cart/add" method="POST" class="mt-6 pt-4 border-t border-gray-100">
                @csrf
                <input type="hidden" name="product_id" value="{{ $product->id }}">

                <div class="flex items-center gap-4 mb-4">
                    <span class="text-sm font-medium text-gray-700">Jumlah:</span>
                    <div class="flex items-center gap-2">
                        <button type="button" onclick="decrementQty()" class="w-8 h-8 bg-gray-200 hover:bg-gray-300 rounded-lg font-bold text-gray-700 flex items-center justify-center">-</button>
                        <input type="number" id="qty-input" name="quantity" value="1" min="1" max="{{ $product->stock }}" readonly class="w-12 text-center border border-gray-300 rounded-lg py-1 font-semibold text-gray-800">
                        <button type="button" onclick="incrementQty({{ $product->stock }})" class="w-8 h-8 bg-gray-200 hover:bg-gray-300 rounded-lg font-bold text-gray-700 flex items-center justify-center">+</button>
                    </div>
                </div>

                <button type="submit" class="w-full bg-indigo-600 text-white py-3 rounded-xl font-bold hover:bg-indigo-700 transition flex items-center justify-center gap-2 shadow-md">
                    <i class="fa-solid fa-cart-plus"></i> Tambah ke Keranjang
                </button>
            </form>
        </div>
    </div>
</div>

<script>
function incrementQty(maxStock) {
    let input = document.getElementById('qty-input');
    let val = parseInt(input.value);
    if (val < maxStock) {
        input.value = val + 1;
    }
}

function decrementQty() {
    let input = document.getElementById('qty-input');
    let val = parseInt(input.value);
    if (val > 1) {
        input.value = val - 1;
    }
}
</script>
@endsection