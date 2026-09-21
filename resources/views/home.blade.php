@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto space-y-6">

    <!-- Banner -->
    <div class="bg-gradient-to-r from-[#2196F3] via-[#90CAF9] to-[#0D47A1] rounded-3xl p-8 text-white shadow-lg text-center relative overflow-hidden">
        <div class="relative z-10">
            <h1 class="text-3xl font-extrabold tracking-tight mb-2 text-white">BELANJA MUDAH DI SHOPIN</h1>
            <p class="text-[#E3F2FD] text-sm font-medium">Temukan berbagai produk pilihan berkualitas dengan harga terbaik!</p>
        </div>
    </div>

    <!-- Filter Kategori -->
    <div class="flex items-center gap-2 overflow-x-auto pb-2">
        <span class="text-xs font-bold uppercase text-[#0D47A1] mr-2 flex items-center gap-1">
            <i class="fa-solid fa-filter"></i> Kategori:
        </span>
        <a href="/" class="px-4 py-1.5 rounded-full text-xs font-semibold {{ !request('category') ? 'bg-[#2196F3] text-white shadow-sm' : 'bg-white text-[#0D47A1] border border-[#90CAF9] hover:bg-[#E3F2FD]' }}">
            Semua
        </a>
        @foreach(['Elektronik', 'Fashion', 'Makanan', 'Aksesoris', 'Lainnya'] as $cat)
            <a href="/?category={{ $cat }}" class="px-4 py-1.5 rounded-full text-xs font-semibold {{ request('category') == $cat ? 'bg-[#2196F3] text-white shadow-sm' : 'bg-white text-[#0D47A1] border border-[#90CAF9] hover:bg-[#E3F2FD]' }}">
                {{ $cat }}
            </a>
        @endforeach
    </div>

    <!-- Grid Produk -->
    <div>
        <h2 class="text-lg font-bold text-[#0D47A1] mb-4 flex items-center gap-2">
            <i class="fa-solid fa-bag-shopping text-[#2196F3]"></i> Produk Terbaru
        </h2>

        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4">
            @forelse($products as $product)
                <a href="{{ route('products.show', $product->id) }}" class="group bg-white rounded-2xl overflow-hidden border border-[#E3F2FD] shadow-sm hover:shadow-md hover:-translate-y-1 transition duration-200 flex flex-col justify-between">
                    <div>
                        <!-- Gambar Produk -->
                        <div class="aspect-square bg-[#E3F2FD] relative overflow-hidden">
                            @if($product->image)
                                <img src="{{ $product->image }}" alt="{{ $product->name }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-[#90CAF9]">
                                    <i class="fa-solid fa-image text-4xl"></i>
                                </div>
                            @endif
                            <span class="absolute top-2 left-2 bg-[#2196F3]/90 text-white text-[10px] font-bold px-2 py-0.5 rounded-full backdrop-blur-sm">
                                {{ $product->category }}
                            </span>
                        </div>

                        <!-- Info Produk -->
                        <div class="p-3">
                            <h3 class="text-xs font-semibold text-[#0D47A1] line-clamp-2 min-h-[32px] group-hover:text-[#2196F3] transition">
                                {{ $product->name }}
                            </h3>
                            <div class="mt-2 flex items-baseline justify-between">
                                <span class="text-sm font-extrabold text-[#2196F3]">
                                    Rp{{ number_format($product->price, 0, ',', '.') }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="p-3 pt-0 text-[11px] text-[#0D47A1] flex items-center justify-between">
                        <span>Stok: {{ $product->stock }}</span>
                        <span class="text-[#2196F3] font-semibold group-hover:underline">Beli <i class="fa-solid fa-chevron-right text-[9px]"></i></span>
                    </div>
                </a>
            @empty
                <div class="col-span-full bg-white p-12 text-center rounded-2xl shadow-sm border border-[#E3F2FD]">
                    <i class="fa-solid fa-box-open text-4xl text-[#90CAF9] mb-2"></i>
                    <p class="text-[#0D47A1] font-medium text-sm">Tidak ada produk ditemukan.</p>
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection