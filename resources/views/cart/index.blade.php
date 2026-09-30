@extends('layouts.app')

@section('content')
<<<<<<< HEAD
<!-- Import SweetAlert2 CDN -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <h1 class="text-2xl font-bold text-gray-800 mb-6 flex items-center gap-2">
        🛒 Keranjang Belanja
    </h1>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
=======
<div class="max-w-5xl mx-auto space-y-6">
    <h1 class="text-2xl font-bold text-gray-800 flex items-center gap-2">
        <i class="fa-solid fa-cart-shopping text-indigo-600"></i> Keranjang Belanja
    </h1>

    @if(session('success'))
        <div class="bg-green-100 text-green-700 p-3 rounded-xl text-sm font-medium">
>>>>>>> 6b2c316134978983c0a7ef62f70651855ad77a04
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
<<<<<<< HEAD
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
=======
        <div class="bg-red-100 text-red-700 p-3 rounded-xl text-sm font-medium">
>>>>>>> 6b2c316134978983c0a7ef62f70651855ad77a04
            {{ session('error') }}
        </div>
    @endif

    @if($cartItems->isEmpty())
<<<<<<< HEAD
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-12 text-center">
            <div class="text-6xl mb-4">🛒</div>
            <p class="text-gray-500 text-lg mb-6">Keranjang belanja kamu masih kosong.</p>
            <a href="{{ route('home') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white font-semibold px-6 py-3 rounded-lg shadow">
                Mulai Belanja Sekarang
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
                                onclick="confirmClearCart()" 
                                class="text-xs font-semibold text-red-600 hover:text-red-800 hover:underline">
                            🗑️ Hapus Semua
                        </button>
                    </div>

                    <!-- List Item Keranjang -->
                    @foreach ($cartItems as $item)
                        @php
                            $imagePath = str_starts_with($item->product->image, 'http') 
                                ? $item->product->image 
                                : asset('storage/' . $item->product->image);$itemSubtotal = $item->product->price * $item->quantity;
                        @endphp
                        
                        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 flex items-center gap-4 cart-item-row" data-id="{{ $item->id }}" data-price="{{ $item->product->price }}">
                            
                            <!-- Checkbox Item -->
                            <input type="checkbox" 
                                   name="selected_items[]" 
                                   value="{{ $item->id }}" 
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

                            <!-- Tombol Plus / Minus Instan -->
                            <div class="flex items-center gap-1 border border-gray-300 rounded-lg p-1 bg-gray-50">
                                <button type="button" 
                                        onclick="adjustQty('{{ $item->id }}', -1, {{$item->product->stock }})"
                                        id="btn-minus-{{ $item->id }}"
                                        class="w-7 h-7 flex items-center justify-center bg-white border border-gray-300 rounded text-gray-700 hover:bg-gray-200 font-bold text-sm shadow-sm disabled:opacity-50 disabled:cursor-not-allowed"
                                        {{ $item->quantity <= 1 ? 'disabled' : '' }}>
                                    -
                                </button>

                                <span id="qty-label-{{ $item->id }}" class="w-8 text-center font-semibold text-gray-800 text-sm">{{ $item->quantity }}</span>
                                
                                <input type="hidden" name="quantities[{{ $item->id }}]" id="qty-val-{{ $item->id }}" value="{{ $item->quantity }}">

                                <button type="button" 
                                        onclick="adjustQty('{{ $item->id }}', 1, {{$item->product->stock }})"
                                        id="btn-plus-{{ $item->id }}"
                                        class="w-7 h-7 flex items-center justify-center bg-white border border-gray-300 rounded text-gray-700 hover:bg-gray-200 font-bold text-sm shadow-sm disabled:opacity-50 disabled:cursor-not-allowed"
                                        {{ $item->quantity >=$item->product->stock ? 'disabled' : '' }}>
                                    +
                                </button>
                            </div>

                            <!-- Subtotal & Hapus -->
                            <div class="text-right min-w-[100px]">
                                <p class="font-bold text-gray-900 text-sm" id="item-subtotal-{{ $item->id }}">
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
                        <span>Total Barang Dicentang</span>
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
                    
                    <button type="button" 
                            id="checkout-btn" 
                            onclick="proceedToCheckout()"
                            disabled 
                            class="w-full bg-indigo-600 hover:bg-indigo-700 disabled:bg-gray-300 disabled:cursor-not-allowed text-white font-bold py-3 px-4 rounded-lg shadow flex items-center justify-center gap-1 transition">
                        Lanjut ke Checkout (<span id="btn-count">0</span>)
                    </button>
                </div>
