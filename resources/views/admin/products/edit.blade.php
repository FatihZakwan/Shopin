@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto px-4 py-8">
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <h1 class="text-2xl font-bold text-gray-800 mb-6">✏️ Edit Produk</h1>

        <form action="{{ route('admin.products.update', $product->id) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Nama Produk</label>
                <input type="text" name="name" value="{{ $product->name }}" required class="w-full border border-gray-300 rounded-lg p-2.5">
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Deskripsi</label>
                <textarea name="description" rows="3" required class="w-full border border-gray-300 rounded-lg p-2.5">{{ $product->description }}</textarea>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Harga (Rp)</label>
                    <input type="number" name="price" value="{{ $product->price }}" required min="0" class="w-full border border-gray-300 rounded-lg p-2.5">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Stok</label>
                    <input type="number" name="stock" value="{{ $product->stock }}" required min="0" class="w-full border border-gray-300 rounded-lg p-2.5">
                </div>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Ganti Gambar (Opsional)</label>
                <input type="file" name="image" accept="image/*" class="w-full border border-gray-300 rounded-lg p-2">
            </div>

            <div class="flex justify-end gap-3 pt-4">
                <a href="{{ route('admin.dashboard') }}" class="px-4 py-2 border rounded-lg text-gray-600 hover:bg-gray-100">Batal</a>
                <button type="submit" class="px-6 py-2 bg-indigo-600 text-white font-bold rounded-lg hover:bg-indigo-700">Update Produk</button>
            </div>
        </form>
    </div>
</div>
@endsection