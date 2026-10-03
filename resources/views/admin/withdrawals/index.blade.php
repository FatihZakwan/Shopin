@extends('layouts.app') {{-- Sesuaikan dengan nama layout admin kamu --}}

@section('content')
<div class="max-w-7xl mx-auto p-6 bg-white rounded-lg shadow-md">
    <h2 class="text-2xl font-bold mb-6 text-gray-800">Kelola Pencairan Saldo</h2>

    @if(session('success'))
        <div class="p-4 mb-4 text-sm text-green-800 bg-green-100 rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="p-4 mb-4 text-sm text-red-800 bg-red-100 rounded-lg">
            {{ session('error') }}
        </div>
    @endif

    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse border border-gray-200">
            <thead>
                <tr class="bg-gray-100 border-b border-gray-200 text-sm text-gray-700">
                    <th class="p-3">Pengguna</th>
                    <th class="p-3">Jumlah</th>
                    <th class="p-3">Rekening / Tujuan</th>
                    <th class="p-3">Status</th>
                    <th class="p-3">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 text-sm">
                @forelse ($withdrawals as $item)
                <tr>
                    <td class="p-3 font-semibold text-gray-800">{{ $item->user->name }}</td>
                    <td class="p-3 text-emerald-600 font-bold">
                        Rp {{ number_format($item->amount, 0, ',', '.') }}
                    </td>
                    <td class="p-3">
                        <span class="font-bold text-gray-700">{{ $item->bank_name }}</span> - {{ $item->account_number }}<br>
                        <span class="text-xs text-gray-500">a.n {{ $item->account_name }}</span>
                    </td>
                    <td class="p-3">
                        @if($item->status === 'pending')
                            <span class="px-2.5 py-1 bg-yellow-100 text-yellow-800 rounded-full text-xs font-medium">Pending</span>
                        @elseif($item->status === 'approved')
                            <span class="px-2.5 py-1 bg-green-100 text-green-800 rounded-full text-xs font-medium">Selesai</span>
                        @else
                            <span class="px-2.5 py-1 bg-red-100 text-red-800 rounded-full text-xs font-medium">Ditolak</span>
                            @if($item->notes)
                                <p class="text-xs text-red-500 mt-1">Ket: {{ $item->notes }}</p>
                            @endif
                        @endif
                    </td>
                    <td class="p-3">
                        @if($item->status === 'pending')
                            <div class="flex items-center gap-2">
                                {{-- Tombol Approve --}}
                                <form action="{{ route('admin.withdrawals.approve', $item->id) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" onclick="return confirm('Pastikan dana sudah ditransfer secara manual!')" class="bg-emerald-600 hover:bg-emerald-700 text-white px-3 py-1.5 rounded text-xs transition">
                                        Setujui
                                    </button>
                                </form>

                                {{-- Form Reject --}}
                                <form action="{{ route('admin.withdrawals.reject', $item->id) }}" method="POST" class="flex gap-1">
                                    @csrf
                                    @method('PATCH')
                                    <input type="text" name="notes" placeholder="Alasan tolak" class="border p-1 text-xs rounded border-gray-300" required>
                                    <button type="submit" onclick="return confirm('Tolak dan kembalikan saldo ke user?')" class="bg-red-600 hover:bg-red-700 text-white px-3 py-1.5 rounded text-xs transition">
                                        Tolak
                                    </button>
                                </form>
                            </div>
                        @else
                            <span class="text-gray-400 text-xs italic">Selesai diproses</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="p-4 text-center text-gray-500">Belum ada pengajuan pencairan saldo.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $withdrawals->links() }}
    </div>
</div>
@endsection