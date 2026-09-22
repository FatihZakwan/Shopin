@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <h1 class="text-2xl font-bold text-gray-800 mb-6 flex items-center gap-2">
        🛒 Keranjang Belanja
    </h1>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            {{ session('error') }}
        </div>
    @endif

    @if($cartItems->isEmpty())
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-12 text-center">
            <div class="text-6xl mb-4">🛒</div>
            <p class="text-gray-500 text-lg mb-6">Keranjang belanja kamu masih kosong.</p>
            <a href="{{ route('home') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white font-semibold px-6 py-3 rounded-lg shadow transition">
                Mulai Belanja Now
            </a>
        </div>
    @else
        <!-- Form Utama Checkout Item Terpilih -->
        <form action="{{ route('checkout.index') }}" method="GET" id="checkout-form">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                
                <!-- Daftar Produk -->
                <div class="lg:col-span-2 space-y-4">
                    
                    <!-- Header Checkbox "Pilih Semua" -->
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 flex items-center justify-between">
                        <label class="flex items-center gap-3 cursor-pointer select-none font-semibold text-gray-700">
                            <input type="checkbox" id="select-all" class="w-5 h-5 rounded text-indigo-600 focus:ring-indigo-500 cursor-pointer">
                            <span>Pilih Semua ({{ $cartItems->count() }})</span>
                        </label>

                        <button type="button" 
                                onclick="document.getElementById('clear-cart-form').submit();" 
                                class="text-xs font-semibold text-red-600 hover:text-red-800 hover:underline">
                            🗑️ Hapus Semua
                        </button>
                    </div>

                    <!-- List Item Keranjang -->
                    @foreach($cartItems as $item)
                        @php
                            $imagePath = str_starts_with($item->product->image, 'http') 
                                ? $item->product->image 
                                : asset('storage/' . $item->product->image);
                            $itemSubtotal = $item->product->price * $item->quantity;
                        @endphp
                        
                        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 flex items-center gap-4">
                            
                            <!-- Checkbox Item -->
                            <input type="checkbox" 
                                   name="selected_items[]" 
                                   value="{{ $item->id }}" 
                                   data-price="{{ $item->product->price }}" 
                                   data-quantity="{{ $item->quantity }}"
                                   class="item-checkbox w-5 h-5 rounded text-indigo-600 focus:ring-indigo-500 cursor-pointer flex-shrink-0">

                            <!-- Gambar Produk -->
                            <img src="{{ $imagePath }}" 
                                 alt="{{ $item->product->name }}" 
                                 class="w-20 h-20 object-cover rounded-lg border border-gray-100 flex-shrink-0"
                                 onerror="this.onerror=null;this.src='https://via.placeholder.com/150';">
                            
                            <!-- Detail Produk -->
                            <div class="flex-1 min-w-0">
                                <h3 class="font-semibold text-gray-800 text-base truncate">{{ $item->product->name }}</h3>
                                <p class="text-sm font-medium text-indigo-600">Rp {{ number_format($item->product->price, 0, ',', '.') }}</p>
                            </div>

                            <!-- Tombol Plus / Minus -->
                            <div class="flex items-center gap-1 border border-gray-300 rounded-lg p-1 bg-gray-50">
                                <button type="button" 
                                        onclick="updateCartQuantity('{{ $item->id }}', {{ max(1, $item->quantity - 1) }})"
                                        class="w-7 h-7 flex items-center justify-center bg-white border border-gray-300 rounded text-gray-700 hover:bg-gray-200 font-bold text-sm shadow-sm"
                                        {{ $item->quantity <= 1 ? 'disabled style=opacity:0.5;' : '' }}>
                                    -
                                </button>

                                <span class="w-8 text-center font-semibold text-gray-800 text-sm">{{ $item->quantity }}</span>

                                <button type="button" 
                                        onclick="updateCartQuantity('{{ $item->id }}', {{ $item->quantity + 1 }})"
                                        class="w-7 h-7 flex items-center justify-center bg-white border border-gray-300 rounded text-gray-700 hover:bg-gray-200 font-bold text-sm shadow-sm"
                                        {{ $item->quantity >= $item->product->stock ? 'disabled style=opacity:0.5;' : '' }}>
                                    +
                                </button>
                            </div>

                            <!-- Subtotal & Hapus -->
                            <div class="text-right min-w-[100px]">
                                <p class="font-bold text-gray-900 text-sm">
                                    Rp {{ number_format($itemSubtotal, 0, ',', '.') }}
                                </p>
                                
                                <button type="button" 
                                        onclick="removeCartItem('{{ $item->id }}')" 
                                        class="text-xs font-semibold text-red-600 hover:text-red-800 hover:underline mt-1">
                                    🗑️ Hapus
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Ringkasan Belanja -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 h-fit">
                    <h2 class="text-lg font-bold text-gray-800 mb-4 pb-2 border-b">Ringkasan Belanja</h2>
                    
                    <div class="flex justify-between mb-2 text-gray-600 text-sm">
                        <span>Total Barang Dicenthang</span>
                        <span id="selected-count" class="font-semibold text-gray-800">0 barang</span>
                    </div>

                    <div class="flex justify-between mb-3 text-gray-600 text-sm">
                        <span>Total Subtotal</span>
                        <span id="subtotal-display" class="font-semibold text-gray-800">Rp 0</span>
                    </div>
                    
                    <hr class="my-4">
                    
                    <div class="flex justify-between font-bold text-lg text-gray-900 mb-6">
                        <span>Total</span>
                        <span id="total-display" class="text-indigo-600">Rp 0</span>
                    </div>
                    
                    <button type="submit" 
                            id="checkout-btn" 
                            disabled 
                            class="w-full bg-indigo-600 hover:bg-indigo-700 disabled:bg-gray-300 disabled:cursor-not-allowed text-white font-bold py-3 px-4 rounded-lg shadow transition">
                        Lanjut ke Checkout (<span id="btn-count">0</span>)
                    </button>
                </div>
            </div>
        </form>

        <!-- Form Tersembunyi untuk Update Quantity -->
        <form id="update-qty-form" action="{{ route('cart.update') }}" method="POST" class="hidden">
            @csrf
            <input type="hidden" name="cart_id" id="update-cart-id">
            <input type="hidden" name="quantity" id="update-cart-qty">
        </form>

        <!-- Form Tersembunyi untuk Hapus Single Item -->
        <form id="remove-item-form" action="{{ route('cart.remove') }}" method="POST" class="hidden">
            @csrf
            <input type="hidden" name="cart_id" id="remove-cart-id">
        </form>

        <!-- Form Tersembunyi untuk Clear Cart -->
        <form id="clear-cart-form" action="{{ route('cart.clear') }}" method="POST" class="hidden">
            @csrf
        </form>
    @endif