=======
        <div class="bg-white rounded-2xl p-12 text-center shadow-sm border border-gray-100 space-y-3">
            <i class="fa-solid fa-basket-shopping text-5xl text-gray-300"></i>
            <p class="text-gray-500 font-medium">Keranjang belanja kamu masih kosong.</p>
            <a href="/" class="inline-block bg-indigo-600 text-white px-6 py-2.5 rounded-xl font-bold text-sm hover:bg-indigo-700 transition">
                Mulai Belanja Now
            </a>
        </div>
    @else
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Daftar Item Keranjang -->
            <div class="lg:col-span-2 space-y-3">
                @foreach($cartItems as $item)
                    <div class="bg-white rounded-2xl p-4 shadow-sm border border-gray-100 flex items-center justify-between gap-4">
                        <div class="flex items-center gap-4">
                            <img src="{{ $item->product->image }}" alt="{{ $item->product->name }}" class="w-16 h-16 object-cover rounded-xl border border-gray-100">
                            <div>
                                <h3 class="font-bold text-gray-800 text-sm line-clamp-1">{{ $item->product->name }}</h3>
                                <p class="text-xs text-indigo-600 font-extrabold mt-0.5">
                                    Rp{{ number_format($item->product->price, 0, ',', '.') }}
                                </p>
                            </div>
                        </div>

                        <!-- Tombol Tambah/Kurang & Hapus -->
                        <div class="flex items-center gap-4">
                            <div class="flex items-center border border-gray-200 rounded-xl overflow-hidden bg-gray-50">
                                <form action="{{ route('cart.update') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="cart_id" value="{{ $item->id }}">
                                    <input type="hidden" name="type" value="dec">
                                    <button type="submit" class="w-8 h-8 flex items-center justify-center font-bold text-gray-600 hover:bg-gray-200 text-xs">-</button>
                                </form>

                                <span class="w-8 text-center text-xs font-bold">{{ $item->quantity }}</span>

                                <form action="{{ route('cart.update') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="cart_id" value="{{ $item->id }}">
                                    <input type="hidden" name="type" value="inc">
                                    <button type="submit" class="w-8 h-8 flex items-center justify-center font-bold text-gray-600 hover:bg-gray-200 text-xs">+</button>
                                </form>
                            </div>

                            <form action="{{ route('cart.remove') }}" method="POST">
                                @csrf
                                <input type="hidden" name="cart_id" value="{{ $item->id }}">
                                <button type="submit" class="text-red-500 hover:text-red-700 text-sm p-1">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
>>>>>>> 6b2c316134978983c0a7ef62f70651855ad77a04
            </div>
        </form>

<<<<<<< HEAD
        <!-- Form Tersembunyi untuk Hapus Single Item -->
        <form id="remove-item-form" action="{{ route('cart.remove') }}" method="POST" class="hidden">
            @csrf
            <input type="hidden" name="cart_id" id="remove-cart-id">
        </form>

        <!-- Form Tersembunyi untuk Clear Cart -->
        <form id="clear-cart-form" action="{{ route('cart.clear') }}" method="POST" class="hidden">
            @csrf
        </form>
=======
            <!-- Ringkasan Belanja -->
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 h-fit space-y-4">
                <h2 class="font-bold text-gray-800 border-b pb-3">Ringkasan Belanja</h2>
                <div class="flex justify-between items-center text-sm">
                    <span class="text-gray-500">Total Harga</span>
                    <span class="font-extrabold text-indigo-600 text-lg">Rp{{ number_format($total, 0, ',', '.') }}</span>
                </div>
                <a href="{{ route('checkout.index') }}" class="block text-center w-full bg-indigo-600 text-white py-3 rounded-xl font-bold hover:bg-indigo-700 transition shadow-md">
                    Lanjut ke Checkout <i class="fa-solid fa-arrow-right ml-1"></i>
                </a>
            </div>
        </div>
