<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Services\TripayService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    protected $tripayService;

    public function __construct(TripayService $tripayService)
    {
        $this->tripayService = $tripayService;
    }

    public function index(Request $request)
    {
        $selectedIds = $request->input('selected_items', []);
        $quantities = $request->input('quantities', []);

        if (empty($selectedIds)) {
            return redirect()->route('cart.index')->with('error', 'Pilih minimal satu produk untuk di-checkout.');
        }

        // Ambil item keranjang berdasarkan ID yang dicentang
        $cartItems = Cart::with('product')
            ->where('user_id', Auth::id())
            ->whereIn('id', $selectedIds)
            ->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Produk di keranjang tidak ditemukan.');
        }

        // Update kuantitas di database/memory sesuai input dari form keranjang
        foreach ($cartItems as $item) {
            if (isset($quantities[$item->id])) {
                $item->quantity = (int) $quantities[$item->id];
                $item->save(); // Simpan perubahan qty dari keranjang ke database
            }
        }

        $total = $cartItems->sum(function ($item) {
            return $item->product->price * $item->quantity;
        });

        // Ambil Channel Pembayaran dari Tripay
        $channels = [];
        try {
            $tripayResponse = $this->tripayService->getPaymentChannels();
            if (isset($tripayResponse->success) && $tripayResponse->success) {
                $channels = $tripayResponse->data;
            }
        } catch (\Exception $e) {
            // Mengabaikan error jika Tripay API timeout/gagal
        }

        return view('checkout.index', compact('cartItems', 'total', 'selectedIds', 'channels'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'customer_name'  => 'required|string|max:255',
            'customer_email' => 'required|email|max:255',
            'phone'          => 'required|string|max:20',
            'address'        => 'required|string',
            'payment_method' => 'required|string',
            'cart_ids'       => 'required|array',
            'cart_ids.*'     => 'exists:carts,id',
        ]);

        $cartItems = Cart::with('product')
            ->where('user_id', Auth::id())
            ->whereIn('id', $request->cart_ids)
            ->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Keranjang belanja kamu kosong!');
        }

        $total = $cartItems->sum(function ($item) {
            return $item->product->price * $item->quantity;
        });

        DB::beginTransaction();

        try {
            // 1. Buat Order Baru
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

            // 2. Simpan Item Order & Kurangi Stok
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

                $product->decrement('stock', $item->quantity);
            }

            // LOAD RELASI ORDER ITEMS SUPAYA KEBACA OLEH TRIPAY SERVICE
            $order->load('orderItems');

            // 3. Panggil API Tripay untuk membuat transaksi
            $tripayRes = $this->tripayService->createTransaction($order, $request->payment_method);

            // 4. Simpan detail pembayaran Tripay
            Payment::create([
                'order_id'  => $order->id,
                'method'    => $request->payment_method,
                'reference' => $tripayRes->data->reference,
                'amount'    => $total,
                'status'    => 'UNPAID',
            ]);

            // 5. Hapus barang yang di-checkout dari keranjang
            Cart::whereIn('id', $request->cart_ids)->where('user_id', Auth::id())->delete();

            DB::commit();

            // 6. Redirect ke halaman instruksi bayar dari Tripay
            return redirect($tripayRes->data->checkout_url);

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Method untuk menangani kembalinya pembeli dari Tripay (Return URL)
     */
    public function success(Request $request)
    {
        return redirect()->route('home')->with('success', 'Transaksi berhasil dibuat. Silakan selesaikan pembayaran Anda!');
    }
}