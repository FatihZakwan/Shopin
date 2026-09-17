<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'SHOPIN') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
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
                    @if(Auth::user()->role === 'ADMIN')
                        <a href="/admin" class="hover:underline"><i class="fa-solid fa-gauge"></i> Dashboard</a>
                    @else
                        <a href="/cart" class="hover:underline flex items-center gap-1">
                            <i class="fa-solid fa-cart-shopping"></i> Keranjang
                        </a>
                        <a href="/orders" class="hover:underline flex items-center gap-1">
                            <i class="fa-solid fa-box"></i> Pesanan
                        </a>
                    @endif
                    <span class="text-xs bg-indigo-700 px-2 py-1 rounded"><i class="fa-solid fa-user"></i> {{ Auth::user()->name }}</span>
                    <form action="/logout" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="bg-red-500 hover:bg-red-600 px-3 py-1 rounded text-white text-xs">Logout</button>
                    </form>
                @else
                    <a href="/login" class="hover:underline"><i class="fa-solid fa-right-to-bracket"></i> Login</a>
                    <a href="/register" class="bg-white text-indigo-600 px-3 py-1.5 rounded-lg hover:bg-gray-100"><i class="fa-solid fa-user-plus"></i> Register</a>
                @endauth
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="flex-grow max-w-7xl w-full mx-auto p-4">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-gray-800 text-gray-300 py-4 text-center text-sm mt-8">
        &copy; 2026 SHOPIN. Project Pembelajaran Laravel.
    </footer>

</body>
</html>