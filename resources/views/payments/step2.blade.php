<div x-show="step === 2" x-cloak class="space-y-5">
    <h2 class="text-lg font-bold text-gray-800 border-b pb-3 flex items-center gap-2">
        <i class="fa-solid fa-credit-card text-indigo-600"></i> Step 2: Pilih Metode Pembayaran
    </h2>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 max-h-96 overflow-y-auto p-1">
        @if (isset($channels) && count($channels) > 0)
            @foreach ($channels as $channel)
                @if (isset($channel->active) && $channel->active)
                    <label class="border border-gray-200 rounded-xl p-3 flex items-center justify-between cursor-pointer hover:border-indigo-500 transition"
                           :class="selectedPayment === '{{ $channel->code }}' ? 'border-indigo-600 bg-indigo-50/40' : ''">
                        <div class="flex items-center gap-3">
                            <input type="radio" name="payment_method" value="{{ $channel->code }}" 
                                   x-model="selectedPayment" 
                                   @change="selectedPaymentName = '{{ $channel->name }}'"
                                   required class="text-indigo-600 focus:ring-indigo-500">
                            <div>
                                <p class="text-sm font-bold text-gray-800">{{ $channel->name }}</p>
                                <p class="text-xs text-gray-500">{{ $channel->group }}</p>
                            </div>
                        </div>
                        @if (isset($channel->icon_url))
                            <img src="{{ $channel->icon_url }}" alt="{{ $channel->name }}" class="h-6 object-contain">
                        @endif
                    </label>
                @endif
            @endforeach
        @else
            <label class="border border-gray-200 rounded-xl p-3 flex items-center gap-3 cursor-pointer">
                <input type="radio" name="payment_method" value="QRIS" x-model="selectedPayment" @change="selectedPaymentName = 'QRIS (All Payment)'" checked class="text-indigo-600 focus:ring-indigo-500">
                <div>
                    <p class="text-sm font-bold text-gray-800">QRIS (All Payment)</p>
                    <p class="text-xs text-gray-500">Tripay Sandbox Mode</p>
                </div>
            </label>
        @endif
    </div>

    <div class="mt-8 flex justify-between">
        <button type="button" @click="step = 1; setTimeout(() => { if(map) map.resize(); }, 150);" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-6 py-2.5 rounded-xl font-bold text-sm transition flex items-center gap-2">
            <i class="fa-solid fa-arrow-left"></i> Kembali ke Lokasi
        </button>

        <button type="button" @click="if (!selectedPayment) { alert('Silakan pilih metode pembayaran terlebih dahulu.'); } else { step = 3; }" class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2.5 rounded-xl font-bold text-sm transition flex items-center gap-2 shadow-md">
            Lihat Ringkasan <i class="fa-solid fa-arrow-right"></i>
        </button>
    </div>
</div>