<div x-show="step === 1" x-cloak class="space-y-5">
    <!-- Header Step 1 + Tombol Kembali ke Keranjang (Sejajar & Rapi) -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-b pb-3">
        <h2 class="text-lg font-bold text-gray-800 flex items-center gap-2">
            <i class="fa-solid fa-location-dot text-indigo-600"></i> Step 1: Informasi Pengiriman & Lokasi
        </h2>

        <a href="{{ route('cart.index') }}" 
           class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2 rounded-xl font-bold text-sm shadow-md transition shrink-0">
            <i class="fa-solid fa-arrow-left"></i>
            Kembali ke Keranjang
        </a>
    </div>

    <div>
        <label class="block text-xs font-semibold uppercase text-gray-500 mb-1">Nama Lengkap</label>
        <input type="text" id="customer_name" name="customer_name" value="{{ Auth::user()->name ?? '' }}" required class="w-full border border-gray-300 rounded-xl p-2.5 text-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none">
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
            <label class="block text-xs font-semibold uppercase text-gray-500 mb-1">Email</label>
            <input type="email" id="customer_email" name="customer_email" value="{{ Auth::user()->email ?? '' }}" required class="w-full border border-gray-300 rounded-xl p-2.5 text-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none">
        </div>
        <div>
            <label class="block text-xs font-semibold uppercase text-gray-500 mb-1">Nomor WhatsApp / HP</label>
            <input type="text" id="phone" name="phone" placeholder="08123456789" required class="w-full border border-gray-300 rounded-xl p-2.5 text-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none">
        </div>
    </div>

    <div class="space-y-3 pt-2">
        <div class="flex justify-between items-center">
            <label class="block text-xs font-semibold uppercase text-gray-500">Pilih Titik Lokasi di Peta</label>
            <button type="button" onclick="getCurrentLocation()" class="text-xs text-indigo-600 font-semibold hover:underline flex items-center gap-1">
                <i class="fa-solid fa-location-crosshairs"></i> Gunakan Lokasi Saat Ini
            </button>
        </div>

        <div class="flex gap-2">
            <input type="text" id="search-input" placeholder="Ketik jalan, perumahan, atau daerah di Indonesia..." class="w-full border border-gray-300 rounded-xl px-3 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none">
            <button type="button" onclick="performManualSearch()" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2.5 rounded-xl font-semibold text-sm flex items-center gap-1.5 transition shrink-0">
                <i class="fa-solid fa-magnifying-glass"></i> Cari
            </button>
        </div>

        <div class="relative w-full">
            <div id="map" class="w-full h-80 rounded-xl border border-gray-300 shadow-inner z-0"></div>
        </div>
        
        <div>
            <label class="block text-xs font-semibold uppercase text-gray-500 mb-1">Alamat Lengkap Pengiriman</label>
            <textarea id="address" name="address" rows="3" placeholder="Geser pin pada peta atau pilih dari hasil pencarian..." required class="w-full border border-gray-300 rounded-xl p-2.5 text-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none leading-relaxed"></textarea>
        </div>
    </div>

    <div class="mt-8 flex justify-end">
        <button type="button" @click="if (validateStep1()) { step = 2; }" class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2.5 rounded-xl font-bold text-sm shadow-md transition">
            Lanjut ke Pembayaran <i class="fa-solid fa-arrow-right"></i>
        </button>
    </div>
</div>