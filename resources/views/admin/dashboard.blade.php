@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6 space-y-6">
    <!-- Header Admin -->
    <div class="flex justify-between items-center bg-white p-6 rounded-xl shadow-sm border border-gray-100">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Dashboard Panel Admin</h1>
            <p class="text-gray-500 text-sm">Kelola data pengguna, produk, dan pantaulah statistik penjualan.</p>
        </div>
        <button class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium text-sm flex items-center space-x-2 shadow">
            <i class="fas fa-plus"></i>
            <span>Tambah Data Baru</span>
        </button>
    </div>

    <!-- 1. Ringkasan Kartu Statistik (Cards) -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100 flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Total Pengguna</p>
                <h3 class="text-2xl font-bold text-gray-800 mt-1">{{ $stats['total_users'] }}</h3>
            </div>
            <div class="p-3 bg-blue-50 text-blue-600 rounded-lg">
                <i class="fas fa-users text-xl"></i>
            </div>
        </div>

        <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100 flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Total Produk</p>
                <h3 class="text-2xl font-bold text-gray-800 mt-1">{{ $stats['total_products'] }}</h3>
            </div>
            <div class="p-3 bg-green-50 text-green-600 rounded-lg">
                <i class="fas fa-box text-xl"></i>
            </div>
        </div>

        <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100 flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Total Pesanan</p>
                <h3 class="text-2xl font-bold text-gray-800 mt-1">{{ $stats['total_orders'] }}</h3>
            </div>
            <div class="p-3 bg-yellow-50 text-yellow-600 rounded-lg">
                <i class="fas fa-shopping-bag text-xl"></i>
            </div>
        </div>

        <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100 flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Total Pendapatan</p>
                <h3 class="text-2xl font-bold text-gray-800 mt-1">Rp {{ number_format($stats['total_revenue'], 0, ',', '.') }}</h3>
            </div>
            <div class="p-3 bg-purple-50 text-purple-600 rounded-lg">
                <i class="fas fa-wallet text-xl"></i>
            </div>
        </div>
    </div>

    <!-- 2. Area Grafik Penjualan -->
    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
        <h2 class="text-lg font-bold text-gray-800 mb-4">Grafik Penjualan Bulanan</h2>
        <div class="h-64">
            <canvas id="salesChart"></canvas>
        </div>
    </div>

    <!-- 3. Tabel Kelola Data Pengguna (CRUD) -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-5 border-b border-gray-100 flex justify-between items-center">
            <h2 class="text-lg font-bold text-gray-800">Data Pengguna & Akses</h2>
            <input type="text" placeholder="Cari user..." class="px-3 py-1.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 text-gray-500 text-xs font-semibold uppercase tracking-wider">
                        <th class="p-4">ID</th>
                        <th class="p-4">Nama</th>
                        <th class="p-4">Email</th>
                        <th class="p-4">Role</th>
                        <th class="p-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-sm text-gray-700">
                    <tr class="hover:bg-gray-50">
                        <td class="p-4 font-medium">#1</td>
                        <td class="p-4">Admin System</td>
                        <td class="p-4">admin@shopin.com</td>
                        <td class="p-4"><span class="bg-blue-100 text-blue-700 px-2 py-1 rounded text-xs font-medium">Admin</span></td>
                        <td class="p-4 text-center space-x-2">
                            <button class="text-blue-600 hover:text-blue-800 font-medium text-xs"><i class="fas fa-edit"></i> Edit</button>
                            <button class="text-red-600 hover:text-red-800 font-medium text-xs"><i class="fas fa-trash"></i> Hapus</button>
                        </td>
                    </tr>
                    <tr class="hover:bg-gray-50">
                        <td class="p-4 font-medium">#2</td>
                        <td class="p-4">User Pelanggan</td>
                        <td class="p-4">user@gmail.com</td>
                        <td class="p-4"><span class="bg-gray-100 text-gray-700 px-2 py-1 rounded text-xs font-medium">Pelanggan</span></td>
                        <td class="p-4 text-center space-x-2">
                            <button class="text-blue-600 hover:text-blue-800 font-medium text-xs"><i class="fas fa-edit"></i> Edit</button>
                            <button class="text-red-600 hover:text-red-800 font-medium text-xs"><i class="fas fa-trash"></i> Hapus</button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Script Chart.js untuk Grafik -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('salesChart').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep'],
            datasets: [{
                label: 'Penjualan (Rp)',
                data: [1200000, 1900000, 3000000, 5000000, 2000000, 3000000, 4500000, 6000000, 8000000],
                borderColor: '#2563eb',
                backgroundColor: 'rgba(37, 99, 235, 0.1)',
                fill: true,
                tension: 0.3
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false
        }
    });
</script>
@endsection