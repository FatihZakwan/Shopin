@extends('layouts.app')

@section('content')
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Super Admin Dashboard</h1>
            <p class="text-sm text-gray-500">Kelola seluruh pengguna dan hak akses di Shopin</p>
        </div>
        <div class="bg-indigo-50 text-indigo-700 px-4 py-2 rounded-xl text-sm font-semibold flex items-center gap-2">
            <i class="fa-solid fa-users"></i> Total User: {{ $users->count() }}
        </div>
    </div>

    <!-- Tabel Daftar User -->
    <div class="overflow-x-auto rounded-xl border border-gray-200">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50 text-gray-600 text-xs uppercase tracking-wider border-b border-gray-200">
                    <th class="p-4">Nama</th>
                    <th class="p-4">Email</th>
                    <th class="p-4">Email Notifikasi</th>
                    <th class="p-4">Role Saat Ini</th>
                    <th class="p-4 text-center">Aksi (Ubah Role & Hapus)</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 text-sm text-gray-700">
                @foreach($users as $user)
                <tr class="hover:bg-gray-50/50 transition">
                    <td class="p-4 font-semibold text-gray-800">
                        {{ $user->name }}
                        @if($user->id === auth()->id())
                            <span class="ml-2 text-xs bg-emerald-100 text-emerald-700 font-bold px-2 py-0.5 rounded-full">Anda</span>
                        @endif
                    </td>
                    <td class="p-4 text-gray-600">{{ $user->email }}</td>
                    <td class="p-4 text-gray-600">{{ $user->email_notifikasi }}</td>
                    <td class="p-4">
                        @if(strtolower($user->role) === 'super_admin')
                            <span class="bg-red-100 text-red-700 font-bold text-xs px-3 py-1 rounded-full inline-flex items-center gap-1">
                                <i class="fa-solid fa-shield-halved text-xs"></i> Super Admin
                            </span>
                        @elseif(strtolower($user->role) === 'admin')
                            <span class="bg-indigo-100 text-indigo-700 font-bold text-xs px-3 py-1 rounded-full inline-flex items-center gap-1">
                                <i class="fa-solid fa-user-gear text-xs"></i> Admin Toko
                            </span>
                        @else
                            <span class="bg-gray-100 text-gray-600 font-bold text-xs px-3 py-1 rounded-full inline-flex items-center gap-1">
                                <i class="fa-solid fa-user text-xs"></i> User / Pembeli
                            </span>
                        @endif
                    </td>
                    <td class="p-4 text-center">
                        @if($user->id !== auth()->id())
                            <div class="flex items-center justify-center gap-2">
                                <!-- Form Simpan Role -->
<form action="{{ route('superadmin.users.updateRole', $user->id) }}" method="POST" class="flex items-center gap-1">
    @csrf
    @method('PATCH')
    <select name="role" class="text-xs bg-gray-50 border border-gray-300 rounded-lg px-2 py-1.5 focus:outline-none focus:ring-2 focus:ring-indigo-500">
        <option value="user" {{ strtolower($user->role) === 'user' ? 'selected' : '' }}>User</option>
        <option value="admin" {{ strtolower($user->role) === 'admin' ? 'selected' : '' }}>Admin Toko</option>
    </select>
    <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white text-xs px-3 py-1.5 rounded-lg transition font-medium flex items-center gap-1">
        <i class="fa-solid fa-floppy-disk"></i> Simpan
    </button>
</form>

                                <!-- Form Hapus User -->
                                <form action="{{ route('superadmin.users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus user ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="bg-red-500 hover:bg-red-600 text-white text-xs px-3 py-1.5 rounded-lg transition font-medium flex items-center gap-1">
                                        <i class="fa-solid fa-trash"></i> Hapus
                                    </button>
                                </form>
                            </div>
                        @else
                            <span class="text-xs text-gray-400 italic">Tidak dapat diubah <i>*Given</i></span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection