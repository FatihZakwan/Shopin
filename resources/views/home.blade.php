@extends('layouts.app')

@section('content')
<!-- Hero Banner -->
<div class="bg-gradient-to-r from-indigo-500 to-purple-600 rounded-2xl p-8 text-white mb-8 shadow-lg text-center">
    <h1 class="text-3xl font-extrabold mb-2">BELANJA MUDAH DI SHOPIN</h1>
    <p class="text-indigo-100">Temukan berbagai produk pilihan dengan harga terbaik.</p>
</div>

<!-- Kategori Filter -->
<div class="mb-6 flex flex-wrap items-center gap-2">
    <span class="font-bold text-gray-700 mr-2"><i class="fa-solid fa-layer-group"></i> Kategori:</span>
    @foreach($categories as $cat)
        <a href="/?category={{ $cat }}{{ request('search') ? '&search='.request('search') : '' }}" 
           class="px-4 py-1.5 rounded-full text-sm font-medium transition {{ (request('category', 'Semua') == $cat) ? 'bg-indigo-600 text-white shadow' : 'bg-white text-gray-700 hover:bg-gray-200' }}">
            {{ $cat }}
        </a>
    @endforeach
</div>

<!-- Daftar Produk Card -->
<h2 class="text-xl font-bold text-gray-800 mb-4">Produk Terbaru</h2>

@if($products->isEmpty())
    <div class="bg-white p-8 text-center rounded-xl shadow-sm text-gray-500">
        <i class="fa-solid fa-box-open text-4xl mb-2 text-gray-400"></i>
        <p>Produk tidak ditemukan.</p>
    </div>
@else
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
        @foreach($products as $product)
            <div class="bg-white rounded-xl shadow-sm hover:shadow-md transition overflow-hidden flex flex-col justify-between border border-gray-100">
                <a href="/products/{{ $product->id }}">
                    <div class="h-48 bg-gray-100 flex items-center justify-center text-gray-400">
                        <i class="fa-solid fa-image text-5xl"></i>
                    </div>
                </a>
                <div class="p-4 flex-grow flex flex-col justify-between">
                    <div>
                        <span class="text-xs font-semibold text-indigo-600 bg-indigo-50 px-2 py-0.5 rounded">{{ $product->category }}</span>
                        <a href="/products/{{ $product->id }}" class="block font-semibold text-gray-800 hover:text-indigo-600 mt-2 line-clamp-1">
                            {{ $product->name }}
                        </a>
                        <p class="text-indigo-600 font-bold text-lg mt-1">Rp{{ number_format($product->price, 0, ',', '.') }}</p>
                        <p class="text-xs text-gray-500 mt-1"><i class="fa-solid fa-boxes-stacked"></i> Stok: {{ $product->stock }}</p>
                    </div>

                    <form action="/cart/add" method="POST" class="mt-4">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        
                        <!-- Control Quantity JS Sederhana -->
                        <div class="flex items-center justify-center gap-2 mb-3">
                            <button type="button" onclick="decrementQty({{ $product->id }})" class="w-7 h-7 bg-gray-200 hover:bg-gray-300 rounded font-bold text-gray-700 flex items-center justify-center">-</button>
                            <input type="number" id="qty-{{ $product->id }}" name="quantity" value="1" min="1" max="{{ $product->stock }}" readonly class="w-10 text-center border border-gray-300 rounded text-sm py-0.5">
                            <button type="button" onclick="incrementQty({{ $product->id }}, {{ $product->stock }})" class="w-7 h-7 bg-gray-200 hover:bg-gray-300 rounded font-bold text-gray-700 flex items-center justify-center">+</button>
                        </div>

                        <button type="submit" class="w-full bg-indigo-600 text-white py-2 rounded-lg text-sm font-semibold hover:bg-indigo-700 transition flex items-center justify-center gap-2">
                            <i class="fa-solid fa-cart-plus"></i> + Keranjang
                        </button>
                    </form>
                </div>
            </div>
        @endforeach
    </div>
@endif

<script>
function incrementQty(id, maxStock) {
    let input = document.getElementById('qty-' + id);
    let val = parseInt(input.value);
    if (val < maxStock) {
        input.value = val + 1;
    }
}

function decrementQty(id) {
    let input = document.getElementById('qty-' + id);
    let val = parseInt(input.value);
    if (val > 1) {
        input.value = val - 1;
    }
}
</script>
@endsection