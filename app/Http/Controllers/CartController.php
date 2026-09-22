<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    // Menampilkan halaman keranjang
    public function index()
    {
        $cartItems = Cart::with('product')
            ->where('user_id', Auth::id())
            ->get();

        $subtotal = $cartItems->sum(function ($item) {
            return $item->product->price * $item->quantity;
        });

        return view('cart.index', compact('cartItems', 'subtotal'));
    }

    // Menambah produk ke keranjang
    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $userId = Auth::id();
        $productId = $request->product_id;
        $quantity = $request->quantity;

        // Cek stok produk
        $product = Product::findOrFail($productId);
        if ($quantity > $product->stock) {
            return back()->with('error', 'Jumlah melebihi stok yang tersedia.');
        }

        // Cek apakah item sudah ada di keranjang
        $cartItem = Cart::where('user_id', $userId)
            ->where('product_id', $productId)
            ->first();

        if ($cartItem) {
            $newQuantity = $cartItem->quantity + $quantity;
            if ($newQuantity > $product->stock) {
                return back()->with('error', 'Total jumlah di keranjang melebihi stok tersedia.');
            }
            $cartItem->update(['quantity' => $newQuantity]);
        } else {
            Cart::create([
                'user_id' => $userId,
                'product_id' => $productId,
                'quantity' => $quantity,
            ]);
        }

        return redirect()->route('cart.index')->with('success', 'Produk berhasil ditambahkan ke keranjang!');
    }

    // Update jumlah item di keranjang
    public function update(Request $request)
    {
        $request->validate([
            'cart_id' => 'required|exists:carts,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $cartItem = Cart::where('id', $request->cart_id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        if ($request->quantity > $cartItem->product->stock) {
            return back()->with('error', 'Jumlah melebihi stok tersedia.');
        }

        $cartItem->update(['quantity' => $request->quantity]);

        return back()->with('success', 'Jumlah produk berhasil diperbarui.');
    }

    // Menghapus 1 item dari keranjang
    public function remove(Request $request)
    {
        $request->validate([
            'cart_id' => 'required|exists:carts,id',
        ]);

        Cart::where('id', $request->cart_id)
            ->where('user_id', Auth::id())
            ->delete();

        return back()->with('success', 'Produk berhasil dihapus dari keranjang.');
    }

    // Mengosongkan keranjang
    public function clear()
    {
        Cart::where('user_id', Auth::id())->delete();

        return back()->with('success', 'Keranjang berhasil dikosongkan.');
    }
}