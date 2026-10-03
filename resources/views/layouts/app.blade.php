<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'SHOPIN') }}</title>

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Animate.css -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    
    <!-- FontAwesome Icon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- SweetAlert2 CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- CSS Overlay Loading & x-cloak -->
    <style>
        #global-loading-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(15, 23, 42, 0.6);
            backdrop-filter: blur(4px);
            z-index: 9999;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            color: white;
        }
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="bg-gray-100 flex flex-col min-h-screen">

    <!-- Navbar -->
    <nav class="bg-indigo-600 text-white shadow-md sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 py-3 flex flex-col md:flex-row items-center justify-between gap-4">
            
            <a href="/" class="text-2xl font-bold tracking-wider flex items-center gap-2">
                <i class="fa-solid fa-bag-shopping"></i> SHOPIN
            </a>

            <!-- Search Form -->
            <form action="/" method="GET" class="w-full md:w-1/2 flex items-center bg-white rounded-lg overflow-hidden px-2 py-1">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari produk di Shopin..." class="w-full px-3 py-1 text-gray-800 outline-none text-sm">
                @if(request('category'))
                    <input type="hidden" name="category" value="{{ request('category') }}">
                @endif
                <button type="submit" class="bg-indigo-600 text-white px-4 py-1.5 rounded-md hover:bg-indigo-700 text-sm">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </button>
            </form>

            <!-- Nav Links -->
            <div class="flex items-center gap-4 text-sm font-medium">
                @auth
                    <!-- Khusus Super Admin -->
                    @if(strtolower(Auth::user()->role) === 'super_admin')
                        <a href="/super-admin/dashboard" class="hover:underline text-amber-300 font-bold flex items-center gap-1">
                            <i class="fa-solid fa-user-shield"></i> Super Admin Panel
                        </a>
                    @endif

                    <!-- Khusus Admin Toko Saja -->
                    @if(strtolower(Auth::user()->role) === 'admin')
                        <a href="/admin/dashboard" class="hover:underline flex items-center gap-1">
                            <i class="fa-solid fa-gauge"></i> Kelola Toko
                        </a>
                    @endif

                    <!-- Khusus User / Pembeli Biasa -->
                    @if(strtolower(Auth::user()->role) === 'user')
                        <a href="/cart" class="hover:underline flex items-center gap-1">
                            <i class="fa-solid fa-cart-shopping"></i> Keranjang
                        </a>
                        <a href="/orders" class="hover:underline flex items-center gap-1">
                            <i class="fa-solid fa-box"></i> Pesanan
                        </a>
                    @endif

                    <!-- Badge User -->
                    <span class="text-xs bg-indigo-700 px-2 py-1 rounded">
                        <i class="fa-solid fa-user"></i> {{ Auth::user()->name }}
                    </span>

                    <!-- Form Logout -->
                    <form id="logout-form" action="/logout" method="POST" class="hidden">
                        @csrf
                    </form>

                    <!-- Tombol Logout -->
                    <button type="button" onclick="confirmLogout()" class="bg-red-500 hover:bg-red-600 px-3 py-1 rounded text-white text-xs transition">
                        Logout
                    </button>
                @else
                    <a href="/login" class="hover:underline"><i class="fa-solid fa-right-to-bracket"></i> Login</a>
                    <a href="/register" class="bg-white text-indigo-600 px-3 py-1.5 rounded-lg hover:bg-gray-100 transition"><i class="fa-solid fa-user-plus"></i> Register</a>
                @endauth
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="grow max-w-7xl w-full mx-auto p-4">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-gray-800 text-gray-300 py-4 text-center text-sm mt-8">
        &copy; 2026 SHOPIN. Project Pembelajaran Laravel.
    </footer>

    <!-- Global Loading Overlay -->
    <div id="global-loading-overlay">
        <div class="inline-block w-12 h-12 border-4 border-indigo-500 border-t-transparent rounded-full animate-spin mb-3"></div>
        <p class="text-sm font-semibold tracking-wide text-white" id="loading-text">Memproses...</p>
    </div>

    <!-- Scripts -->
    <script>
        function confirmLogout() {
            Swal.fire({
                title: 'Apakah Anda ingin logout?',
                text: 'Sesi Anda akan diakhiri.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Ya, Logout',
                cancelButtonText: 'Batal',
                customClass: {
                    popup: 'rounded-2xl',
                    confirmButton: 'rounded-xl font-bold px-4 py-2 text-sm',
                    cancelButton: 'rounded-xl font-bold px-4 py-2 text-sm'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'Keluar...',
                        text: 'Sedang memproses logout Anda.',
                        allowOutsideClick: false,
                        showConfirmButton: false,
                        willOpen: () => { Swal.showLoading(); }
                    });
                    document.getElementById('logout-form').submit();
                }
            });
        }

        function showGlobalLoading(text = 'Memproses...') {
            const overlay = document.getElementById('global-loading-overlay');
            const loadingText = document.getElementById('loading-text');
            if (overlay && loadingText) {
                loadingText.innerText = text;
                overlay.style.display = 'flex';
            }
        }

        function hideGlobalLoading() {
            const overlay = document.getElementById('global-loading-overlay');
            if (overlay) overlay.style.display = 'none';
        }

        document.addEventListener('DOMContentLoaded', function () {
            @if(session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: @json(session('success')),
                    showConfirmButton: false,
                    timer: 2500,
                    timerProgressBar: true,
                    showClass: { popup: 'animate__animated animate__fadeInDown' },
                    hideClass: { popup: 'animate__animated animate__fadeOutUp' }
                });
            @endif

            @if(session('error') || $errors->any())
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal!',
                    text: @json(session('error') ?? $errors->first()),
                    confirmButtonColor: '#4F46E5',
                    showClass: { popup: 'animate__animated animate__shakeX' }
                });
            @endif
        });
    </script>
</body>
</html>