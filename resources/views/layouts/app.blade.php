<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'SHOPIN') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-[#E3F2FD] text-[#0D47A1] flex flex-col min-h-screen">

    <!-- Navbar -->
    <nav class="bg-[#2196F3] text-white shadow-md sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 py-3 flex flex-col md:flex-row items-center justify-between gap-4">
            
            <a href="/" class="text-2xl font-bold tracking-wider flex items-center gap-2 text-white">
                <i class="fa-solid fa-bag-shopping"></i> SHOPIN
            </a>

            <!-- Search Form -->
            <form action="/" method="GET" class="w-full md:w-1/2 flex items-center bg-white rounded-lg overflow-hidden px-2 py-1 shadow-sm">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari produk di Shopin..." class="w-full px-3 py-1 text-[#0D47A1] outline-none text-sm bg-transparent">
                @if(request('category'))
                    <input type="hidden" name="category" value="{{ request('category') }}">
                @endif
                <button type="submit" class="bg-[#0D47A1] text-white px-4 py-1.5 rounded-md hover:bg-[#1565C0] text-sm">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </button>
            </form>

            <!-- Nav Links -->
            <div class="flex items-center gap-4 text-sm font-medium">
                @auth
                    @if(Auth::user()->role === 'ADMIN')
                        <a href="/admin" class="hover:underline text-white"><i class="fa-solid fa-gauge"></i> Dashboard</a>
                    @else
                        <a href="/cart" class="hover:underline flex items-center gap-1 text-white">
                            <i class="fa-solid fa-cart-shopping"></i> Keranjang
                        </a>
                        <a href="/orders" class="hover:underline flex items-center gap-1 text-white">
                            <i class="fa-solid fa-box"></i> Pesanan
                        </a>
                    @endif
                    <span class="text-xs bg-[#0D47A1] px-2 py-1 rounded"><i class="fa-solid fa-user"></i> {{ Auth::user()->name }}</span>
                    <form action="/logout" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="bg-[#E3F2FD] text-[#0D47A1] hover:bg-white px-3 py-1 rounded text-white text-xs font-semibold">Logout</button>
                    </form>
                @else
                    <a href="/login" class="hover:underline text-white"><i class="fa-solid fa-right-to-bracket"></i> Login</a>
                    <a href="/register" class="bg-white text-[#0D47A1] px-3 py-1.5 rounded-lg hover:bg-[#E3F2FD] font-semibold"><i class="fa-solid fa-user-plus"></i> Register</a>
                @endauth
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="flex-grow max-w-7xl w-full mx-auto p-4">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-[#0D47A1] text-[#E3F2FD] py-4 text-center text-sm mt-8">
        &copy; 2026 SHOPIN. Project Pembelajaran Laravel.
    </footer>

</body>
</html>