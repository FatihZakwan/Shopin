@extends('layouts.app')

@section('content')
<!-- Chart.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    
    <!-- Title & Action -->
    <div class="flex flex-col md:flex-row md:items-center justify-between mb-8 gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Dashboard Admin SHOPIN</h1>
            <p class="text-gray-500 text-sm">Kelola produk, pengguna, dan pantau grafik pendapatan toko Anda.</p>
        </div>
        <a href="{{ route('admin.products.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold px-4 py-2.5 rounded-lg shadow flex items-center gap-2 w-fit transition">
            ➕ Tambah Produk Baru
        </a>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-6 shadow-sm">
            {{ session('success') }}
        </div>
    @endif

    <!-- 1. KARTU STATISTIK -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Total Produk -->
        <div class="bg-indigo-600 text-white rounded-xl shadow-md p-5 flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold uppercase tracking-wider opacity-80">Total Produk</p>
                <h3 class="text-3xl font-extrabold mt-1">{{ $totalProducts }}</h3>
            </div>
            <div class="text-4xl opacity-80">📦</div>
        </div>

        <!-- Total User -->
        <div class="bg-emerald-600 text-white rounded-xl shadow-md p-5 flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold uppercase tracking-wider opacity-80">Total Pengguna</p>
                <h3 class="text-3xl font-extrabold mt-1">{{ $totalUsers }}</h3>
            </div>
            <div class="text-4xl opacity-80">👥</div>
        </div>

        <!-- Total Pesanan -->
        <div class="bg-amber-500 text-white rounded-xl shadow-md p-5 flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold uppercase tracking-wider opacity-80">Total Pesanan</p>
                <h3 class="text-3xl font-extrabold mt-1">{{ $totalOrders }}</h3>
            </div>
            <div class="text-4xl opacity-80">🛒</div>
        </div>

        <!-- Total Pendapatan -->
        <div class="bg-purple-600 text-white rounded-xl shadow-md p-5 flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold uppercase tracking-wider opacity-80">Total Penghasilan</p>
                <h3 class="text-2xl font-extrabold mt-1">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</h3>
            </div>
            <div class="text-4xl opacity-80">💰</div>
        </div>
    </div>

    <!-- 2. GRAFIK STATISTIK PENGHASILAN -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-8">
        <h2 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
            📈 Grafik Penghasilan Toko (Tahun {{ date('Y') }})
        </h2>
        <div class="h-72 w-full">
            <canvas id="revenueChart"></canvas>
        </div>
    </div>

    <!-- 3. TABEL MANAJEMEN PRODUK -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-8">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-lg font-bold text-gray-800 flex items-center gap-2">
                🛍️ Daftar Produk Shopin
            </h2>
            <span class="text-xs text-gray-500">Menampilkan produk terbaru</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 border-b text-gray-600 text-xs uppercase font-semibold">
                        <th class="py-3 px-4">Gambar</th>
                        <th class="py-3 px-4">Nama Produk</th>
                        <th class="py-3 px-4">Harga</th>
                        <th class="py-3 px-4">Stok</th>
                        <th class="py-3 px-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-sm">
                    @forelse($products as $prod)
                        @php
                            $img = str_starts_with($prod->image, 'http') 
                                ? $prod->image 
                                : asset('storage/' . $prod->image);
                        @endphp
                        <tr class="hover:bg-gray-50">
                            <td class="py-3 px-4">
                                <img src="{{ $img }}" class="w-12 h-12 object-cover rounded-lg border" onerror="this.src='https://via.placeholder.com/100'">
                            </td>
                            <td class="py-3 px-4 font-semibold text-gray-800">{{ $prod->name }}</td>
                            <td class="py-3 px-4 text-indigo-600 font-medium">Rp {{ number_format($prod->price, 0, ',', '.') }}</td>
                            <td class="py-3 px-4">
                                <span class="px-2.5 py-1 rounded-full text-xs font-semibold {{ $prod->stock > 0 ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                    {{ $prod->stock }} unit
                                </span>
                            </td>
                            <td class="py-3 px-4 text-center space-x-2">
                                <a href="{{ route('admin.products.edit', $prod->id) }}" class="bg-amber-100 text-amber-700 hover:bg-amber-200 px-3 py-1 rounded text-xs font-semibold transition">
                                    ✏️ Edit
                                </a>
                                <form action="{{ route('admin.products.destroy', $prod->id) }}" method="POST" class="inline" onsubmit="return confirm('Yakin hapus produk ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="bg-red-100 text-red-700 hover:bg-red-200 px-3 py-1 rounded text-xs font-semibold transition">
                                        🗑️ Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-6 text-gray-500">Belum ada produk.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- 4. TABEL DATA PENGGUNA -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <h2 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
            👤 Data Pengguna Terdaftar
        </h2>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 border-b text-gray-600 text-xs uppercase font-semibold">
                        <th class="py-3 px-4">Nama</th>
                        <th class="py-3 px-4">Email</th>
                        <th class="py-3 px-4">Role</th>
                        <th class="py-3 px-4">Tanggal Bergabung</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-sm">
                    @foreach($users as $user)
                        <tr class="hover:bg-gray-50">
                            <td class="py-3 px-4 font-medium text-gray-800">{{ $user->name }}</td>
                            <td class="py-3 px-4 text-gray-600">{{ $user->email }}</td>
                            <td class="py-3 px-4">
                                <span class="px-2.5 py-1 rounded-full text-xs font-semibold {{ strtoupper($user->role) === 'ADMIN' ? 'bg-purple-100 text-purple-700' : 'bg-blue-100 text-blue-700' }}">
                                    {{ strtoupper($user->role ?? 'USER') }}
                                </span>
                            </td>
                            <td class="py-3 px-4 text-gray-500">
                                {{ $user->created_at ? $user->created_at->format('d M Y') : '-' }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Script Render Chart.js -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const ctx = document.getElementById('revenueChart').getContext('2d');
        const chartData = @json($chartData);

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'],
                datasets: [{
                    label: 'Penghasilan (Rp)',
                    data: chartData,
                    borderColor: '#4F46E5',
                    backgroundColor: 'rgba(79, 70, 229, 0.1)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.3,
                    pointBackgroundColor: '#4F46E5',
                    pointRadius: 5
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return 'Rp ' + new Intl.NumberFormat('id-ID').format(value);
                            }
                        }
                    }
                }
            }
        });
    });
</script>
@endsection