</div>

<!-- Script Hitung Total & Interaksi Checkbox -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const selectAll = document.getElementById('select-all');
        const itemCheckboxes = document.querySelectorAll('.item-checkbox');
        const selectedCount = document.getElementById('selected-count');
        const subtotalDisplay = document.getElementById('subtotal-display');
        const totalDisplay = document.getElementById('total-display');
        const checkoutBtn = document.getElementById('checkout-btn');
        const btnCount = document.getElementById('btn-count');

        function formatRupiah(number) {
            return 'Rp ' + new Intl.NumberFormat('id-ID').format(number);
        }

        function calculateTotal() {
            let total = 0;
            let count = 0;

            itemCheckboxes.forEach(cb => {
                if (cb.checked) {
                    const price = parseFloat(cb.getAttribute('data-price')) || 0;
                    const qty = parseInt(cb.getAttribute('data-quantity')) || 0;
                    total += price * qty;
                    count++;
                }
            });

            selectedCount.textContent = count + ' barang';
            subtotalDisplay.textContent = formatRupiah(total);
            totalDisplay.textContent = formatRupiah(total);
            btnCount.textContent = count;

            if (count > 0) {
                checkoutBtn.removeAttribute('disabled');
            } else {
                checkoutBtn.setAttribute('disabled', 'true');
            }

            // Update status 'Pilih Semua' jika semua checkbox tercentang
            if (selectAll && itemCheckboxes.length > 0) {
                selectAll.checked = (count === itemCheckboxes.length);
            }
        }

        if (selectAll) {
            selectAll.addEventListener('change', function () {
                itemCheckboxes.forEach(cb => {
                    cb.checked = selectAll.checked;
                });
                calculateTotal();
            });
        }

        itemCheckboxes.forEach(cb => {
            cb.addEventListener('change', calculateTotal);
        });

        calculateTotal();
    });

    function updateCartQuantity(cartId, newQty) {
        document.getElementById('update-cart-id').value = cartId;
        document.getElementById('update-cart-qty').value = newQty;
        document.getElementById('update-qty-form').submit();
    }

    function removeCartItem(cartId) {
        if (confirm('Apakah kamu yakin ingin menghapus produk ini?')) {
            document.getElementById('remove-cart-id').value = cartId;
            document.getElementById('remove-item-form').submit();
        }
    }
</script>
@endsection