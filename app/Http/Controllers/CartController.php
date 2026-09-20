<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    // Menampilkan halaman keranjang belanja dari database
    public function index()
    {
        $cartItems = Cart::with('product')
            ->where('user_id', Auth::id())
            ->get();

        $total = $cartItems->sum(function ($item) {
            return $item->product->price * $item->quantity;
        });

        return view('cart.index', compact('cartItems', 'total'));
    }

    // Menambah produk ke keranjang database
    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $userId = Auth::id();

        $product = Product::findOrFail($request->product_id);

        if ($product->stock < $request->quantity) {
            return redirect()->back()->with('error', 'Stok produk tidak mencukupi.');
        }

        // Cek apakah produk sudah ada di keranjang user
        $cartItem = Cart::where('user_id', $userId)
            ->where('product_id', $request->product_id)
            ->first();

        if ($cartItem) {
            $newQuantity = $cartItem->quantity + $request->quantity;
            if ($newQuantity > $product->stock) {
                return redirect()->back()->with('error', 'Jumlah melebihi stok yang tersedia.');
            }
            $cartItem->update(['quantity' => $newQuantity]);
        } else {
            Cart::create([
                'user_id' => $userId,
                'product_id' => $request->product_id,
                'quantity' => $request->quantity,
            ]);
        }

        return redirect()->back()->with('success', 'Produk berhasil ditambahkan ke keranjang!');
    }

    // Update jumlah item di keranjang
    public function update(Request $request)
    {
        $request->validate([
            'cart_id' => 'required|exists:carts,id',
            'type' => 'required|in:inc,dec',
        ]);

        $cartItem = Cart::where('id', $request->cart_id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        if ($request->type === 'inc') {
            if ($cartItem->quantity < $cartItem->product->stock) {
                $cartItem->increment('quantity');
            }
        } else {
            if ($cartItem->quantity > 1) {
                $cartItem->decrement('quantity');
            }
        }

        return redirect()->back();
    }

    // Hapus 1 item dari keranjang
    public function remove(Request $request)
    {
        $request->validate([
            'cart_id' => 'required|exists:carts,id',
        ]);

        Cart::where('id', $request->cart_id)
            ->where('user_id', Auth::id())
            ->delete();

        return redirect()->back()->with('success', 'Produk berhasil dihapus dari keranjang.');
    }
}