@extends('layouts.app')

@section('content')
<!-- Import Chart.js dan Alpine.js untuk Grafik & Modal -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

<div class="max-w-6xl mx-auto px-4 py-6" x-data="{ openAddModal: false, openEditModal: false, editUser: {} }">
    
    <!-- Alert Notifikasi Sukses -->
    @if(session('success'))
        <div class="mb-4 p-4 bg-blue-100 border-l-4 border-[#2196F3] text-[#0D47A1] rounded shadow-sm flex justify-between items-center">
            <span>{{ session('success') }}</span>
            <button onclick="this.parentElement.remove()" class="font-bold text-lg">&times;</button>
        </div>
    @endif

    <!-- Header Dashboard -->
    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 mb-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-2xl font-bold text-[#0D47A1]">Dashboard Admin</h1>
            <p class="text-gray-500 text-sm">Selamat datang kembali, <span class="font-semibold text-gray-700">{{ Auth::user()->name }}</span>!</p>
        </div>
        <button @click="openAddModal = true" class="bg-[#2196F3] hover:bg-[#0D47A1] text-white px-5 py-2.5 rounded-xl font-semibold text-sm transition shadow-sm">
            + Tambah User Baru
        </button>
    </div>

    <!-- Ringkasan Statistik (Kartu Produk Dihilangkan) -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <!-- Total Pengguna -->
        <div class="bg-gradient-to-r from-[#2196F3] to-[#0D47A1] text-white p-6 rounded-2xl shadow-md">
            <p class="text-xs font-semibold uppercase tracking-wider text-blue-100">Total Pengguna</p>
            <h2 class="text-3xl font-extrabold mt-2">{{ $totalUsers ?? \App\Models\User::count() }}</h2>
        </div>
        
        <!-- Total Siswa / User Biasa -->
        <div class="bg-gradient-to-r from-[#90CAF9] to-[#2196F3] text-white p-6 rounded-2xl shadow-md">
            <p class="text-xs font-semibold uppercase tracking-wider text-blue-900">Total Siswa / User</p>
            <h2 class="text-3xl font-extrabold mt-2 text-blue-950">{{ $totalSiswa ?? \App\Models\User::where('role', '!=', 'Admin')->count() }}</h2>
        </div>

        <!-- Total Pesanan -->
        <div class="bg-amber-500 text-white p-6 rounded-2xl shadow-md">
            <p class="text-xs font-semibold uppercase tracking-wider text-amber-100">Total Pesanan</p>
            <h2 class="text-3xl font-extrabold mt-2">{{ \App\Models\Order::count() }}</h2>
        </div>
    </div>

    <!-- Grafik Statistik Pengguna -->
    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 mb-8">
        <h3 class="text-lg font-bold text-[#0D47A1] mb-4">📊 Grafik Distribusi Peran Pengguna</h3>
        <div class="w-full h-64 flex justify-center">
            <canvas id="userChart"></canvas>
        </div>
    </div>

    <!-- Tabel Kelola Data Pengguna (CRUD) -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-6 border-b border-gray-100 flex justify-between items-center">
            <h3 class="text-lg font-bold text-[#0D47A1]">📋 Kelola Data Pengguna</h3>
            <span class="text-xs bg-[#E3F2FD] text-[#0D47A1] font-semibold px-3 py-1 rounded-full">
                Total: {{ $users->count() ?? \App\Models\User::count() }} User
            </span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-[#E3F2FD] text-[#0D47A1] text-xs font-bold uppercase tracking-wider">
                        <th class="p-4">ID</th>
                        <th class="p-4">Nama</th>
                        <th class="p-4">Email</th>
                        <th class="p-4">Role</th>
                        <th class="p-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-sm text-gray-700">
                    @php 
                        $userList = $users ?? \App\Models\User::all(); 
                    @endphp
                    @foreach($userList as $user)
                    <tr class="hover:bg-[#E3F2FD]/30 transition">
                        <td class="p-4 font-semibold text-gray-500">#{{ $user->id }}</td>
                        <td class="p-4 font-bold text-gray-800">{{ $user->name }}</td>
                        <td class="p-4">{{ $user->email }}</td>
                        <td class="p-4">
                            <span class="px-3 py-1 text-xs font-semibold rounded-full {{ strtolower($user->role) === 'admin' ? 'bg-indigo-100 text-[#0D47A1]' : 'bg-blue-100 text-[#2196F3]' }}">
                                {{ ucfirst($user->role) }}
                            </span>
                        </td>
                        <td class="p-4 text-center space-x-2">
                            <!-- Tombol Edit -->
                            <button @click="openEditModal = true; editUser = {{ json_encode($user) }}" 
                                class="bg-amber-500 hover:bg-amber-600 text-white px-3 py-1.5 rounded-lg text-xs font-medium transition shadow-sm">
                                Edit
                            </button>
                            
                            <!-- Tombol Hapus -->
                            <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus user ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-3 py-1.5 rounded-lg text-xs font-medium transition shadow-sm">
                                    Hapus
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal Tambah User -->
    <div x-show="openAddModal" class="fixed inset-0 bg-black/50 flex items-center justify-center p-4 z-50" x-cloak>
        <div class="bg-white rounded-2xl max-w-md w-full p-6 shadow-xl" @click.away="openAddModal = false">
            <h3 class="text-lg font-bold text-[#0D47A1] mb-4">Tambah User Baru</h3>
            <form action="{{ route('admin.users.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label class="block text-xs font-bold text-gray-700 mb-1">Nama</label>
                    <input type="text" name="name" required class="w-full border border-gray-300 rounded-lg p-2.5 text-sm focus:outline-none focus:border-[#2196F3]">
                </div>
                <div class="mb-3">
                    <label class="block text-xs font-bold text-gray-700 mb-1">Email</label>
                    <input type="email" name="email" required class="w-full border border-gray-300 rounded-lg p-2.5 text-sm focus:outline-none focus:border-[#2196F3]">
                </div>
                <div class="mb-3">
                    <label class="block text-xs font-bold text-gray-700 mb-1">Password</label>
                    <input type="password" name="password" required class="w-full border border-gray-300 rounded-lg p-2.5 text-sm focus:outline-none focus:border-[#2196F3]">
                </div>
                <div class="mb-4">
                    <label class="block text-xs font-bold text-gray-700 mb-1">Role</label>
                    <select name="role" class="w-full border border-gray-300 rounded-lg p-2.5 text-sm focus:outline-none focus:border-[#2196F3]">
                        <option value="USER">USER / Siswa</option>
                        <option value="Admin">Admin</option>
                    </select>
                </div>
                <div class="flex justify-end gap-2">
                    <button type="button" @click="openAddModal = false" class="px-4 py-2 bg-gray-100 text-gray-700 text-xs font-bold rounded-lg hover:bg-gray-200">Batal</button>
                    <button type="submit" class="px-4 py-2 bg-[#2196F3] text-white text-xs font-bold rounded-lg hover:bg-[#0D47A1] transition">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Edit User -->
    <div x-show="openEditModal" class="fixed inset-0 bg-black/50 flex items-center justify-center p-4 z-50" x-cloak>
        <div class="bg-white rounded-2xl max-w-md w-full p-6 shadow-xl" @click.away="openEditModal = false">
            <h3 class="text-lg font-bold text-[#0D47A1] mb-4">Edit Data User</h3>
            <form :action="'/admin/users/' + editUser.id" method="POST">
                @csrf
                @method('PUT')
                <div class="mb-3">
                    <label class="block text-xs font-bold text-gray-700 mb-1">Nama</label>
                    <input type="text" name="name" x-model="editUser.name" required class="w-full border border-gray-300 rounded-lg p-2.5 text-sm focus:outline-none focus:border-[#2196F3]">
                </div>
                <div class="mb-3">
                    <label class="block text-xs font-bold text-gray-700 mb-1">Email</label>
                    <input type="email" name="email" x-model="editUser.email" required class="w-full border border-gray-300 rounded-lg p-2.5 text-sm focus:outline-none focus:border-[#2196F3]">
                </div>
                <div class="mb-3">
                    <label class="block text-xs font-bold text-gray-700 mb-1">Password Baru (Opsional)</label>
                    <input type="password" name="password" placeholder="Kosongkan jika tidak diubah" class="w-full border border-gray-300 rounded-lg p-2.5 text-sm focus:outline-none focus:border-[#2196F3]">
                </div>
                <div class="mb-4">
                    <label class="block text-xs font-bold text-gray-700 mb-1">Role</label>
                    <select name="role" x-model="editUser.role" class="w-full border border-gray-300 rounded-lg p-2.5 text-sm focus:outline-none focus:border-[#2196F3]">
                        <option value="USER">USER / Siswa</option>
                        <option value="Admin">Admin</option>
                    </select>
                </div>
                <div class="flex justify-end gap-2">
                    <button type="button" @click="openEditModal = false" class="px-4 py-2 bg-gray-100 text-gray-700 text-xs font-bold rounded-lg hover:bg-gray-200">Batal</button>
                    <button type="submit" class="px-4 py-2 bg-[#2196F3] text-white text-xs font-bold rounded-lg hover:bg-[#0D47A1] transition">Update</button>
                </div>
            </form>
        </div>
    </div>

</div>

<!-- Script Render Grafik Chart.js -->
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const ctx = document.getElementById('userChart').getContext('2d');
        
        // Menghitung statistik role
        const totalAdmin = {{ \App\Models\User::where('role', 'Admin')->count() }};
        const totalUser = {{ \App\Models\User::where('role', '!=', 'Admin')->count() }};

        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: ['Admin', 'Siswa / User'],
                datasets: [{
                    data: [totalAdmin, totalUser],
                    backgroundColor: ['#0D47A1', '#2196F3'],
                    hoverOffset: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });
    });
</script>
@endsection