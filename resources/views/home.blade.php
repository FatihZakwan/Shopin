@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto space-y-6">

    <!-- Banner Shopee Style -->
    <div class="bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-500 rounded-3xl p-8 text-white shadow-lg text-center relative overflow-hidden">
        <div class="relative z-10">
            <h1 class="text-3xl font-extrabold tracking-tight mb-2">BELANJA MUDAH DI SHOPIN</h1>
            <p class="text-indigo-100 text-sm font-medium">Temukan berbagai produk pilihan berkualitas dengan harga terbaik!</p>
        </div>
    </div>

    <!-- Filter Kategori -->
    <div class="flex items-center gap-2 overflow-x-auto pb-2">
        <span class="text-xs font-bold uppercase text-gray-400 mr-2 flex items-center gap-1">
            <i class="fa-solid fa-filter"></i> Kategori:
        </span>
        <a href="/" class="px-4 py-1.5 rounded-full text-xs font-semibold {{ !request('category') ? 'bg-indigo-600 text-white shadow-sm' : 'bg-white text-gray-600 border border-gray-200 hover:bg-gray-50' }}">
            Semua
        </a>
        @foreach(['Elektronik', 'Fashion', 'Makanan', 'Aksesoris', 'Lainnya'] as $cat)
            <a href="/?category={{ $cat }}" class="px-4 py-1.5 rounded-full text-xs font-semibold {{ request('category') == $cat ? 'bg-indigo-600 text-white shadow-sm' : 'bg-white text-gray-600 border border-gray-200 hover:bg-gray-50' }}">
                {{ $cat }}
            </a>
        @endforeach
    </div>

    <!-- Grid Produk ala Shopee -->
    <div>
        <h2 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
            <i class="fa-solid fa-bag-shopping text-indigo-600"></i> Produk Terbaru
        </h2>

        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4">
            @forelse($products as $product)
                <a href="{{ route('products.show', $product->id) }}" class="group bg-white rounded-2xl overflow-hidden border border-gray-100 shadow-sm hover:shadow-md hover:-translate-y-1 transition duration-200 flex flex-col justify-between">
                    <div>
                        <!-- Gambar Produk -->
                        <div class="aspect-square bg-gray-100 relative overflow-hidden">
                            @if($product->image)
                                <img src="{{ $product->image }}" alt="{{ $product->name }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-gray-300">
                                    <i class="fa-solid fa-image text-4xl"></i>
                                </div>
                            @endif
                            <span class="absolute top-2 left-2 bg-indigo-600/90 text-white text-[10px] font-bold px-2 py-0.5 rounded-full backdrop-blur-sm">
                                {{ $product->category }}
                            </span>
                        </div>

                        <!-- Info Produk (Nama & Harga) -->
                        <div class="p-3">
                            <h3 class="text-xs font-semibold text-gray-800 line-clamp-2 min-h-[32px] group-hover:text-indigo-600 transition">
                                {{ $product->name }}
                            </h3>
                            <div class="mt-2 flex items-baseline justify-between">
                                <span class="text-sm font-extrabold text-indigo-600">
                                    Rp{{ number_format($product->price, 0, ',', '.') }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="p-3 pt-0 text-[11px] text-gray-400 flex items-center justify-between">
                        <span>Stok: {{ $product->stock }}</span>
                        <span class="text-indigo-600 font-semibold group-hover:underline">Beli <i class="fa-solid fa-chevron-right text-[9px]"></i></span>
                    </div>
                </a>
            @empty
                <div class="col-span-full bg-white p-12 text-center rounded-2xl shadow-sm border border-gray-100">
                    <i class="fa-solid fa-box-open text-4xl text-gray-300 mb-2"></i>
                    <p class="text-gray-500 font-medium text-sm">Tidak ada produk ditemukan.</p>
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection