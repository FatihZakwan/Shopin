<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    // Menampilkan halaman formulir checkout
    public function index(Request $request)
    {
        $selectedIds = $request->input('selected_items', []);

        // Jika tidak ada item terpilih dari form, redirect kembali
        if (empty($selectedIds)) {
            return redirect()->route('cart.index')->with('error', 'Pilih minimal satu produk untuk di-checkout.');
        }

        // Ambil data keranjang dari DB berdasarkan item yang dicentang
        $cartItems = Cart::with('product')
            ->where('user_id', Auth::id())
            ->whereIn('id', $selectedIds)
            ->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Produk di keranjang tidak ditemukan.');
        }

        $total = $cartItems->sum(function ($item) {
            return $item->product->price * $item->quantity;
        });

        return view('checkout.index', compact('cartItems', 'total', 'selectedIds'));
    }

    // Memproses pembuatan order & pembayaran
    public function store(Request $request)
    {
        $request->validate([
            'customer_name'  => 'required|string|max:255',
            'customer_email' => 'required|email|max:255',
            'phone'          => 'required|string|max:20',
            'address'        => 'required|string',
            'payment_method' => 'required|string',
            'cart_ids'       => 'required|array', // Menerima array ID item keranjang
            'cart_ids.*'     => 'exists:carts,id',
        ]);

        // Ambil item dari database
        $cartItems = Cart::with('product')
            ->where('user_id', Auth::id())
            ->whereIn('id', $request->cart_ids)
            ->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Keranjang belanja kamu kosong atau item tidak valid!');
        }

        // Hitung total harga
        $total = $cartItems->sum(function ($item) {
            return $item->product->price * $item->quantity;
        });

        // Database Transaction untuk menjamin konsistensi data
        DB::beginTransaction();

        try {
            // 1. Buat Data Order
            $order = Order::create([
                'user_id'        => Auth::id(),
                'invoice'        => 'INV-' . strtoupper(Str::random(8)),
                'total'          => $total,
                'status'         => 'UNPAID',
                'customer_name'  => $request->customer_name,
                'customer_email' => $request->customer_email,
                'phone'          => $request->phone,
                'address'        => $request->address,
            ]);

            // 2. Buat Item Order & Potong Stok Produk
            foreach ($cartItems as $item) {
                $product = $item->product;

                if (!$product || $product->stock < $item->quantity) {
                    DB::rollBack();
                    return back()->with('error', 'Stok untuk ' . ($product->name ?? 'Produk') . ' tidak mencukupi.');
                }

                OrderItem::create([
                    'order_id'     => $order->id,
                    'product_id'   => $product->id,
                    'product_name' => $product->name,
                    'price'        => $product->price,
                    'quantity'     => $item->quantity,
                    'subtotal'     => $product->price * $item->quantity,
                ]);

                // Kurangi stok produk
                $product->decrement('stock', $item->quantity);
            }

            // 3. Buat Record Pembayaran (Simulasi)
            Payment::create([
                'order_id'  => $order->id,
                'method'    => $request->payment_method,
                'reference' => 'PAY-' . time(),
                'amount'    => $total,
                'status'    => 'UNPAID',
            ]);

            // 4. Hapus item terpilih dari tabel carts
            Cart::whereIn('id', $request->cart_ids)
                ->where('user_id', Auth::id())
                ->delete();

            DB::commit();

            return redirect()->route('checkout.success', $order->id)->with('success', 'Pesanan berhasil dibuat!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan saat membuat pesanan: ' . $e->getMessage());
        }
    }

    // Halaman sukses & Instruksi Pembayaran
    public function success($id)
    {
        $order = Order::with(['items', 'payment'])->where('user_id', Auth::id())->findOrFail($id);
        return view('checkout.success', compact('order'));
    }

    // Simulasi Bayar Sekarang (Ubah status jadi PAID)
    public function payNow($id)
    {
        $order = Order::where('user_id', Auth::id())->findOrFail($id);

        $order->update(['status' => 'PAID']);
        if ($order->payment) {
            $order->payment->update(['status' => 'PAID']);
        }

        return back()->with('success', 'Pembayaran berhasil dikonfirmasi! Pesanan Anda sedang diproses.');
    }
}