>>>>>>> 6b2c316134978983c0a7ef62f70651855ad77a04
    @endif
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const selectAll = document.getElementById('select-all');
        const itemCheckboxes = document.querySelectorAll('.item-checkbox');
        
        if (selectAll) {
            selectAll.addEventListener('change', function () {
                itemCheckboxes.forEach(cb => {
                    cb.checked = selectAll.checked;
                });
                recalculateTotal();
            });
        }

        itemCheckboxes.forEach(cb => {
            cb.addEventListener('change', recalculateTotal);
        });

        recalculateTotal();
    });

    function formatRupiah(amount) {
        return 'Rp ' + new Intl.NumberFormat('id-ID').format(amount);
    }

    function adjustQty(cartId, step, maxStock) {
        const qtyVal = document.getElementById(`qty-val-${cartId}`);
        const qtyLabel = document.getElementById(`qty-label-${cartId}`);
        const btnMinus = document.getElementById(`btn-minus-${cartId}`);
        const btnPlus = document.getElementById(`btn-plus-${cartId}`);
        
        let currentQty = parseInt(qtyVal.value) || 1;
        let nextQty = currentQty + step;

        if (nextQty >= 1 && nextQty <= maxStock) {
            qtyVal.value = nextQty;
            qtyLabel.textContent = nextQty;

            btnMinus.disabled = (nextQty <= 1);
            btnPlus.disabled = (nextQty >= maxStock);

            const row = document.querySelector(`.cart-item-row[data-id="${cartId}"]`);
            const price = parseFloat(row.getAttribute('data-price')) || 0;
            const subtotalLabel = document.getElementById(`item-subtotal-${cartId}`);
            
            subtotalLabel.textContent = formatRupiah(price * nextQty);

            recalculateTotal();
        }
    }

    function recalculateTotal() {
        const itemCheckboxes = document.querySelectorAll('.item-checkbox');
        const selectedCount = document.getElementById('selected-count');
        const subtotalDisplay = document.getElementById('subtotal-display');
        const totalDisplay = document.getElementById('total-display');
        const checkoutBtn = document.getElementById('checkout-btn');
        const btnCount = document.getElementById('btn-count');
        const selectAll = document.getElementById('select-all');

        let grandTotal = 0;
        let checkedCount = 0;

        itemCheckboxes.forEach(cb => {
            if (cb.checked) {
                const cartId = cb.value;
                const row = cb.closest('.cart-item-row');
                const price = parseFloat(row.getAttribute('data-price')) || 0;
                const qty = parseInt(document.getElementById(`qty-val-${cartId}`).value) || 0;
                
                grandTotal += price * qty;
                checkedCount++;
            }
        });

        selectedCount.textContent = checkedCount + ' barang';
        subtotalDisplay.textContent = formatRupiah(grandTotal);
        totalDisplay.textContent = formatRupiah(grandTotal);
        btnCount.textContent = checkedCount;

        if (checkedCount > 0) {
            checkoutBtn.removeAttribute('disabled');
        } else {
            checkoutBtn.setAttribute('disabled', 'true');
        }

        if (selectAll && itemCheckboxes.length > 0) {
            selectAll.checked = (checkedCount === itemCheckboxes.length);
        }
    }

    // --- PROSES CHECKOUT DENGAN SWEETALERT2 ---
    function proceedToCheckout() {
        const checkedItems = document.querySelectorAll('.item-checkbox:checked');
        
        if (checkedItems.length === 0) {
            Swal.fire({
                icon: 'warning',
                title: 'Pilih Produk',
                text: 'Harap centang minimal 1 produk untuk dilanjutkan ke checkout.',
                confirmButtonColor: '#4F46E5',
                customClass: { popup: 'rounded-2xl', confirmButton: 'rounded-xl font-bold px-5 py-2.5' }
            });
            return;
        }

        // Tampilkan Loading Alert
        Swal.fire({
            title: 'Menyiapkan Checkout...',
            text: 'Harap tunggu, Anda sedang diarahkan ke halaman pembayaran.',
            allowOutsideClick: false,
            showConfirmButton: false,
            customClass: { popup: 'rounded-2xl' },
            willOpen: () => {
                Swal.showLoading();
            }
        });

        // Submit Form Utama Checkout
        document.getElementById('checkout-form').submit();
    }

    // --- KONFIRMASI HAPUS SINGLE ITEM DENGAN SWEETALERT2 ---
    function removeCartItem(cartId) {
        Swal.fire({
            title: 'Hapus Produk?',
            text: 'Produk akan dihapus dari keranjang belanja kamu.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#DC2626',
            cancelButtonColor: '#6B7280',
            confirmButtonText: 'Ya, Hapus',
            cancelButtonText: 'Batal',
            customClass: { 
                popup: 'rounded-2xl', 
                confirmButton: 'rounded-xl font-bold px-4 py-2',
                cancelButton: 'rounded-xl font-bold px-4 py-2' 
            }
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('remove-cart-id').value = cartId;
                document.getElementById('remove-item-form').submit();
            }
        });
    }

    // --- KONFIRMASI HAPUS SEMUA DENGAN SWEETALERT2 ---
    function confirmClearCart() {
        Swal.fire({
            title: 'Kosongkan Keranjang?',
            text: 'Semua item di dalam keranjang belanja akan dihapus.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#DC2626',
            cancelButtonColor: '#6B7280',
            confirmButtonText: 'Ya, Hapus Semua',
            cancelButtonText: 'Batal',
            customClass: { 
                popup: 'rounded-2xl', 
                confirmButton: 'rounded-xl font-bold px-4 py-2',
                cancelButton: 'rounded-xl font-bold px-4 py-2' 
            }
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('clear-cart-form').submit();
            }
        });
    }
</script>
@endsection