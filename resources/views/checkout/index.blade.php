    @extends('layouts.app')

    @section('content')
    <div class="max-w-5xl mx-auto px-4 py-6">
        <h1 class="text-2xl font-bold text-gray-800 mb-6 flex items-center gap-2">
            <i class="fa-solid fa-credit-card text-indigo-600"></i> Checkout Pesanan
        </h1>

        @if(session('error'))
            <div class="bg-red-100 text-red-700 p-3 rounded-lg mb-4 text-sm font-medium">
                {{ session('error') }}
            </div>
        @endif

        <form action="{{ route('checkout.store') }}" method="POST">
            @csrf
            
            @foreach ($cartItems as $item)
                <input type="hidden" name="cart_ids[]" value="{{ $item->id }}">
            @endforeach

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                
                <!-- Kolom Kiri: Informasi Pengiriman & Peta -->
                <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm border border-gray-100 p-6 space-y-4">
                    <h2 class="text-lg font-bold text-gray-800 border-b pb-3">Informasi Pengiriman</h2>

                    <div>
                        <label class="block text-xs font-semibold uppercase text-gray-500 mb-1">Nama Lengkap</label>
                        <input type="text" name="customer_name" value="{{ Auth::user()->name }}" required class="w-full border border-gray-300 rounded-xl p-2.5 text-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold uppercase text-gray-500 mb-1">Email</label>
                            <input type="email" name="customer_email" value="{{ Auth::user()->email }}" required class="w-full border border-gray-300 rounded-xl p-2.5 text-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold uppercase text-gray-500 mb-1">Nomor WhatsApp / HP</label>
                            <input type="text" name="phone" placeholder="08123456789" required class="w-full border border-gray-300 rounded-xl p-2.5 text-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                        </div>
                    </div>

                    <!-- Section Peta & Pencarian -->
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

                    <!-- Metode Pembayaran Tripay -->
                    <h2 class="text-lg font-bold text-gray-800 border-b pb-3 pt-4">Metode Pembayaran (Tripay Payment Gateway)</h2>
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 max-h-72 overflow-y-auto p-1">
                        @if (isset($channels) && count($channels) > 0)
                            @foreach ($channels as $channel)
                                @if (isset($channel->active) && $channel->active)
                                    <label class="border border-gray-200 rounded-xl p-3 flex items-center justify-between cursor-pointer hover:border-indigo-500 transition">
                                        <div class="flex items-center gap-3">
                                            <input type="radio" name="payment_method" value="{{ $channel->code }}" required class="text-indigo-600">
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
                                <input type="radio" name="payment_method" value="QRIS" checked class="text-indigo-600">
                                <div>
                                    <p class="text-sm font-bold text-gray-800">QRIS (All Payment)</p>
                                    <p class="text-xs text-gray-500">Tripay Sandbox Mode</p>
                                </div>
                            </label>
                        @endif
                    </div>
                </div> <!-- Penutup Kolom Kiri -->

                <!-- Kolom Kanan: Ringkasan Pesanan -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 h-fit space-y-4">
                    <h2 class="text-lg font-bold text-gray-800 border-b pb-3">Ringkasan Pesanan</h2>

                    <div class="divide-y divide-gray-100 max-h-60 overflow-y-auto">
                        @foreach ($cartItems as $item)
                            <div class="py-2 flex justify-between text-sm">
                                <div>
                                    <p class="font-semibold text-gray-800">{{ $item->product->name ?? 'Produk' }}</p>
                                    <p class="text-xs text-gray-500">{{ $item->quantity }} x Rp{{ number_format($item->product->price ?? 0, 0, ',', '.') }}</p>
                                </div>
                                <span class="font-bold text-gray-700">Rp{{ number_format(($item->product->price ?? 0) * $item->quantity, 0, ',', '.') }}</span>
                            </div>
                        @endforeach
                    </div>

                    <div class="border-t pt-3 flex justify-between font-bold text-lg text-gray-800">
                        <span>Total Bayar</span>
                        <span class="text-indigo-600">Rp{{ number_format($total ?? 0, 0, ',', '.') }}</span>
                    </div>

                    <button type="submit" class="w-full bg-indigo-600 text-white py-3 rounded-xl font-bold hover:bg-indigo-700 transition shadow-md">
                        Bayar Sekarang <i class="fa-solid fa-arrow-right ml-1"></i>
                    </button>
                </div> <!-- Penutup Kolom Kanan -->

            </div> <!-- Penutup Grid -->
        </form>
    </div> <!-- Penutup Main Container -->

    <!-- Script MapLibre GL & MapTiler API -->
    <link href="https://unpkg.com/maplibre-gl@3.6.2/dist/maplibre-gl.css" rel="stylesheet" />
    <script src="https://unpkg.com/maplibre-gl@3.6.2/dist/maplibre-gl.js"></script>

    <script>
        const MAPTILER_KEY = 'fqEpitgACVAIrXUMMaFP';
        let map, marker;
        const defaultLocation = [106.827153, -6.175392]; // Monas, Jakarta [Lng, Lat]

        // Batas koordinat Indonesia (Bounding Box)
        function isWithinIndonesiaBounds(lng, lat) {
            const minLng = 95.0;
            const maxLng = 141.0;
            const minLat = -11.0;
            const maxLat = 6.0;

            return (lng >= minLng && lng <= maxLng && lat >= minLat && lat <= maxLat);
        }

        document.addEventListener('DOMContentLoaded', function() {
            const mapElement = document.getElementById('map');
            if (!mapElement) return;

            // Inisialisasi Peta MapTiler dengan batasan geser peta (maxBounds) khusus Indonesia
            map = new maplibregl.Map({
                container: 'map',
                style: `https://api.maptiler.com/maps/streets-v2/style.json?key=${MAPTILER_KEY}`,
                center: defaultLocation,
                zoom: 15,
                maxBounds: [
                    [92.0, -12.0],  // Sudut Barat Daya
                    [142.0, 8.0]    // Sudut Timur Laut
                ]
            });

            // Kontrol Navigasi
            map.addControl(new maplibregl.NavigationControl({ showCompass: false }), 'top-right');

            // Marker Dragable
            marker = new maplibregl.Marker({ draggable: true, color: '#4F46E5' })
                .setLngLat(defaultLocation)
                .addTo(map);

            // Geocoding Awal
            reverseGeocode(defaultLocation[0], defaultLocation[1]);

            // Event Drag Marker
            marker.on('dragend', function() {
                const lngLat = marker.getLngLat();
                if (isWithinIndonesiaBounds(lngLat.lng, lngLat.lat)) {
                    reverseGeocode(lngLat.lng, lngLat.lat);
                } else {
                    alert("Lokasi tidak terjangkau kurir. Harap pilih titik lokasi di dalam wilayah Indonesia.");
                    marker.setLngLat(defaultLocation);
                    map.flyTo({ center: defaultLocation, zoom: 15 });
                    reverseGeocode(defaultLocation[0], defaultLocation[1]);
                }
            });

            // Event Klik Peta
            map.on('click', function(e) {
                if (isWithinIndonesiaBounds(e.lngLat.lng, e.lngLat.lat)) {
                    marker.setLngLat(e.lngLat);
                    reverseGeocode(e.lngLat.lng, e.lngLat.lat);
                } else {
                    alert("Lokasi tidak terjangkau kurir. Harap pilih titik lokasi di dalam wilayah Indonesia.");
                }
            });

            // Event Input Enter
            const input = document.getElementById('search-input');
            if (input) {
                input.addEventListener('keydown', function(e) {
                    if (e.key === 'Enter') {
                        e.preventDefault();
                        performManualSearch();
                    }
                });
            }

            setTimeout(() => map.resize(), 300);
        });

        // Cari Lokasi
        function performManualSearch() {
            const input = document.getElementById('search-input');
            if (!input || !input.value.trim()) return;

            const query = input.value.trim();

            fetch(`https://api.maptiler.com/geocoding/${encodeURIComponent(query)}.json?key=${MAPTILER_KEY}&country=id&language=id`)
                .then(res => res.json())
                .then(data => {
                    if (data && data.features && data.features.length > 0) {
                        const feature = data.features[0];
                        const [lng, lat] = feature.center;

                        if (isWithinIndonesiaBounds(lng, lat) && isLocationInIndonesia(feature)) {
                            map.flyTo({ center: [lng, lat], zoom: 16 });
                            marker.setLngLat([lng, lat]);
                            reverseGeocode(lng, lat);
                        } else {
                            alert("Lokasi tidak terjangkau kurir. Harap pilih lokasi di wilayah Indonesia.");
                        }
                    } else {
                        alert("Lokasi tidak ditemukan atau berada di luar jangkauan kurir (Hanya wilayah Indonesia).");
                    }
                })
                .catch(() => alert("Gagal mengambil data lokasi dari server."));
        }

        // Ambil GPS User
        function getCurrentLocation() {
            if (!navigator.geolocation) {
                alert("Browser Anda tidak mendukung Geolocation.");
                return;
            }

            const addressInput = document.getElementById('address');
            if (addressInput) addressInput.value = "Mendapatkan lokasi dari GPS...";

            navigator.geolocation.getCurrentPosition(
                function(position) {
                    const lat = position.coords.latitude;
                    const lng = position.coords.longitude;

                    if (isWithinIndonesiaBounds(lng, lat)) {
                        map.flyTo({ center: [lng, lat], zoom: 16 });
                        marker.setLngLat([lng, lat]);
                        reverseGeocode(lng, lat);
                    } else {
                        alert("Lokasi GPS Anda terdeteksi di luar wilayah Indonesia (tidak terjangkau kurir).");
                    }
                },
                function() {
                    alert("Akses lokasi ditolak atau tidak dapat diperoleh.");
                },
                { enableHighAccuracy: true, timeout: 10000 }
            );
        }

        // Reverse Geocoding
        function reverseGeocode(lng, lat) {
            fetch(`https://api.maptiler.com/geocoding/${lng},${lat}.json?key=${MAPTILER_KEY}&language=id`)
                .then(res => res.json())
                .then(data => {
                    const addressInput = document.getElementById('address');
                    if (!addressInput) return;

                    if (data && data.features && data.features.length > 0) {
                        const mainFeature = data.features[0];

                        if (isLocationInIndonesia(mainFeature)) {
                            let parts = [];
                            if (mainFeature.text) parts.push(mainFeature.text);

                            if (mainFeature.context && Array.isArray(mainFeature.context)) {
                                mainFeature.context.forEach(ctx => {
                                    if (ctx.text && !parts.includes(ctx.text)) {
                                        parts.push(ctx.text);
                                    }
                                });
                            }

                            addressInput.value = parts.join(', ');
                        } else {
                            addressInput.value = "";
                            alert("Lokasi tidak terjangkau kurir. Harap pilih titik lokasi di dalam wilayah Indonesia.");
                        }
                    } else {
                        addressInput.value = "";
                        alert("Lokasi tidak terjangkau kurir.");
                    }
                })
                .catch(() => {
                    const addressInput = document.getElementById('address');
                    if (addressInput) addressInput.value = `Lat: ${lat.toFixed(6)}, Lng: ${lng.toFixed(6)}`;
                });
        }

        // Helper fungsi untuk mengecek data negara Indonesia
        function isLocationInIndonesia(feature) {
            if (!feature) return false;

            if (feature.properties) {
                const countryCode = feature.properties.country_code || feature.properties.country_a2;
                if (countryCode && countryCode.toLowerCase() === 'id') return true;
            }

            if (feature.context && Array.isArray(feature.context)) {
                const hasIndonesiaContext = feature.context.some(ctx => 
                    (ctx.country_code && ctx.country_code.toLowerCase() === 'id') ||
                    (ctx.text && ctx.text.toLowerCase() === 'indonesia')
                );
                if (hasIndonesiaContext) return true;
            }

            return false;
        }
    </script>
    @endsection