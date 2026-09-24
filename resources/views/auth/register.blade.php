@extends('layouts.app')

@section('content')
<div class="max-w-md mx-auto mt-10 bg-white p-6 rounded-xl shadow-md transition-all duration-500 ease-in-out transform hover:-translate-y-1 hover:shadow-xl animate__animated animate__fadeIn">
    <h2 class="text-2xl font-bold text-center mb-6 text-gray-800">Daftar Akun SHOPIN</h2>

    <form action="/register" method="POST" class="space-y-4">
        @csrf
        <div>
            <label class="block text-sm font-medium text-gray-700">Nama Lengkap</label>
            <input type="text" name="name" value="{{ old('name') }}" required class="w-full mt-1 p-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none transition">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Email</label>
            <input type="email" name="email" value="{{ old('email') }}" required class="w-full mt-1 p-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none transition">
        </div>

        <!-- Input Password -->
        <div>
            <label class="block text-sm font-medium text-gray-700">Password</label>
            <div class="relative mt-1">
                <input 
                    type="password" 
                    id="regPassword" 
                    name="password" 
                    required 
                    oninput="handlePasswordInput('regPassword', 'toggleRegPassword')"
                    class="w-full p-2 pr-10 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none transition"
                >
                <button 
                    type="button" 
                    id="toggleRegPassword" 
                    onclick="togglePassword('regPassword', 'eyeIconReg')" 
                    class="hidden absolute inset-y-0 right-0 pr-3 flex items-center text-gray-500 hover:text-indigo-600 focus:outline-none transition"
                >
                    <i id="eyeIconReg" class="fa-solid fa-eye"></i>
                </button>
            </div>
        </div>

        <!-- Input Konfirmasi Password -->
        <div>
            <label class="block text-sm font-medium text-gray-700">Konfirmasi Password</label>
            <div class="relative mt-1">
                <input 
                    type="password" 
                    id="regPasswordConfirm" 
                    name="password_confirmation" 
                    required 
                    oninput="handlePasswordInput('regPasswordConfirm', 'toggleRegPasswordConfirm')"
                    class="w-full p-2 pr-10 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none transition"
                >
                <button 
                    type="button" 
                    id="toggleRegPasswordConfirm" 
                    onclick="togglePassword('regPasswordConfirm', 'eyeIconRegConfirm')" 
                    class="hidden absolute inset-y-0 right-0 pr-3 flex items-center text-gray-500 hover:text-indigo-600 focus:outline-none transition"
                >
                    <i id="eyeIconRegConfirm" class="fa-solid fa-eye"></i>
                </button>
            </div>
        </div>

        <button type="submit" class="w-full bg-indigo-600 text-white py-2 rounded-lg font-semibold hover:bg-indigo-700 active:scale-95 transition-all duration-200">REGISTER</button>
    </form>

    <p class="text-xs text-center text-gray-500 mt-4">
        Sudah punya akun? <a href="/login" class="text-indigo-600 font-semibold underline hover:text-indigo-800 transition">Login di sini</a>
    </p>
</div>

<!-- Script Show/Hide & Auto Detect Input -->
<script>
    function handlePasswordInput(inputId, buttonId) {
        const input = document.getElementById(inputId);
        const btn = document.getElementById(buttonId);

        if (input.value.length > 0) {
            btn.classList.remove('hidden');
        } else {
            btn.classList.add('hidden');
        }
    }

    function togglePassword(inputId, iconId) {
        const input = document.getElementById(inputId);
        const icon = document.getElementById(iconId);

        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            input.type = 'password';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    }
</script>
@endsection