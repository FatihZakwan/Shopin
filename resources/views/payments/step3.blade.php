<div x-show="step === 3" x-cloak class="space-y-6">
    <h2 class="text-lg font-bold text-gray-800 border-b pb-3 flex items-center gap-2">
        <i class="fa-solid fa-file-invoice text-indigo-600"></i> Step 3: Ringkasan Pesanan
    </h2>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 bg-gray-50 p-4 rounded-xl text-sm border border-gray-100">
        <div>
            <h3 class="font-bold text-gray-700 mb-1">Tujuan Pengiriman:</h3>
            <p class="font-semibold text-gray-800" x-text="document.getElementById('customer_name')?.value"></p>
            <p class="text-gray-600 text-xs" x-text="document.getElementById('phone')?.value"></p>
            <p class="text-gray-600 text-xs mt-1" x-text="document.getElementById('address')?.value"></p>
        </div>
        <div>
            <h3 class="font-bold text-gray-700 mb-1">Metode Pembayaran:</h3>
            <p class="font-semibold text-indigo-600" x-text="selectedPaymentName || selectedPayment || 'QRIS'"></p>
        </div>
    </div>

    <div>
        <h3 class="font-bold text-gray-700 text-sm mb-2">Item Produk:</h3>
        <div class="divide-y divide-gray-100 border border-gray-100 rounded-xl p-3">
            @if(isset($cartItems) && count($cartItems) > 0)
                @foreach ($cartItems as $item)
                    <div class="py-2.5 flex justify-between items-center text-sm">
                        <div>
                            <p class="font-semibold text-gray-800">{{ $item->product->name ?? 'Produk' }}</p>
                            <p class="text-xs text-gray-500">{{ $item->quantity }} x Rp{{ number_format($item->product->price ?? 0, 0, ',', '.') }}</p>
                        </div>
                        <span class="font-bold text-gray-800">Rp{{ number_format(($item->product->price ?? 0) * $item->quantity, 0, ',', '.') }}</span>
                    </div>
                @endforeach
            @endif
        </div>
    </div>

    <div class="border-t pt-4 flex justify-between items-center text-lg font-bold text-gray-800">
        <span>Total yang harus dibayar:</span>
        <span class="text-indigo-600">Rp{{ number_format($total ?? 0, 0, ',', '.') }}</span>
    </div>

    <div class="mt-8 flex justify-between">
        <button type="button" @click="step = 2" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-6 py-2.5 rounded-xl font-bold text-sm transition flex items-center gap-2">
            <i class="fa-solid fa-arrow-left"></i> Ubah Pembayaran
        </button>

        <button type="button" @click="step = 4" class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2.5 rounded-xl font-bold text-sm transition flex items-center gap-2 shadow-md">
            Lanjut ke Bayar <i class="fa-solid fa-arrow-right"></i>
        </button>
    </div>
</div>