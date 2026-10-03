<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Withdrawal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WithdrawalAdminController extends Controller
{
    public function index()
    {
        $withdrawals = Withdrawal::with('user')->latest()->paginate(10);
        return view('admin.withdrawals.index', compact('withdrawals'));
    }

    public function approve($id)
    {
        $withdrawal = Withdrawal::findOrFail($id);

        if ($withdrawal->status !== 'pending') {
            return back()->with('error', 'Pengajuan ini sudah diproses.');
        }

        $withdrawal->update(['status' => 'approved']);

        return back()->with('success', 'Pengajuan berhasil disetujui.');
    }

    public function reject(Request $request, $id)
    {
        $request->validate([
            'notes' => 'required|string|max:255',
        ]);

        $withdrawal = Withdrawal::findOrFail($id);

        if ($withdrawal->status !== 'pending') {
            return back()->with('error', 'Pengajuan ini sudah diproses.');
        }

        DB::transaction(function () use ($withdrawal, $request) {
            // Ubah status jadi rejected & simpan alasan
            $withdrawal->update([
                'status' => 'rejected',
                'notes' => $request->notes,
            ]);

            // Refund/kembalikan saldo ke akun user
            $withdrawal->user->increment('balance', $withdrawal->amount);
        });

        return back()->with('success', 'Pengajuan ditolak dan saldo berhasil dikembalikan ke pengguna.');
    }
}