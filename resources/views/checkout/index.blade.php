@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto px-4 py-8" x-data="{ 
    step: 1,
    selectedPayment: '',
    selectedPaymentName: '',
    validateStep1() {
        const name = document.getElementById('customer_name')?.value.trim();
        const email = document.getElementById('customer_email')?.value.trim();
        const phone = document.getElementById('phone')?.value.trim();
        const address = document.getElementById('address')?.value.trim();

        if (!name || !email || !phone || !address) {
            alert('Harap isi semua informasi pengiriman dan lokasi terlebih dahulu.');
            return false;
        }
        return true;
    }
}">

    <!-- STEPPER HEADER -->
    <div class="mb-10">
        <div class="flex items-center justify-between max-w-2xl mx-auto">
            <!-- Step 1 -->
            <div class="flex items-center gap-2">
                <div class="w-9 h-9 rounded-full flex items-center justify-center text-sm font-bold transition duration-300"
                     :class="step >= 1 ? 'bg-indigo-600 text-white' : 'bg-gray-200 text-gray-600'">1</div>
                <span class="text-sm font-medium hidden sm:inline" :class="step >= 1 ? 'text-indigo-600 font-semibold' : 'text-gray-500'">Lokasi</span>
            </div>

            <div class="flex-1 h-0.5 mx-3 transition duration-300" :class="step >= 2 ? 'bg-indigo-600' : 'bg-gray-200'"></div>

            <!-- Step 2 -->
            <div class="flex items-center gap-2">
                <div class="w-9 h-9 rounded-full flex items-center justify-center text-sm font-bold transition duration-300"
                     :class="step >= 2 ? 'bg-indigo-600 text-white' : 'bg-gray-200 text-gray-600'">2</div>
                <span class="text-sm font-medium hidden sm:inline" :class="step >= 2 ? 'text-indigo-600 font-semibold' : 'text-gray-500'">Pembayaran</span>
            </div>

            <div class="flex-1 h-0.5 mx-3 transition duration-300" :class="step >= 3 ? 'bg-indigo-600' : 'bg-gray-200'"></div>

            <!-- Step 3 -->
            <div class="flex items-center gap-2">
                <div class="w-9 h-9 rounded-full flex items-center justify-center text-sm font-bold transition duration-300"
                     :class="step >= 3 ? 'bg-indigo-600 text-white' : 'bg-gray-200 text-gray-600'">3</div>
                <span class="text-sm font-medium hidden sm:inline" :class="step >= 3 ? 'text-indigo-600 font-semibold' : 'text-gray-500'">Ringkasan</span>
            </div>

            <div class="flex-1 h-0.5 mx-3 transition duration-300" :class="step >= 4 ? 'bg-indigo-600' : 'bg-gray-200'"></div>

            <!-- Step 4 -->
            <div class="flex items-center gap-2">
                <div class="w-9 h-9 rounded-full flex items-center justify-center text-sm font-bold transition duration-300"
                     :class="step >= 4 ? 'bg-indigo-600 text-white' : 'bg-gray-200 text-gray-600'">4</div>
                <span class="text-sm font-medium hidden sm:inline" :class="step >= 4 ? 'text-indigo-600 font-semibold' : 'text-gray-500'">Bayar</span>
            </div>
        </div>
    </div>

    @if(session('error'))
        <div class="bg-red-100 text-red-700 p-3 rounded-xl mb-6 text-sm font-medium">
            {{ session('error') }}
        </div>
    @endif

    <!-- Form Checkout Utama -->
    <form id="checkout-form" action="{{ route('checkout.store') }}" method="POST">
        @csrf
        
        @if(isset($cartItems) && count($cartItems) > 0)
            @foreach($cartItems as $item)
                <input type="hidden" name="cart_ids[]" value="{{ $item->id }}">
            @endforeach
        @endif

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 sm:p-8">
            @include('payments.step1')
            @include('payments.step2')
            @include('payments.step3')
            @include('payments.step4')
        </div>
    </form>
</div>

<style>
    [x-cloak] { display: none !important; }
</style>

<!-- Import Alpine.js -->
<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

