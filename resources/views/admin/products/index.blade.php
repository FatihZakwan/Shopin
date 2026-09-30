@extends('layouts.app')

@section('content')
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Kelola Produk Toko</h1>
            <p class="text-sm text-gray-500">Tambah, ubah, atau hapus produk yang dijual di Shopin</p>
        </div>
        <div>
            <a href="{{ route('admin.products.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold px-4 py-2 rounded-xl inline-flex items-center gap-2 transition">
                <i class="fa-solid fa-plus"></i> Tambah Produk Baru
            </a>
        </div>
    </div>

    <!-- Tabel Produk -->
    <div class="overflow-x-auto rounded-xl border border-gray-200">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50 text-gray-600 text-xs uppercase tracking-wider border-b border-gray-200">
                    <th class="p-4">Gambar</th>
                    <th class="p-4">Nama Produk</th>
                    <th class="p-4">Kategori</th>
                    <th class="p-4">Harga</th>
                    <th class="p-4">Stok</th>
                    <th class="p-4 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 text-sm text-gray-700">
                @forelse($products as $product)
                <tr class="hover:bg-gray-50/50 transition">
                    <td class="p-4">
                        @if($product->image)
                            <img src="{{ Str::startsWith($product->image, 'http') ? $product->image : asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="w-12 h-12 object-cover rounded-lg border border-gray-200">
                        @else
                            <div class="w-12 h-12 bg-gray-100 text-gray-400 rounded-lg flex items-center justify-center text-xs">No Image</div>
                        @endif
                    </td>
                    <td class="p-4 font-semibold text-gray-800">{{ $product->name }}</td>
                    <td class="p-4">
                        <span class="bg-indigo-50 text-indigo-700 font-semibold text-xs px-2.5 py-1 rounded-md">
                            {{ $product->category }}
                        </span>
                    </td>
                    <td class="p-4 font-semibold text-emerald-600">
                        Rp {{ number_format($product->price, 0, ',', '.') }}
                    </td>
                    <td class="p-4">
                        <span class="font-medium {{ $product->stock < 5 ? 'text-red-500 font-bold' : 'text-gray-700' }}">
                            {{ $product->stock }}
                        </span>
                    </td>
                    <td class="p-4 text-center">
                        <div class="flex items-center justify-center gap-2">
                            <a href="{{ route('admin.products.edit', $product->id) }}" class="bg-amber-500 hover:bg-amber-600 text-white text-xs px-2.5 py-1.5 rounded-lg transition">
                                <i class="fa-solid fa-pen-to-square"></i> Edit
                            </a>
                            <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus produk ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bg-red-500 hover:bg-red-600 text-white text-xs px-2.5 py-1.5 rounded-lg transition">
                                    <i class="fa-solid fa-trash"></i> Hapus
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="p-8 text-center text-gray-400">
                        <i class="fa-solid fa-box-open text-3xl mb-2 block"></i>
                        Belum ada produk. Silakan tambahkan produk baru.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @if(method_exists($products, 'links'))
        <div class="mt-4">
            {{ $products->links() }}
        </div>
    @endif
</div>
@endsection