<?php

namespace App\Http\Controllers;

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
    public function index()
    {
        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Keranjang belanja kamu masih kosong!');
        }

        $total = 0;
        foreach ($cart as $item) {
            $total += $item['price'] * $item['quantity'];
        }

        return view('checkout.index', compact('cart', 'total'));
    }

    // Memproses pembuatan order & pembayaran
    public function store(Request $request)
    {
        $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'required|string',
            'payment_method' => 'required|string',
        ]);

        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Keranjang belanja kamu kosong!');
        }

        // Hitung ulang total
        $total = 0;
        foreach ($cart as $item) {
            $total += $item['price'] * $item['quantity'];
        }

        // Jalankan Transaction DB untuk keamanan data
        DB::beginTransaction();

        try {
            // 1. Buat Data Order
            $order = Order::create([
                'user_id' => Auth::id(),
                'invoice' => 'INV-' . strtoupper(Str::random(8)),
                'total' => $total,
                'status' => 'UNPAID',
                'customer_name' => $request->customer_name,
                'customer_email' => $request->customer_email,
                'phone' => $request->phone,
                'address' => $request->address,
            ]);

            // 2. Buat Item Order & Potong Stok Produk
            foreach ($cart as $item) {
                $product = Product::find($item['id']);

                if (!$product || $product->stock < $item['quantity']) {
                    DB::rollBack();
                    return back()->with('error', 'Stok untuk ' . $item['name'] . ' tidak mencukupi.');
                }

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['id'],
                    'product_name' => $item['name'],
                    'price' => $item['price'],
                    'quantity' => $item['quantity'],
                    'subtotal' => $item['price'] * $item['quantity'],
                ]);

                // Kurangi stok produk
                $product->decrement('stock', $item['quantity']);
            }

            // 3. Buat Record Pembayaran (Simulasi)
            Payment::create([
                'order_id' => $order->id,
                'method' => $request->payment_method,
                'reference' => 'PAY-' . time(),
                'amount' => $total,
                'status' => 'UNPAID',
            ]);

            DB::commit();

            // Kosongkan keranjang belanja
            session()->forget('cart');

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