<!-- Script MapLibre GL & MapTiler API -->
<link href="https://unpkg.com/maplibre-gl@3.6.2/dist/maplibre-gl.css" rel="stylesheet" />
<script src="https://unpkg.com/maplibre-gl@3.6.2/dist/maplibre-gl.js"></script>

<script>
    const MAPTILER_KEY = 'fqEpitgACVAIrXUMMaFP';
    let map, marker;
    const defaultLocation = [106.827153, -6.175392];

    function isWithinIndonesiaBounds(lng, lat) {
        return (lng >= 95.0 && lng <= 141.0 && lat >= -11.0 && lat <= 6.0);
    }

    document.addEventListener('DOMContentLoaded', function() {
        const mapElement = document.getElementById('map');
        if (!mapElement) return;

        map = new maplibregl.Map({
            container: 'map',
            style: `https://api.maptiler.com/maps/streets-v2/style.json?key=${MAPTILER_KEY}`,
            center: defaultLocation,
            zoom: 15,
            maxBounds: [[92.0, -12.0], [142.0, 8.0]]
        });

        map.addControl(new maplibregl.NavigationControl({ showCompass: false }), 'top-right');

        marker = new maplibregl.Marker({ draggable: true, color: '#4F46E5' })
            .setLngLat(defaultLocation)
            .addTo(map);

        reverseGeocode(defaultLocation[0], defaultLocation[1]);

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

        map.on('click', function(e) {
            if (isWithinIndonesiaBounds(e.lngLat.lng, e.lngLat.lat)) {
                marker.setLngLat(e.lngLat);
                reverseGeocode(e.lngLat.lng, e.lngLat.lat);
            } else {
                alert("Lokasi tidak terjangkau kurir. Harap pilih titik lokasi di dalam wilayah Indonesia.");
            }
        });

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

    function performManualSearch() {
        const input = document.getElementById('search-input');
        if (!input || !input.value.trim()) return;

        fetch(`https://api.maptiler.com/geocoding/${encodeURIComponent(input.value.trim())}.json?key=${MAPTILER_KEY}&country=id&language=id`)
            .then(res => res.json())
            .then(data => {
                if (data && data.features && data.features.length > 0) {
                    const feature = data.features[0];
                    const [lng, lat] = feature.center;
                    if (isWithinIndonesiaBounds(lng, lat)) {
                        map.flyTo({ center: [lng, lat], zoom: 16 });
                        marker.setLngLat([lng, lat]);
                        reverseGeocode(lng, lat);
                    } else {
                        alert("Lokasi tidak terjangkau kurir.");
                    }
                } else {
                    alert("Lokasi tidak ditemukan.");
                }
            })
            .catch(() => alert("Gagal mengambil data lokasi."));
    }

    function getCurrentLocation() {
        if (!navigator.geolocation) return alert("Browser Anda tidak mendukung Geolocation.");
        document.getElementById('address').value = "Mendapatkan lokasi dari GPS...";

        navigator.geolocation.getCurrentPosition(function(position) {
            const lat = position.coords.latitude;
            const lng = position.coords.longitude;
            if (isWithinIndonesiaBounds(lng, lat)) {
                map.flyTo({ center: [lng, lat], zoom: 16 });
                marker.setLngLat([lng, lat]);
                reverseGeocode(lng, lat);
            } else {
                alert("Lokasi GPS Anda di luar wilayah Indonesia.");
            }
        }, function() {
            alert("Gagal mengakses lokasi GPS.");
        }, { enableHighAccuracy: true });
    }

    function reverseGeocode(lng, lat) {
        fetch(`https://api.maptiler.com/geocoding/${lng},${lat}.json?key=${MAPTILER_KEY}&language=id`)
            .then(res => res.json())
            .then(data => {
                const addressInput = document.getElementById('address');
                if (!addressInput) return;

                if (data && data.features && data.features.length > 0) {
                    let parts = [];
                    const mainFeature = data.features[0];
                    if (mainFeature.text) parts.push(mainFeature.text);
                    if (mainFeature.context) {
                        mainFeature.context.forEach(ctx => {
                            if (ctx.text && !parts.includes(ctx.text)) parts.push(ctx.text);
                        });
                    }
                    addressInput.value = parts.join(', ');
                }
            });
    }
</script>
@endsection